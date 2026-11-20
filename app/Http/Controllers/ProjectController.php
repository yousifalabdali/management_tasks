<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()->projects()
            ->withCount(['tasks', 'doneTasks'])->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // صفحة المشروع = لوحة المهام مفلترة على هذا المشروع
        return redirect()->route('tasks.index', ['project' => $project->id, 'view' => 'board']);
    }

    public function store(ProjectRequest $request)
    {
        $request->user()->projects()->create($request->validated());

        return back()->with('success', 'تم إنشاء المشروع');
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->update($request->validated());

        return back()->with('success', 'تم تحديث المشروع');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete(); // المهام تبقى بدون مشروع (nullOnDelete)

        return back()->with('success', 'تم حذف المشروع');
    }
}
