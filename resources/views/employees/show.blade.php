@extends('layouts.app')

@section('title', $employee->full_name)

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $employee->full_name }}</h1>
            <p>{{ $employee->job_title }} — {{ $employee->department->name }}</p>
        </div>
        <div class="actions">
            <a class="btn" href="{{ route('employees.edit', $employee) }}">تعديل</a>
            <a class="btn btn-light" href="{{ route('employees.index') }}">رجوع للقائمة</a>
        </div>
    </div>

    <div class="card">
        <h2>البيانات الوظيفية</h2>
        <div class="card-body details">
            <div class="item"><div class="label">الرقم الوظيفي</div><div class="value">{{ $employee->employee_code }}</div></div>
            <div class="item"><div class="label">القسم</div><div class="value">{{ $employee->department->name }} ({{ $employee->department->code }})</div></div>
            <div class="item"><div class="label">المسمى الوظيفي</div><div class="value">{{ $employee->job_title }}</div></div>
            <div class="item"><div class="label">تاريخ التعيين</div><div class="value numeric">{{ $employee->hire_date->format('Y-m-d') }}</div></div>
            <div class="item"><div class="label">سنوات الخدمة</div><div class="value numeric">{{ $employee->years_of_service }}</div></div>
            <div class="item"><div class="label">الراتب الشهري</div><div class="value numeric">{{ number_format((float) $employee->salary, 2) }}</div></div>
            <div class="item"><div class="label">الحالة الوظيفية</div><div class="value">@include('employees.partials.status', ['status' => $employee->status])</div></div>
        </div>
    </div>

    <div class="card">
        <h2>البيانات الشخصية</h2>
        <div class="card-body details">
            <div class="item"><div class="label">الرقم القومي</div><div class="value numeric">{{ $employee->national_id }}</div></div>
            <div class="item"><div class="label">النوع</div><div class="value">{{ $employee->gender->label() }}</div></div>
            <div class="item"><div class="label">تاريخ الميلاد</div><div class="value numeric">{{ $employee->birth_date?->format('Y-m-d') ?? '—' }}</div></div>
            <div class="item"><div class="label">البريد الإلكتروني</div><div class="value">{{ $employee->email }}</div></div>
            <div class="item"><div class="label">رقم الهاتف</div><div class="value numeric">{{ $employee->phone ?? '—' }}</div></div>
            <div class="item"><div class="label">العنوان</div><div class="value">{{ $employee->address ?? '—' }}</div></div>
        </div>
    </div>

    @if ($employee->notes)
        <div class="card">
            <h2>ملاحظات</h2>
            <div class="card-body">{{ $employee->notes }}</div>
        </div>
    @endif
@endsection
