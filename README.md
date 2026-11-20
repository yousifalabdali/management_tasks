# مهامي — تطبيق إدارة المهام (Laravel)

تطبيق ويب عربي لإدارة المهام والمشاريع، مبني بـ Laravel 13 و Blade و Tailwind و Alpine.js.

## المزايا
- لوحة تحكم فيها إحصائيات ونسبة الإنجاز ومهام اليوم والمهام المتأخرة
- عرض المهام كقائمة أو كلوحة **Kanban** تنقل فيها المهام بالسحب والإفلات
- بحث وفلترة حسب الحالة والأولوية والمشروع
- مشاريع ملوّنة، لكل مشروع شريط تقدّم
- وضع داكن، وواجهة عربية بالكامل (RTL)، وتصميم يناسب الجوال
- تسجيل دخول وإنشاء حساب، وكل مستخدم يرى مهامه فقط

## التشغيل
```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed     # ينشئ القاعدة ومستخدمًا تجريبيًا
npm run build                  # أو npm run dev أثناء التطوير
php artisan serve
```
ادخل بحساب: `demo@example.com` / `password`

لتشغيل الاختبارات: `php artisan test`

## أين تجد كل شيء؟
| الجزء | الملف |
|---|---|
| المسارات | `routes/web.php` |
| المنطق | `app/Http/Controllers/` (Dashboard, Task, Project) |
| التحقق من المدخلات | `app/Http/Requests/` |
| الصلاحيات (كل مستخدم يملك بياناته) | `app/Policies/` |
| النماذج والعلاقات والفلترة | `app/Models/Task.php`, `Project.php` |
| الحالات والأولويات (الاسم العربي واللون) | `app/Enums/` |
| الصفحات | `resources/views/` (`dashboard`, `tasks/`, `projects/`) |
| القالب العام والشريط الجانبي | `resources/views/layouts/app.blade.php` |
| السحب والإفلات والوضع الداكن | `resources/js/app.js` |
| أنماط الأزرار والحقول (`.btn-primary`, `.input`, `.card`) | `resources/css/app.css` |
| الترجمة العربية | `lang/ar.json`, `lang/ar/` |

### أمثلة على التعديل
- **إضافة حالة جديدة للمهام:** أضف `case` في `app/Enums/TaskStatus.php` مع اسمها ولونها، وتظهر تلقائيًا في القوائم وفي لوحة Kanban.
- **تغيير اللون الأساسي:** عدّل `brand` في `tailwind.config.js`، ثم شغّل `npm run build`.
# management_tasks
