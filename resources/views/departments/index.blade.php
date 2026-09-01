@extends('layouts.app')

@section('title', 'الأقسام')

@section('content')
    <div class="page-head">
        <div>
            <h1>الأقسام</h1>
            <p>إدارة الأقسام التي ينتمي إليها الموظفون.</p>
        </div>
        <a class="btn" href="{{ route('departments.create') }}">إضافة قسم</a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>الكود</th><th>الاسم</th><th>الموقع</th><th>عدد الموظفين</th><th>إجراءات</th></tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td>{{ $department->code }}</td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->location ?? '—' }}</td>
                            <td class="numeric">{{ $department->employees_count }}</td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-light btn-sm" href="{{ route('departments.edit', $department) }}">تعديل</a>
                                    <form method="POST" action="{{ route('departments.destroy', $department) }}"
                                          onsubmit="return confirm('هل تريد حذف القسم {{ $department->name }}؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">لا توجد أقسام مسجلة بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $departments->links('partials.pagination') }}
    </div>
@endsection
