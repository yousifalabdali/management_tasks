{{-- إشعارات منبثقة: تظهر رسائل الجلسة، ويمكن إطلاقها من JS عبر $dispatch('toast', {message}) --}}
<div x-data="{
        items: [],
        add(message, type = 'success') {
            const id = Date.now() + Math.random();
            this.items.push({ id, message, type });
            setTimeout(() => this.items = this.items.filter(i => i.id !== id), 3500);
        }
     }"
     x-init="
        @if (session('success')) add(@js(session('success'))); @endif
        @if (session('status') === 'profile-updated') add('تم حفظ الملف الشخصي'); @endif
        @if (session('status') === 'password-updated') add('تم تغيير كلمة المرور'); @endif
        @if ($errors->any()) add('يرجى مراجعة الحقول', 'error'); @endif
     "
     @toast.window="add($event.detail.message, $event.detail.type)"
     class="fixed bottom-6 left-6 z-50 flex flex-col gap-2">
    <template x-for="item in items" :key="item.id">
        <div x-transition.opacity.duration.300ms
             :class="item.type === 'error' ? 'bg-rose-600' : 'bg-slate-900 dark:bg-white dark:text-slate-900'"
             class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium text-white shadow-xl">
            <span x-text="item.message"></span>
        </div>
    </template>
</div>
