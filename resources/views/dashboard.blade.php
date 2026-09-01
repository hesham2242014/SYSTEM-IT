@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="page-head">
        <div>
            <h1>لوحة التحكم</h1>
            <p>نظرة عامة على بيانات الموظفين والأقسام.</p>
        </div>
        <a class="btn" href="{{ route('employees.create') }}">إضافة موظف</a>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="label">إجمالي الموظفين</div>
            <div class="value numeric">{{ number_format($employeesCount) }}</div>
        </div>
        <div class="stat">
            <div class="label">على رأس العمل</div>
            <div class="value numeric">{{ number_format($activeCount) }}</div>
        </div>
        <div class="stat">
            <div class="label">عدد الأقسام</div>
            <div class="value numeric">{{ number_format($departmentsCount) }}</div>
        </div>
        <div class="stat">
            <div class="label">إجمالي الرواتب الشهرية</div>
            <div class="value numeric">{{ number_format($payroll, 2) }}</div>
        </div>
    </div>

    <div class="card">
        <h2>توزيع الموظفين على الأقسام</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>الكود</th><th>القسم</th><th>الموقع</th><th>عدد الموظفين</th></tr>
                </thead>
                <tbody>
                    @forelse ($byDepartment as $department)
                        <tr>
                            <td>{{ $department->code }}</td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->location ?? '—' }}</td>
                            <td class="numeric">{{ $department->employees_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty">لا توجد أقسام مسجلة بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h2>أحدث الموظفين المضافين</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>الرقم الوظيفي</th><th>الاسم</th><th>القسم</th><th>المسمى الوظيفي</th><th>الحالة</th></tr>
                </thead>
                <tbody>
                    @forelse ($latest as $employee)
                        <tr>
                            <td>{{ $employee->employee_code }}</td>
                            <td><a href="{{ route('employees.show', $employee) }}">{{ $employee->full_name }}</a></td>
                            <td>{{ $employee->department->name }}</td>
                            <td>{{ $employee->job_title }}</td>
                            <td>@include('employees.partials.status', ['status' => $employee->status])</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">لا يوجد موظفون مسجلون بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
