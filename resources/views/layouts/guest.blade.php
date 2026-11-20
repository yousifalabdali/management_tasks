<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('partials.head')
</head>
<body class="font-sans text-slate-800 antialiased dark:text-slate-200">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="flex items-center justify-center bg-white p-6 dark:bg-slate-950">
            <div class="w-full max-w-sm">
                <div class="mb-8 flex items-center gap-3">
                    <div class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-violet-500 text-white">
                        @include('partials.icon', ['name' => 'check'])
                    </div>
                    <span class="text-xl font-bold">{{ config('app.name') }}</span>
                </div>
                {{ $slot }}
            </div>
        </div>
        <div class="relative hidden overflow-hidden bg-gradient-to-br from-brand-600 via-violet-600 to-fuchsia-600 lg:flex lg:items-center lg:justify-center">
            <div class="absolute -left-20 -top-20 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-10 h-96 w-96 rounded-full bg-fuchsia-300/20 blur-3xl"></div>
            <div class="relative max-w-md p-10 text-white">
                <h2 class="text-4xl font-bold leading-tight">نظّم مهامك،<br>وأنجز أكثر.</h2>
                <p class="mt-4 text-lg text-white/80">خطط لمشاريعك، وتابع تقدّمك، وحرّك مهامك بين المراحل بسحبة واحدة.</p>
                <div class="mt-10 space-y-3">
                    @foreach (['لوحة Kanban بالسحب والإفلات', 'مشاريع ملوّنة ونسب إنجاز', 'تنبيه للمهام المتأخرة'] as $f)
                        <div class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 backdrop-blur">
                            @include('partials.icon', ['name' => 'check'])
                            <span>{{ $f }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
