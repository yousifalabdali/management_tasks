<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** مستخدم تجريبي مع مشاريع ومهام واقعية */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'مستخدم تجريبي',
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        $data = [
            ['إطلاق الموقع الجديد', '#6366f1', 'إعادة تصميم موقع الشركة وإطلاقه', [
                ['تصميم الصفحة الرئيسية', 'done', 'high', -6],
                ['كتابة محتوى صفحة "من نحن"', 'in_progress', 'medium', 1],
                ['ربط نموذج التواصل بالبريد', 'todo', 'high', 0],
                ['اختبار الموقع على الجوال', 'todo', 'medium', 5],
                ['تحسين سرعة التحميل', 'todo', 'low', 10],
            ]],
            ['حملة التسويق', '#ec4899', 'حملة إطلاق المنتج على وسائل التواصل', [
                ['تجهيز تقويم المحتوى', 'done', 'medium', -3],
                ['تصميم 6 منشورات', 'in_progress', 'high', 2],
                ['التنسيق مع المؤثرين', 'todo', 'medium', -1],
                ['إعداد تقرير الأداء الأسبوعي', 'todo', 'low', 7],
            ]],
            ['شخصي', '#22c55e', null, [
                ['قراءة كتاب "العادات الذرية"', 'in_progress', 'low', 14],
                ['تجديد الاشتراك في النادي', 'todo', 'medium', 0],
                ['حجز موعد الطبيب', 'done', 'high', -2],
            ]],
        ];

        foreach ($data as [$name, $color, $description, $tasks]) {
            $project = $user->projects()->create(compact('name', 'color', 'description'));

            foreach ($tasks as $i => [$title, $status, $priority, $days]) {
                $user->tasks()->create([
                    'project_id' => $project->id,
                    'title' => $title,
                    'status' => $status,
                    'priority' => $priority,
                    'due_date' => today()->addDays($days),
                    'position' => $i,
                ]);
            }
        }

        $user->tasks()->create(['title' => 'الرد على رسائل البريد', 'status' => 'todo', 'priority' => 'medium', 'due_date' => today()]);
    }
}
