<?php

namespace App\Http\Controllers;

use App\Enums\EmployeeStatus;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'employeesCount' => Employee::count(),
            'departmentsCount' => Department::count(),
            'activeCount' => Employee::where('status', EmployeeStatus::Active)->count(),
            'payroll' => (float) Employee::where('status', EmployeeStatus::Active)->sum('salary'),
            'byDepartment' => Department::withCount('employees')->orderByDesc('employees_count')->get(),
            'latest' => Employee::with('department')->latest()->take(5)->get(),
        ]);
    }
}
