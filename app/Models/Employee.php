<?php

namespace App\Models;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'employee_code',
    'first_name',
    'last_name',
    'national_id',
    'email',
    'phone',
    'gender',
    'birth_date',
    'hire_date',
    'department_id',
    'job_title',
    'salary',
    'status',
    'address',
    'notes',
])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'hire_date' => 'date',
            'salary' => 'decimal:2',
            'gender' => Gender::class,
            'status' => EmployeeStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => trim("{$this->first_name} {$this->last_name}"));
    }

    /**
     * Number of full years since the hire date.
     *
     * @return Attribute<int, never>
     */
    protected function yearsOfService(): Attribute
    {
        return Attribute::get(fn (): int => (int) $this->hire_date->diffInYears(now()));
    }

    /**
     * Match the name, code, e-mail or national id against a search term.
     *
     * @param  Builder<Employee>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $term = trim((string) $term);

        if ($term === '') {
            return;
        }

        $query->where(function (Builder $query) use ($term): void {
            $query->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('employee_code', 'like', "%{$term}%")
                ->orWhere('national_id', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('job_title', 'like', "%{$term}%");
        });
    }
}
