<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('partials.head')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200" x-data="{ sidebar: false }">

    {{-- الشريط الجانبي --}}
    <aside :class="sidebar ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 right-0 z-40 flex w-64 flex-col border-l border-slate-200 bg-white transition-transform dark:border-slate-800 dark:bg-slate-900">
        <div class="flex h-16 items-center gap-3 px-6">
            <div class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-violet-500 text-white shadow-lg shadow-brand-500/30">
                @include('partials.icon', ['name' => 'check'])
            </div>
            <span class="text-lg font-bold">{{ config('app.name') }}</span>
        </div>

        @php
            $links = [
                ['route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'home', 'label' => 'لوحة التحكم'],
                ['route' => 'tasks.index', 'match' => 'tasks.*', 'icon' => 'tasks', 'label' => 'المهام'],
                ['route' => 'projects.index', 'match' => 'projects.*', 'icon' => 'folder', 'label' => 'المشاريع'],
                ['route' => 'profile.edit', 'match' => 'profile.*', 'icon' => 'user', 'label' => 'الملف الشخصي'],
            ];
        @endphp
        <nav class="flex-1 space-y-1 px-3 py-4">
            @foreach ($links as $link)
                @php $active = request()->routeIs($link['match']); @endphp
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                          {{ $active ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    @include('partials.icon', ['name' => $link['icon']])
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-slate-200 p-4 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-full bg-brand-100 font-bold text-brand-700 dark:bg-brand-500/20 dark:text-brand-400">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-rose-600 dark:hover:bg-slate-800" title="تسجيل الخروج">
                        @include('partials.icon', ['name' => 'logout'])
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <div x-show="sidebar" x-cloak @click="sidebar = false" class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

    {{-- المحتوى --}}
    <div class="lg:pr-64">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-slate-200 bg-white/80 px-4 backdrop-blur sm:px-8 dark:border-slate-800 dark:bg-slate-900/80">
            <button @click="sidebar = true" class="rounded-lg p-2 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800">
                @include('partials.icon', ['name' => 'menu'])
            </button>
            <h1 class="flex-1 text-lg font-bold">{{ $header ?? '' }}</h1>
            <div x-data="theme">
                <button @click="toggle" class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" title="تبديل الوضع">
                    <span x-show="!dark">@include('partials.icon', ['name' => 'moon'])</span>
                    <span x-show="dark" x-cloak>@include('partials.icon', ['name' => 'sun'])</span>
                </button>
            </div>
            {{ $actions ?? '' }}
        </header>

        <main class="mx-auto max-w-7xl p-4 sm:p-8">
            {{ $slot }}
        </main>
    </div>

    @include('partials.toasts')
</body>
</html>
