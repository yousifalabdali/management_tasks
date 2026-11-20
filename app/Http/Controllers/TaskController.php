<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /** قائمة المهام (عرض قائمة أو Kanban) مع الفلترة */
    public function index(Request $request)
    {
        $filters = $request->only('search', 'status', 'priority', 'project');
        $view = $request->query('view') === 'board' ? 'board' : 'list';

        $query = $request->user()->tasks()->with('project')->filter($filters);

        $tasks = $view === 'board'
            ? $query->orderBy('position')->latest()->get()->groupBy(fn ($t) => $t->status->value)
            : $query->orderByRaw("status = 'done'")->orderBy('due_date')->latest()->paginate(15)->withQueryString();

        $projects = $request->user()->projects()->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'projects', 'filters', 'view'));
    }

    public function store(TaskRequest $request)
    {
        $request->user()->tasks()->create($request->validated());

        return back()->with('success', 'تمت إضافة المهمة بنجاح');
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());

        return back()->with('success', 'تم تحديث المهمة');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return back()->with('success', 'تم حذف المهمة');
    }

    /** تغيير الحالة والترتيب (يُستدعى من السحب والإفلات أو زر الإنجاز) */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'order' => ['array'],          // ترتيب المعرّفات داخل العمود
            'order.*' => ['integer'],
        ]);

        $task->update(['status' => $data['status']]);

        foreach ($data['order'] ?? [] as $position => $id) {
            $request->user()->tasks()->whereKey($id)->update(['position' => $position]);
        }

        return $request->wantsJson()
            ? response()->json(['ok' => true])
            : back()->with('success', 'تم تحديث الحالة');
    }
}
