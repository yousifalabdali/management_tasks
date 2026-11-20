<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $tasks = $request->user()->tasks();

        $stats = [
            'total' => (clone $tasks)->count(),
            'in_progress' => (clone $tasks)->where('status', TaskStatus::InProgress)->count(),
            'done' => (clone $tasks)->where('status', TaskStatus::Done)->count(),
            'overdue' => (clone $tasks)->overdue()->count(),
        ];
        $stats['progress'] = $stats['total'] ? (int) round($stats['done'] / $stats['total'] * 100) : 0;

        // مهام اليوم والمتأخرة غير المنجزة
        $todayTasks = (clone $tasks)->with('project')
            ->where('status', '!=', TaskStatus::Done)
            ->whereDate('due_date', '<=', today())
            ->orderBy('due_date')->limit(8)->get();

        $recentTasks = (clone $tasks)->with('project')->latest()->limit(5)->get();

        $projects = $request->user()->projects()
            ->withCount(['tasks', 'doneTasks'])->latest()->limit(4)->get();

        return view('dashboard', compact('stats', 'todayTasks', 'recentTasks', 'projects'));
    }
}
