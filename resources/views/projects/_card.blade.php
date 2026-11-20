@php $progress = $project->progress(); @endphp
<a href="{{ route('projects.show', $project) }}" class="card group block overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="h-1.5" style="background: {{ $project->color }}"></div>
    <div class="p-5">
        <div class="flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-xl text-white" style="background: {{ $project->color }}">
                @include('partials.icon', ['name' => 'folder'])
            </div>
            <div class="min-w-0">
                <p class="truncate font-bold group-hover:text-brand-600">{{ $project->name }}</p>
                <p class="text-xs text-slate-500">{{ $project->tasks_count }} مهمة @if($project->description)· {{ \Illuminate\Support\Str::limit($project->description, 30) }}@endif</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="mb-1.5 flex justify-between text-xs text-slate-500">
                <span>التقدّم</span><span class="font-semibold">{{ $progress }}%</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full rounded-full transition-all" style="width: {{ $progress }}%; background: {{ $project->color }}"></div>
            </div>
        </div>
    </div>
</a>
