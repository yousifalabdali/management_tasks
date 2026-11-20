import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;

/**
 * لوحة Kanban: سحب المهمة بين الأعمدة يحفظ الحالة والترتيب في الخادم.
 * الاستخدام: <div x-data="kanban('{{ url('/tasks') }}')"> وكل عمود فيه data-status
 */
Alpine.data('kanban', (baseUrl) => ({
    init() {
        this.$el.querySelectorAll('[data-status]').forEach((column) => {
            Sortable.create(column, {
                group: 'tasks',
                animation: 180,
                ghostClass: 'sortable-ghost',
                onEnd: (evt) => this.save(evt),
            });
        });
    },
    async save(evt) {
        const column = evt.to;
        const order = [...column.children].map((el) => Number(el.dataset.id)).filter(Boolean);
        try {
            const res = await fetch(`${baseUrl}/${evt.item.dataset.id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ status: column.dataset.status, order }),
            });
            if (!res.ok) throw new Error(res.status);
            this.$dispatch('toast', { message: 'تم نقل المهمة' });
            this.$dispatch('counts-changed');
        } catch {
            this.$dispatch('toast', { message: 'تعذّر الحفظ، أعد تحميل الصفحة', type: 'error' });
        }
    },
}));

/** الوضع الداكن: يُحفظ في المتصفح */
Alpine.data('theme', () => ({
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        try { localStorage.setItem('theme', this.dark ? 'dark' : 'light'); } catch {}
    },
}));

Alpine.start();
