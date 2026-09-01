@extends('layouts.app')

@section('title', 'إضافة قسم')

@section('content')
    <div class="page-head"><div><h1>إضافة قسم جديد</h1></div></div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('departments.store') }}">
                @include('departments.partials.form', ['submitLabel' => 'حفظ القسم'])
            </form>
        </div>
    </div>
@endsection
