<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/tasks')->assertRedirect('/login');
    }

    public function test_pages_render(): void
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->for(Project::factory()->for($user))->count(3)->create();

        $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee('لوحة التحكم');
        $this->actingAs($user)->get('/tasks')->assertOk();
        $this->actingAs($user)->get('/tasks?view=board')->assertOk()->assertSee('قيد التنفيذ');
        $this->actingAs($user)->get('/projects')->assertOk();
    }

    public function test_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/tasks', [
            'title' => 'مهمة جديدة', 'status' => 'todo', 'priority' => 'high', 'due_date' => '2026-12-01',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tasks', ['user_id' => $user->id, 'title' => 'مهمة جديدة', 'priority' => 'high']);
    }

    public function test_validation_messages_are_arabic(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/tasks', ['status' => 'todo', 'priority' => 'low'])
            ->assertSessionHasErrors(['title' => 'حقل العنوان مطلوب.']);
    }

    public function test_user_can_update_and_move_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['status' => 'todo']);

        $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'معدّلة', 'status' => 'in_progress', 'priority' => 'low',
        ])->assertSessionHasNoErrors();
        $this->assertSame('معدّلة', $task->fresh()->title);

        $this->actingAs($user)->patchJson("/tasks/{$task->id}/status", ['status' => 'done', 'order' => [$task->id]])
            ->assertOk();
        $this->assertSame('done', $task->fresh()->status->value);
    }

    public function test_user_cannot_touch_other_users_tasks(): void
    {
        $task = Task::factory()->create();
        $intruder = User::factory()->create();

        $this->actingAs($intruder)->delete("/tasks/{$task->id}")->assertForbidden();
        $this->actingAs($intruder)->patch("/tasks/{$task->id}/status", ['status' => 'done'])->assertForbidden();
        $this->assertModelExists($task);
    }

    public function test_cannot_assign_task_to_someone_elses_project(): void
    {
        $user = User::factory()->create();
        $foreignProject = Project::factory()->create();

        $this->actingAs($user)->post('/tasks', [
            'title' => 'x', 'status' => 'todo', 'priority' => 'low', 'project_id' => $foreignProject->id,
        ])->assertSessionHasErrors('project_id');
    }

    public function test_user_can_manage_projects(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/projects', ['name' => 'مشروعي', 'color' => '#22c55e'])->assertSessionHasNoErrors();
        $project = $user->projects()->first();
        $task = Task::factory()->for($user)->create(['project_id' => $project->id]);

        $this->actingAs($user)->delete("/projects/{$project->id}");
        $this->assertModelMissing($project);
        $this->assertNull($task->fresh()->project_id); // المهمة تبقى
    }
}
