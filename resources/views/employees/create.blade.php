@extends('layouts.app')

@section('title', 'إضافة موظف')

@section('content')
    <div class="page-head">
        <div>
            <h1>إضافة موظف جديد</h1>
            <p>أدخل البيانات الأساسية والوظيفية للموظف.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('employees.store') }}">
                @include('employees.partials.form', ['submitLabel' => 'حفظ الموظف'])
            </form>
        </div>
    </div>
@endsection
