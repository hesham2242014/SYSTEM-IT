@extends('layouts.app')

@section('title', 'تعديل بيانات موظف')

@section('content')
    <div class="page-head">
        <div>
            <h1>تعديل بيانات: {{ $employee->full_name }}</h1>
            <p>الرقم الوظيفي {{ $employee->employee_code }}</p>
        </div>
        <a class="btn btn-light" href="{{ route('employees.show', $employee) }}">عرض الملف</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('employees.update', $employee) }}">
                @method('PUT')
                @include('employees.partials.form', ['submitLabel' => 'حفظ التعديلات'])
            </form>
        </div>
    </div>
@endsection
