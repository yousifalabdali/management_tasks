{{--
    نافذة إضافة/تعديل مهمة (واحدة للصفحة كاملة).
    افتحها من أي زر عبر: $dispatch('task-form', { ...بيانات المهمة }) أو $dispatch('task-form') لمهمة جديدة.
--}}
@php use App\Enums\{TaskStatus, TaskPriority}; @endphp
<div x-data="{
        open: false,
        task: {},
        blank: { id: null, title: '', description: '', status: 'todo', priority: 'medium', due_date: '', project_id: @js($defaultProject ?? '') },
        show(data) { this.task = { ...this.blank, ...(data || {}) }; this.open = true; this.$nextTick(() => this.$refs.title.focus()); },
     }"
     @task-form.window="show($event.detail)"
     @keydown.escape.window="open = false"
     x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center">
    <div x-show="open" x-transition.opacity @click="open = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <form method="POST" :action="task.id ? '{{ url('tasks') }}/' + task.id : '{{ route('tasks.store') }}'"
          x-show="open" x-transition class="card relative w-full max-w-lg p-6">
        @csrf
        <template x-if="task.id"><input type="hidden" name="_method" value="PUT"></template>

        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-bold" x-text="task.id ? 'تعديل المهمة' : 'مهمة جديدة'"></h2>
            <button type="button" @click="open = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                @include('partials.icon', ['name' => 'x'])
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label class="label" for="title">العنوان</label>
                <input id="title" name="title" x-ref="title" x-model="task.title" required maxlength="255" class="input" placeholder="مثال: تجهيز عرض الاجتماع">
            </div>
            <div>
                <label class="label" for="description">الوصف</label>
                <textarea id="description" name="description" x-model="task.description" rows="3" class="input" placeholder="تفاصيل اختيارية…"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label" for="status">الحالة</label>
                    <select id="status" name="status" x-model="task.status" class="input">
                        @foreach (TaskStatus::cases() as $s)<option value="{{ $s->value }}">{{ $s->label() }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="priority">الأولوية</label>
                    <select id="priority" name="priority" x-model="task.priority" class="input">
                        @foreach (TaskPriority::cases() as $p)<option value="{{ $p->value }}">{{ $p->label() }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="due_date">تاريخ التسليم</label>
                    <input id="due_date" type="date" name="due_date" x-model="task.due_date" class="input">
                </div>
                <div>
                    <label class="label" for="project_id">المشروع</label>
                    <select id="project_id" name="project_id" x-model="task.project_id" class="input">
                        <option value="">بدون مشروع</option>
                        @foreach ($projects as $project)<option value="{{ $project->id }}">{{ $project->name }}</option>@endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" @click="open = false" class="btn-ghost">إلغاء</button>
            <button class="btn-primary" x-text="task.id ? 'حفظ التعديلات' : 'إضافة المهمة'"></button>
        </div>
    </form>
</div>
