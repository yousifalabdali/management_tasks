<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';

    /** الاسم الظاهر للمستخدم */
    public function label(): string
    {
        return match ($this) {
            self::Todo => 'قيد الانتظار',
            self::InProgress => 'قيد التنفيذ',
            self::Done => 'منجزة',
        };
    }

    /** ألوان الشارة (Tailwind) */
    public function badge(): string
    {
        return match ($this) {
            self::Todo => 'bg-slate-100 text-slate-700 dark:bg-slate-700/50 dark:text-slate-200',
            self::InProgress => 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300',
            self::Done => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300',
        };
    }
}
