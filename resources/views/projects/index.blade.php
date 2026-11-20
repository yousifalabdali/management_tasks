<x-app-layout>
    <x-slot name="header">المشاريع</x-slot>
    <x-slot name="actions">
        <button @click="$dispatch('project-form')" class="btn-primary">
            @include('partials.icon', ['name' => 'plus']) <span class="hidden sm:inline">مشروع جديد</span>
        </button>
    </x-slot>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($projects as $project)
            <div class="relative">
                @include('projects._card')
                <div class="absolute left-3 top-4 flex gap-1">
                    <button @click="$dispatch('project-form', @js($project->only('id', 'name', 'color', 'description')))"
                            class="rounded-lg bg-white/80 p-1.5 text-slate-500 backdrop-blur hover:text-brand-600 dark:bg-slate-800/80" title="تعديل">
                        @include('partials.icon', ['name' => 'edit', 'class' => 'h-4 w-4'])
                    </button>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('حذف المشروع؟ ستبقى مهامه بدون مشروع.')">
                        @csrf @method('DELETE')
                        <button class="rounded-lg bg-white/80 p-1.5 text-slate-500 backdrop-blur hover:text-rose-600 dark:bg-slate-800/80" title="حذف">
                            @include('partials.icon', ['name' => 'trash', 'class' => 'h-4 w-4'])
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card col-span-full px-6 py-16 text-center">
                <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/10">
                    @include('partials.icon', ['name' => 'folder', 'class' => 'h-7 w-7'])
                </div>
                <p class="font-semibold">لا توجد مشاريع بعد</p>
                <p class="mt-1 text-sm text-slate-500">المشاريع تساعدك على تجميع المهام المرتبطة ببعض</p>
                <button @click="$dispatch('project-form')" class="btn-primary mt-5">@include('partials.icon', ['name' => 'plus']) مشروع جديد</button>
            </div>
        @endforelse
    </div>

    {{-- نافذة إضافة/تعديل مشروع --}}
    @php $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#ef4444', '#f97316', '#eab308', '#22c55e', '#14b8a6', '#0ea5e9', '#64748b']; @endphp
    <div x-data="{ open: false, p: {}, show(d) { this.p = { id: null, name: '', color: '#6366f1', description: '', ...(d || {}) }; this.open = true; } }"
         @project-form.window="show($event.detail)" @keydown.escape.window="open = false"
         x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center">
        <div @click="open = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <form method="POST" :action="p.id ? '{{ url('projects') }}/' + p.id : '{{ route('projects.store') }}'" x-show="open" x-transition class="card relative w-full max-w-md p-6">
            @csrf
            <template x-if="p.id"><input type="hidden" name="_method" value="PUT"></template>
            <h2 class="mb-5 text-lg font-bold" x-text="p.id ? 'تعديل المشروع' : 'مشروع جديد'"></h2>
            <div class="space-y-4">
                <div>
                    <label class="label" for="name">اسم المشروع</label>
                    <input id="name" name="name" x-model="p.name" required maxlength="100" class="input" placeholder="مثال: إطلاق الموقع">
                </div>
                <div>
                    <span class="label">اللون</span>
                    <input type="hidden" name="color" :value="p.color">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($colors as $c)
                            <button type="button" @click="p.color = '{{ $c }}'" style="background: {{ $c }}"
                                    :class="p.color === '{{ $c }}' ? 'ring-2 ring-offset-2 ring-slate-400 dark:ring-offset-slate-900' : ''"
                                    class="h-8 w-8 rounded-full transition hover:scale-110" aria-label="{{ $c }}"></button>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="label" for="pdesc">الوصف</label>
                    <textarea id="pdesc" name="description" x-model="p.description" rows="3" class="input"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="open = false" class="btn-ghost">إلغاء</button>
                <button class="btn-primary" x-text="p.id ? 'حفظ' : 'إنشاء المشروع'"></button>
            </div>
        </form>
    </div>
</x-app-layout>
