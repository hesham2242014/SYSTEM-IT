<?php

namespace Tests\Feature;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_employee_list_is_displayed(): void
    {
        $employee = Employee::factory()->create(['first_name' => 'هشام']);

        $this->get(route('employees.index'))
            ->assertOk()
            ->assertSee('هشام', false)
            ->assertSee($employee->employee_code);
    }

    public function test_the_list_can_be_filtered_by_search_term(): void
    {
        $wanted = Employee::factory()->create(['employee_code' => 'EMP-0001', 'last_name' => 'المصري']);
        $other = Employee::factory()->create(['employee_code' => 'EMP-9999', 'last_name' => 'السيد']);

        $this->get(route('employees.index', ['search' => 'المصري']))
            ->assertOk()
            ->assertSee($wanted->employee_code)
            ->assertDontSee($other->employee_code);
    }

    public function test_the_list_can_be_filtered_by_department_and_status(): void
    {
        $department = Department::factory()->create();
        $wanted = Employee::factory()->create([
            'department_id' => $department->id,
            'status' => EmployeeStatus::Active,
            'employee_code' => 'EMP-0001',
        ]);
        $other = Employee::factory()->create([
            'department_id' => $department->id,
            'status' => EmployeeStatus::Terminated,
            'employee_code' => 'EMP-0002',
        ]);

        $this->get(route('employees.index', [
            'department_id' => $department->id,
            'status' => EmployeeStatus::Active->value,
        ]))
            ->assertOk()
            ->assertSee($wanted->employee_code)
            ->assertDontSee($other->employee_code);
    }

    public function test_an_employee_can_be_created(): void
    {
        $department = Department::factory()->create();

        $response = $this->post(route('employees.store'), $this->validPayload($department));

        $employee = Employee::firstWhere('employee_code', 'EMP-1001');

        $this->assertNotNull($employee);
        $response->assertRedirect(route('employees.show', $employee));
        $this->assertSame('سارة', $employee->first_name);
        $this->assertSame($department->id, $employee->department_id);
        $this->assertSame(EmployeeStatus::Active, $employee->status);
    }

    public function test_creating_an_employee_requires_valid_data(): void
    {
        $this->from(route('employees.create'))
            ->post(route('employees.store'), [])
            ->assertRedirect(route('employees.create'))
            ->assertSessionHasErrors([
                'employee_code', 'first_name', 'last_name', 'national_id',
                'email', 'gender', 'hire_date', 'department_id', 'job_title',
                'salary', 'status',
            ]);

        $this->assertSame(0, Employee::count());
    }

    public function test_the_employee_code_must_be_unique(): void
    {
        $department = Department::factory()->create();
        Employee::factory()->create(['employee_code' => 'EMP-1001']);

        $this->post(route('employees.store'), $this->validPayload($department))
            ->assertSessionHasErrors('employee_code');
    }

    public function test_the_hire_date_cannot_be_in_the_future(): void
    {
        $department = Department::factory()->create();

        $this->post(route('employees.store'), [
            ...$this->validPayload($department),
            'hire_date' => now()->addDay()->toDateString(),
        ])->assertSessionHasErrors('hire_date');
    }

    public function test_an_employee_can_be_updated(): void
    {
        $employee = Employee::factory()->create();

        $this->put(route('employees.update', $employee), [
            ...$this->validPayload($employee->department),
            'employee_code' => $employee->employee_code,
            'national_id' => $employee->national_id,
            'email' => $employee->email,
            'job_title' => 'مدير تقنية المعلومات',
            'salary' => 32000,
        ])->assertRedirect(route('employees.show', $employee));

        $this->assertSame('مدير تقنية المعلومات', $employee->refresh()->job_title);
        $this->assertSame('32000.00', $employee->salary);
    }

    public function test_an_employee_keeps_its_own_unique_values_when_updated(): void
    {
        $employee = Employee::factory()->create();

        $this->put(route('employees.update', $employee), [
            ...$this->validPayload($employee->department),
            'employee_code' => $employee->employee_code,
            'national_id' => $employee->national_id,
            'email' => $employee->email,
        ])->assertSessionHasNoErrors();
    }

    public function test_an_employee_can_be_deleted(): void
    {
        $employee = Employee::factory()->create();

        $this->delete(route('employees.destroy', $employee))
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_an_employee_profile_is_displayed(): void
    {
        $employee = Employee::factory()->create(['job_title' => 'مهندس شبكات']);

        $this->get(route('employees.show', $employee))
            ->assertOk()
            ->assertSee('مهندس شبكات', false)
            ->assertSee($employee->email);
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(Department $department): array
    {
        return [
            'employee_code' => 'EMP-1001',
            'first_name' => 'سارة',
            'last_name' => 'محمود',
            'national_id' => '29001011234567',
            'email' => 'sara@system-it.local',
            'phone' => '01000000000',
            'gender' => Gender::Female->value,
            'birth_date' => '1995-03-14',
            'hire_date' => '2022-01-10',
            'department_id' => $department->id,
            'job_title' => 'مطور برمجيات',
            'salary' => 18000,
            'status' => EmployeeStatus::Active->value,
            'address' => 'القاهرة',
            'notes' => null,
        ];
    }
}
