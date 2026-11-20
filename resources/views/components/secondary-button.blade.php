<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-ghost border border-slate-300 dark:border-slate-700']) }}>
    {{ $slot }}
</button>
