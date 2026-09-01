<?php

namespace Database\Seeders;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $departments = collect([
            ['code' => 'IT', 'name' => 'تقنية المعلومات', 'location' => 'الدور الثالث'],
            ['code' => 'HR', 'name' => 'الموارد البشرية', 'location' => 'الدور الأول'],
            ['code' => 'FIN', 'name' => 'الشؤون المالية', 'location' => 'الدور الثاني'],
            ['code' => 'OPS', 'name' => 'العمليات', 'location' => 'المبنى ب'],
            ['code' => 'SLS', 'name' => 'المبيعات', 'location' => 'الدور الأرضي'],
        ])->map(fn (array $attributes) => Department::firstOrCreate(
            ['code' => $attributes['code']],
            $attributes,
        ));

        $employees = [
            ['هشام', 'عبد الرحمن', 'IT', 'مدير تقنية المعلومات', 32000, Gender::Male, EmployeeStatus::Active],
            ['سارة', 'محمود', 'IT', 'مطور برمجيات', 18000, Gender::Female, EmployeeStatus::Active],
            ['أحمد', 'السيد', 'IT', 'مهندس شبكات', 15500, Gender::Male, EmployeeStatus::OnLeave],
            ['منى', 'خالد', 'HR', 'أخصائي موارد بشرية', 12000, Gender::Female, EmployeeStatus::Active],
            ['كريم', 'فؤاد', 'HR', 'مسؤول توظيف', 11000, Gender::Male, EmployeeStatus::Active],
            ['ليلى', 'حسن', 'FIN', 'محاسب أول', 17000, Gender::Female, EmployeeStatus::Active],
            ['طارق', 'مصطفى', 'FIN', 'مراجع داخلي', 16000, Gender::Male, EmployeeStatus::Suspended],
            ['نورا', 'إبراهيم', 'OPS', 'مشرف عمليات', 14000, Gender::Female, EmployeeStatus::Active],
            ['ياسر', 'عادل', 'OPS', 'فني صيانة', 9000, Gender::Male, EmployeeStatus::Terminated],
            ['دينا', 'سمير', 'SLS', 'مدير مبيعات', 22000, Gender::Female, EmployeeStatus::Active],
        ];

        foreach ($employees as $index => [$first, $last, $code, $title, $salary, $gender, $status]) {
            Employee::firstOrCreate(
                ['employee_code' => sprintf('EMP-%04d', $index + 1)],
                [
                    'first_name' => $first,
                    'last_name' => $last,
                    'national_id' => sprintf('2900101%07d', $index + 1),
                    'email' => sprintf('employee%02d@system-it.local', $index + 1),
                    'phone' => sprintf('0100000%04d', $index + 1),
                    'gender' => $gender,
                    'birth_date' => now()->subYears(28 + $index)->toDateString(),
                    'hire_date' => now()->subYears(1)->subMonths($index)->toDateString(),
                    'department_id' => $departments->firstWhere('code', $code)->id,
                    'job_title' => $title,
                    'salary' => $salary,
                    'status' => $status,
                    'address' => 'القاهرة، مصر',
                ],
            );
        }
    }
}
