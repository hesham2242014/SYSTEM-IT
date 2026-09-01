<?php

namespace App\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $department = $this->route('department');
        $ignore = $department instanceof Department ? $department->id : null;

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('departments')->ignore($ignore)],
            'name' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code' => 'كود القسم',
            'name' => 'اسم القسم',
            'location' => 'الموقع',
            'description' => 'الوصف',
        ];
    }
}
