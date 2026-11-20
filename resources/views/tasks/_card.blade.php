{{-- بطاقة مهمة (تُستخدم في Kanban ولوحة التحكم) --}}
@php
    $payload = [
        'id' => $task->id, 'title' => $task->title, 'description' => $task->description,
        'status' => $task->status->value, 'priority' => $task->priority->value,
        'due_date' => $task->due_date?->format('Y-m-d') ?? '', 'project_id' => $task->project_id ?? '',
    ];
@endphp
<div data-id="{{ $task->id }}" @click="$dispatch('task-form', @js($payload))"
     class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand-500/50">
    <div class="flex items-start justify-between gap-2">
        <p class="font-semibold leading-snug {{ $task->status->value === 'done' ? 'text-slate-400 line-through' : '' }}">{{ $task->title }}</p>
        <span class="badge shrink-0 {{ $task->priority->badge() }}">{{ $task->priority->label() }}</span>
    </div>
    @if ($task->description)
        <p class="mt-1.5 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">{{ $task->description }}</p>
    @endif
    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-slate-500">
        @if ($task->project)
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full" style="background: {{ $task->project->color }}"></span>{{ $task->project->name }}
            </span>
        @endif
        @if ($task->due_date)
            <span class="inline-flex items-center gap-1 {{ $task->isOverdue() ? 'font-semibold text-rose-600' : '' }}">
                @include('partials.icon', ['name' => 'calendar', 'class' => 'h-3.5 w-3.5'])
                {{ $task->due_date->translatedFormat('j F') }}
            </span>
        @endif
    </div>
</div>
