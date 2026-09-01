@extends('layouts.app')

@section('title', 'الموظفون')

@section('content')
    <div class="page-head">
        <div>
            <h1>الموظفون</h1>
            <p>إجمالي {{ number_format($employees->total()) }} موظف.</p>
        </div>
        <a class="btn" href="{{ route('employees.create') }}">إضافة موظف</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('employees.index') }}" class="filters">
                <div class="field">
                    <label for="search">بحث</label>
                    <input type="search" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                           placeholder="الاسم، الرقم الوظيفي، الرقم القومي، البريد...">
                </div>
                <div class="field">
                    <label for="department_id">القسم</label>
                    <select id="department_id" name="department_id">
                        <option value="">كل الأقسام</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(($filters['department_id'] ?? null) == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="status">الحالة</label>
                    <select id="status" name="status">
                        <option value="">كل الحالات</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? null) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn">تصفية</button>
                <a class="btn btn-light" href="{{ route('employees.index') }}">إعادة تعيين</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>الرقم الوظيفي</th>
                        <th>الاسم</th>
                        <th>القسم</th>
                        <th>المسمى الوظيفي</th>
                        <th>تاريخ التعيين</th>
                        <th>الراتب</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_code }}</td>
                            <td><a href="{{ route('employees.show', $employee) }}">{{ $employee->full_name }}</a></td>
                            <td>{{ $employee->department->name }}</td>
                            <td>{{ $employee->job_title }}</td>
                            <td class="numeric">{{ $employee->hire_date->format('Y-m-d') }}</td>
                            <td class="numeric">{{ number_format((float) $employee->salary, 2) }}</td>
                            <td>@include('employees.partials.status', ['status' => $employee->status])</td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-light btn-sm" href="{{ route('employees.edit', $employee) }}">تعديل</a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}"
                                          onsubmit="return confirm('هل تريد حذف الموظف {{ $employee->full_name }}؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty">لا توجد نتائج مطابقة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $employees->links('partials.pagination') }}
    </div>
@endsection
