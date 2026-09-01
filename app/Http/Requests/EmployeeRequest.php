<?php

namespace App\Http\Requests;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EmployeeRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $employee = $this->route('employee');
        $ignore = $employee instanceof Employee ? $employee->id : null;

        return [
            'employee_code' => ['required', 'string', 'max:20', Rule::unique('employees')->ignore($ignore)],
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],
            'national_id' => ['required', 'string', 'digits_between:8,20', Rule::unique('employees')->ignore($ignore)],
            'email' => ['required', 'email', 'max:150', Rule::unique('employees')->ignore($ignore)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', new Enum(Gender::class)],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'hire_date' => ['required', 'date', 'before_or_equal:today'],
            'department_id' => ['required', 'exists:departments,id'],
            'job_title' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'status' => ['required', new Enum(EmployeeStatus::class)],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'employee_code' => 'الرقم الوظيفي',
            'first_name' => 'الاسم الأول',
            'last_name' => 'اسم العائلة',
            'national_id' => 'الرقم القومي',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'gender' => 'النوع',
            'birth_date' => 'تاريخ الميلاد',
            'hire_date' => 'تاريخ التعيين',
            'department_id' => 'القسم',
            'job_title' => 'المسمى الوظيفي',
            'salary' => 'الراتب',
            'status' => 'الحالة الوظيفية',
            'address' => 'العنوان',
            'notes' => 'ملاحظات',
        ];
    }
}
