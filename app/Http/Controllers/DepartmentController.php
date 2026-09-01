<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        return view('departments.index', [
            'departments' => Department::withCount('employees')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('departments.create', [
            'department' => new Department,
        ]);
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()
            ->route('departments.index')
            ->with('status', 'تم إضافة القسم بنجاح.');
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', [
            'department' => $department,
        ]);
    }

    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()
            ->route('departments.index')
            ->with('status', 'تم تحديث القسم بنجاح.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->employees()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'لا يمكن حذف قسم يحتوي على موظفين.');
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('status', 'تم حذف القسم بنجاح.');
    }
}
