# نظام بيانات الموظفين — Employee Data System

تطبيق ويب لإدارة بيانات الموظفين والأقسام، مبني بـ **Laravel 13** و **PHP 8.4** بواجهة عربية كاملة (RTL).

## المزايا

- **لوحة تحكم**: إجمالي الموظفين، عدد من هم على رأس العمل، عدد الأقسام، إجمالي الرواتب الشهرية، توزيع الموظفين على الأقسام، وأحدث الموظفين المضافين.
- **إدارة الموظفين**: إضافة، عرض، تعديل، وحذف (CRUD كامل).
- **بحث وتصفية**: بحث بالاسم أو الرقم الوظيفي أو الرقم القومي أو البريد أو المسمى الوظيفي، مع تصفية حسب القسم والحالة الوظيفية، وترقيم صفحات يحافظ على معايير البحث.
- **إدارة الأقسام**: CRUD للأقسام مع عدّاد الموظفين، ومنع حذف أي قسم يحتوي على موظفين.
- **تحقق كامل من البيانات**: حقول مطلوبة، تفرّد الرقم الوظيفي والرقم القومي والبريد، ومنع تاريخ تعيين مستقبلي — مع رسائل خطأ بالعربية.
- **حالات وظيفية**: على رأس العمل، في إجازة، موقوف، انتهت الخدمة.

## المتطلبات

- PHP 8.3+ مع امتداد `pdo_mysql`
- MySQL 8.0+ أو MariaDB 10.6+
- Composer

## التشغيل

أنشئ قاعدة البيانات أولًا:

```sql
CREATE DATABASE system_it CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

> `utf8mb4` ضرورية لتخزين البيانات العربية بشكل صحيح.

ثم:

```bash
composer install
cp .env.example .env
php artisan key:generate
# عدّل DB_USERNAME و DB_PASSWORD في .env بما يناسب خادمك
php artisan migrate --seed     # ينشئ الجداول مع بيانات تجريبية
php artisan serve
```

ثم افتح <http://127.0.0.1:8000>.

### إعدادات قاعدة البيانات

قاعدة البيانات هي **MySQL** (`DB_CONNECTION=mysql`)، بمحرك InnoDB وترميز `utf8mb4_unicode_ci`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=system_it
DB_USERNAME=root
DB_PASSWORD=
```

## الاختبارات

```bash
php artisan test
```

الاختبارات تعمل على SQLite في الذاكرة (`phpunit.xml`) لتكون سريعة ولا تحتاج خادمًا،
والكود نفسه يعمل على المحركين لأن كل الوصول للبيانات يتم عبر Eloquent والـ migrations.

## هيكل المشروع

| الملف | الوصف |
| --- | --- |
| `app/Models/Employee.php` | نموذج الموظف: العلاقات، التحويلات، نطاق البحث، الاسم الكامل، سنوات الخدمة |
| `app/Models/Department.php` | نموذج القسم وعلاقته بالموظفين |
| `app/Enums/EmployeeStatus.php` | الحالات الوظيفية مع مسمياتها العربية |
| `app/Enums/Gender.php` | النوع (ذكر / أنثى) |
| `app/Http/Controllers/EmployeeController.php` | عمليات الموظفين والبحث والتصفية |
| `app/Http/Controllers/DepartmentController.php` | عمليات الأقسام |
| `app/Http/Controllers/DashboardController.php` | إحصائيات لوحة التحكم |
| `app/Http/Requests/` | قواعد التحقق وأسماء الحقول بالعربية |
| `database/migrations/` | جداول `departments` و `employees` |
| `config/database.php` | الاتصال الافتراضي `mysql` |
| `database/seeders/DatabaseSeeder.php` | 5 أقسام و 10 موظفين كبيانات تجريبية |
| `resources/views/` | واجهات Blade عربية (RTL) |
| `public/css/app.css` | التنسيقات — بدون أي اعتماد على مكتبات خارجية |
| `tests/` | 23 اختبارًا تغطي الـ CRUD والتحقق والبحث والإحصائيات |

## جدول `employees`

| الحقل | النوع | ملاحظات |
| --- | --- | --- |
| `employee_code` | string | فريد |
| `first_name` / `last_name` | string | |
| `national_id` | string | فريد، من 8 إلى 20 رقمًا |
| `email` | string | فريد |
| `phone` | string | اختياري |
| `gender` | enum | `male` / `female` |
| `birth_date` | date | اختياري |
| `hire_date` | date | لا يقبل تاريخًا مستقبليًا |
| `department_id` | FK | مقيّد بالحذف (`restrictOnDelete`) |
| `job_title` | string | |
| `salary` | decimal(12,2) | |
| `status` | enum | `active` / `on_leave` / `suspended` / `terminated` |
| `address` / `notes` | string / text | اختياري |
