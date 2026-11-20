<x-app-layout>
    <x-slot name="header">لوحة التحكم</x-slot>
    <x-slot name="actions">
        <button @click="$dispatch('task-form')" class="btn-primary">
            @include('partials.icon', ['name' => 'plus']) <span class="hidden sm:inline">مهمة جديدة</span>
        </button>
    </x-slot>

    <div class="mb-8">
        <h2 class="text-2xl font-bold">أهلًا {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
        <p class="mt-1 text-slate-500">{{ now()->translatedFormat('l، j F Y') }} — هذا ملخص مهامك.</p>
    </div>

    {{-- بطاقات الإحصائيات --}}
    @php
        $cards = [
            ['label' => 'كل المهام', 'value' => $stats['total'], 'icon' => 'tasks', 'tone' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400', 'url' => route('tasks.index')],
            ['label' => 'قيد التنفيذ', 'value' => $stats['in_progress'], 'icon' => 'clock', 'tone' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400', 'url' => route('tasks.index', ['status' => 'in_progress'])],
            ['label' => 'منجزة', 'value' => $stats['done'], 'icon' => 'check', 'tone' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400', 'url' => route('tasks.index', ['status' => 'done'])],
            ['label' => 'متأخرة', 'value' => $stats['overdue'], 'icon' => 'alert', 'tone' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400', 'url' => route('tasks.index')],
        ];
    @endphp
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($cards as $c)
            <a href="{{ $c['url'] }}" class="card p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="mb-4 grid h-10 w-10 place-items-center rounded-xl {{ $c['tone'] }}">
                    @include('partials.icon', ['name' => $c['icon']])
                </div>
                <p class="text-3xl font-bold tabular-nums">{{ $c['value'] }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ $c['label'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        {{-- مهام اليوم --}}
        <section class="card p-6 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-bold">مهام اليوم والمتأخرة</h3>
                <a href="{{ route('tasks.index', ['view' => 'board']) }}" class="text-sm font-medium text-brand-600 hover:underline">عرض اللوحة</a>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                @forelse ($todayTasks as $task)
                    @include('tasks._card')
                @empty
                    <div class="col-span-full rounded-xl bg-emerald-50 p-6 text-center text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                        🎉 لا توجد مهام مستحقة اليوم، عمل رائع!
                    </div>
                @endforelse
            </div>
        </section>

        {{-- نسبة الإنجاز --}}
        <section class="card flex flex-col items-center justify-center p-6 text-center">
            <h3 class="mb-4 self-start font-bold">نسبة الإنجاز</h3>
            @php $r = 52; $circ = 2 * pi() * $r; @endphp
            <svg viewBox="0 0 120 120" class="h-40 w-40 -rotate-90">
                <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke-width="12" class="stroke-slate-100 dark:stroke-slate-800"/>
                <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke-width="12" stroke-linecap="round" class="stroke-brand-500 transition-all duration-700"
                        stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $circ * (1 - $stats['progress'] / 100) }}"/>
            </svg>
            <p class="-mt-[104px] mb-[72px] text-3xl font-bold">{{ $stats['progress'] }}%</p>
            <p class="text-sm text-slate-500">أنجزت {{ $stats['done'] }} من {{ $stats['total'] }} مهمة</p>
        </section>
    </div>

    {{-- المشاريع --}}
    <section class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-bold">المشاريع</h3>
            <a href="{{ route('projects.index') }}" class="text-sm font-medium text-brand-600 hover:underline">كل المشاريع</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($projects as $project)
                @include('projects._card')
            @empty
                <a href="{{ route('projects.index') }}" class="card col-span-full p-6 text-center text-slate-500 hover:text-brand-600">أنشئ أول مشروع لتنظيم مهامك ←</a>
            @endforelse
        </div>
    </section>

    @include('tasks._form-modal', ['projects' => auth()->user()->projects()->orderBy('name')->get()])
</x-app-layout>
