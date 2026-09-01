<?php

namespace App\Http\Controllers;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Http\Requests\EmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $employees = Employee::query()
            ->with('department')
            ->search($request->string('search'))
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->integer('department_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('employee_code')
            ->paginate(15)
            ->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'departments' => Department::orderBy('name')->get(),
            'statuses' => EmployeeStatus::options(),
            'filters' => $request->only('search', 'department_id', 'status'),
        ]);
    }

    public function create(): View
    {
        return view('employees.create', [
            'employee' => new Employee(['status' => EmployeeStatus::Active]),
            'departments' => Department::orderBy('name')->get(),
            'genders' => Gender::options(),
            'statuses' => EmployeeStatus::options(),
        ]);
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $employee = Employee::create($request->validated());

        return redirect()
            ->route('employees.show', $employee)
            ->with('status', 'تم إضافة الموظف بنجاح.');
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', [
            'employee' => $employee->load('department'),
        ]);
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', [
            'employee' => $employee,
            'departments' => Department::orderBy('name')->get(),
            'genders' => Gender::options(),
            'statuses' => EmployeeStatus::options(),
        ]);
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()
            ->route('employees.show', $employee)
            ->with('status', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('status', 'تم حذف الموظف بنجاح.');
    }
}
