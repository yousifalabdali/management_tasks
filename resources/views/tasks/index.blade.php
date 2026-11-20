@php use App\Enums\{TaskStatus, TaskPriority}; @endphp
<x-app-layout>
    <x-slot name="header">المهام</x-slot>
    <x-slot name="actions">
        <button @click="$dispatch('task-form')" class="btn-primary">
            @include('partials.icon', ['name' => 'plus']) <span class="hidden sm:inline">مهمة جديدة</span>
        </button>
    </x-slot>

    {{-- شريط الفلترة --}}
    <form method="GET" class="card mb-6 flex flex-wrap items-center gap-3 p-3">
        <input type="hidden" name="view" value="{{ $view }}">
        <div class="relative min-w-[200px] flex-1">
            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">@include('partials.icon', ['name' => 'search', 'class' => 'h-4 w-4'])</span>
            <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="ابحث في المهام…" class="input pr-9">
        </div>
        @if ($view === 'list')
            <select name="status" class="input w-auto" onchange="this.form.submit()">
                <option value="">كل الحالات</option>
                @foreach (TaskStatus::cases() as $s)<option value="{{ $s->value }}" @selected(($filters['status'] ?? '') === $s->value)>{{ $s->label() }}</option>@endforeach
            </select>
        @endif
        <select name="priority" class="input w-auto" onchange="this.form.submit()">
            <option value="">كل الأولويات</option>
            @foreach (TaskPriority::cases() as $p)<option value="{{ $p->value }}" @selected(($filters['priority'] ?? '') === $p->value)>{{ $p->label() }}</option>@endforeach
        </select>
        <select name="project" class="input w-auto" onchange="this.form.submit()">
            <option value="">كل المشاريع</option>
            @foreach ($projects as $p)<option value="{{ $p->id }}" @selected(($filters['project'] ?? '') == $p->id)>{{ $p->name }}</option>@endforeach
        </select>
        @if (array_filter($filters))
            <a href="{{ route('tasks.index', ['view' => $view]) }}" class="btn-ghost">مسح الفلاتر</a>
        @endif

        {{-- تبديل العرض --}}
        <div class="flex rounded-xl bg-slate-100 p-1 dark:bg-slate-800">
            @foreach (['list' => 'قائمة', 'board' => 'لوحة'] as $key => $label)
                <a href="{{ route('tasks.index', array_merge($filters, ['view' => $key])) }}"
                   class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium {{ $view === $key ? 'bg-white shadow-sm dark:bg-slate-700' : 'text-slate-500' }}">
                    @include('partials.icon', ['name' => $key, 'class' => 'h-4 w-4']) {{ $label }}
                </a>
            @endforeach
        </div>
    </form>

    @if ($view === 'board')
        {{-- عرض Kanban --}}
        <div x-data="kanban('{{ url('tasks') }}')" class="grid gap-5 md:grid-cols-3">
            @foreach (TaskStatus::cases() as $status)
                @php $column = $tasks[$status->value] ?? collect(); @endphp
                <section class="rounded-2xl bg-slate-100/70 p-3 dark:bg-slate-900/50">
                    <header class="mb-3 flex items-center justify-between px-1">
                        <span class="badge {{ $status->badge() }} text-sm">{{ $status->label() }}</span>
                        <span class="text-sm text-slate-400">{{ $column->count() }}</span>
                    </header>
                    <div data-status="{{ $status->value }}" class="min-h-[200px] space-y-3">
                        @foreach ($column as $task)
                            @include('tasks._card')
                        @endforeach
                    </div>
                    <button @click="$dispatch('task-form', { status: '{{ $status->value }}' })"
                            class="mt-3 flex w-full items-center justify-center gap-1 rounded-xl border-2 border-dashed border-slate-300 py-2 text-sm text-slate-500 hover:border-brand-400 hover:text-brand-600 dark:border-slate-700">
                        @include('partials.icon', ['name' => 'plus', 'class' => 'h-4 w-4']) إضافة
                    </button>
                </section>
            @endforeach
        </div>
    @else
        {{-- عرض القائمة --}}
        <div class="card divide-y divide-slate-100 overflow-hidden dark:divide-slate-800">
            @forelse ($tasks as $task)
                @php
                    $payload = ['id' => $task->id, 'title' => $task->title, 'description' => $task->description,
                        'status' => $task->status->value, 'priority' => $task->priority->value,
                        'due_date' => $task->due_date?->format('Y-m-d') ?? '', 'project_id' => $task->project_id ?? ''];
                    $done = $task->status === TaskStatus::Done;
                @endphp
                <div class="flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                    {{-- زر الإنجاز السريع --}}
                    <form method="POST" action="{{ route('tasks.status', $task) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $done ? 'todo' : 'done' }}">
                        <button title="{{ $done ? 'إعادة فتح' : 'تحديد كمنجزة' }}"
                                class="grid h-6 w-6 place-items-center rounded-full border-2 transition {{ $done ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 hover:border-emerald-500 dark:border-slate-600' }}">
                            @if ($done) @include('partials.icon', ['name' => 'check', 'class' => 'h-3.5 w-3.5']) @endif
                        </button>
                    </form>

                    <button class="min-w-0 flex-1 text-right" @click="$dispatch('task-form', @js($payload))">
                        <p class="truncate font-medium {{ $done ? 'text-slate-400 line-through' : '' }}">{{ $task->title }}</p>
                        <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                            @if ($task->project)
                                <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full" style="background: {{ $task->project->color }}"></span>{{ $task->project->name }}</span>
                            @endif
                            @if ($task->due_date)
                                <span class="{{ $task->isOverdue() ? 'font-semibold text-rose-600' : '' }}">
                                    {{ $task->isOverdue() ? 'متأخرة · ' : '' }}{{ $task->due_date->translatedFormat('j F Y') }}
                                </span>
                            @endif
                        </div>
                    </button>

                    <span class="badge hidden sm:inline-flex {{ $task->status->badge() }}">{{ $task->status->label() }}</span>
                    <span class="badge {{ $task->priority->badge() }}">{{ $task->priority->label() }}</span>

                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('حذف هذه المهمة؟')">
                        @csrf @method('DELETE')
                        <button class="rounded-lg p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10" title="حذف">
                            @include('partials.icon', ['name' => 'trash', 'class' => 'h-4 w-4'])
                        </button>
                    </form>
                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/10">
                        @include('partials.icon', ['name' => 'tasks', 'class' => 'h-7 w-7'])
                    </div>
                    <p class="font-semibold">لا توجد مهام</p>
                    <p class="mt-1 text-sm text-slate-500">ابدأ بإضافة أول مهمة لك</p>
                    <button @click="$dispatch('task-form')" class="btn-primary mt-5">@include('partials.icon', ['name' => 'plus']) مهمة جديدة</button>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $tasks->links() }}</div>
    @endif

    @include('tasks._form-modal', ['defaultProject' => $filters['project'] ?? ''])
</x-app-layout>
