@csrf

<div class="form-grid">
    <div class="field @error('code') invalid @enderror">
        <label for="code">كود القسم</label>
        <input type="text" id="code" name="code" value="{{ old('code', $department->code) }}" required>
        @error('code') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('name') invalid @enderror">
        <label for="name">اسم القسم</label>
        <input type="text" id="name" name="name" value="{{ old('name', $department->name) }}" required>
        @error('name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field @error('location') invalid @enderror">
        <label for="location">الموقع</label>
        <input type="text" id="location" name="location" value="{{ old('location', $department->location) }}">
        @error('location') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field field-full @error('description') invalid @enderror">
        <label for="description">الوصف</label>
        <textarea id="description" name="description" rows="3">{{ old('description', $department->description) }}</textarea>
        @error('description') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-footer">
    <button type="submit" class="btn">{{ $submitLabel }}</button>
    <a class="btn btn-light" href="{{ route('departments.index') }}">إلغاء</a>
</div>
