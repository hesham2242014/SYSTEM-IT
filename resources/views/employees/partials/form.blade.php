@csrf

<div class="form-grid">
    <div class="field @error('employee_code') invalid @enderror">
        <label for="employee_code">الرقم الوظيفي</label>
        <input type="text" id="employee_code" name="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" required>
        @error('employee_code') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('first_name') invalid @enderror">
        <label for="first_name">الاسم الأول</label>
        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
        @error('first_name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('last_name') invalid @enderror">
        <label for="last_name">اسم العائلة</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
        @error('last_name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('national_id') invalid @enderror">
        <label for="national_id">الرقم القومي</label>
        <input type="text" id="national_id" name="national_id" value="{{ old('national_id', $employee->national_id) }}" required>
        @error('national_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('email') invalid @enderror">
        <label for="email">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
        @error('email') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('phone') invalid @enderror">
        <label for="phone">رقم الهاتف</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
        @error('phone') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('gender') invalid @enderror">
        <label for="gender">النوع</label>
        <select id="gender" name="gender" required>
            @foreach ($genders as $value => $label)
                <option value="{{ $value }}" @selected(old('gender', $employee->gender?->value) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('gender') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('birth_date') invalid @enderror">
        <label for="birth_date">تاريخ الميلاد</label>
        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}">
        @error('birth_date') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('hire_date') invalid @enderror">
        <label for="hire_date">تاريخ التعيين</label>
        <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" required>
        @error('hire_date') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('department_id') invalid @enderror">
        <label for="department_id">القسم</label>
        <select id="department_id" name="department_id" required>
            <option value="">— اختر القسم —</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id', $employee->department_id) == $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('department_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('job_title') invalid @enderror">
        <label for="job_title">المسمى الوظيفي</label>
        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $employee->job_title) }}" required>
        @error('job_title') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('salary') invalid @enderror">
        <label for="salary">الراتب الشهري</label>
        <input type="number" step="0.01" min="0" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}" required>
        @error('salary') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('status') invalid @enderror">
        <label for="status">الحالة الوظيفية</label>
        <select id="status" name="status" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $employee->status?->value) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field field-full @error('address') invalid @enderror">
        <label for="address">العنوان</label>
        <input type="text" id="address" name="address" value="{{ old('address', $employee->address) }}">
        @error('address') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field field-full @error('notes') invalid @enderror">
        <label for="notes">ملاحظات</label>
        <textarea id="notes" name="notes" rows="3">{{ old('notes', $employee->notes) }}</textarea>
        @error('notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-footer">
    <button type="submit" class="btn">{{ $submitLabel }}</button>
    <a class="btn btn-light" href="{{ route('employees.index') }}">إلغاء</a>
</div>
