@extends('layouts.app')

@section('title', 'تعديل قسم')

@section('content')
    <div class="page-head"><div><h1>تعديل القسم: {{ $department->name }}</h1></div></div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('departments.update', $department) }}">
                @method('PUT')
                @include('departments.partials.form', ['submitLabel' => 'حفظ التعديلات'])
            </form>
        </div>
    </div>
@endsection
