<?php

declare(strict_types=1);

class EmployeeController extends Controller
{
    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $this->view('employees.index', [
            'employees' => Employee::all($search),
            'search'    => $search,
        ], 'Employees');
    }

    public function create(): void
    {
        $this->view('employees.create', [
            'nextCode' => Employee::nextCode(),
        ], 'Add Employee');
    }

    public function store(): void
    {
        $data = $this->collect();
        $errors = $this->validate($data);

        if ($errors) {
            flash('error', implode(' ', $errors));
            flash_old($_POST);
            redirect('/employees/create');
        }

        $id = Employee::create($data);

        // Optionally create a login account for the employee.
        $loginEmail = $this->input('login_email');
        $loginPass  = $this->input('login_password');
        if ($loginEmail !== '' && $loginPass !== '') {
            if (User::emailExists($loginEmail)) {
                flash('error', 'Employee saved, but the login email is already in use — no account created.');
            } else {
                User::create($data['name'], $loginEmail, $loginPass, 'employee', $id);
            }
        }

        clear_old();
        flash('success', 'Employee ' . $data['employee_code'] . ' added successfully.');
        redirect('/employees');
    }

    public function show(string $id): void
    {
        $employee = Employee::find((int) $id);
        if (!$employee) {
            redirect('/employees');
        }
        $month = date('Y-m');
        $this->view('employees.show', [
            'employee'   => $employee,
            'attendance' => Attendance::forMonth((int) $id, $month),
            'leaves'     => LeaveRequest::forEmployee((int) $id),
            'salaries'   => Salary::forEmployee((int) $id),
            'month'      => $month,
        ], 'Employee: ' . $employee['name']);
    }

    public function edit(string $id): void
    {
        $employee = Employee::find((int) $id);
        if (!$employee) {
            redirect('/employees');
        }
        $this->view('employees.edit', ['employee' => $employee], 'Edit Employee');
    }

    public function update(string $id): void
    {
        $employee = Employee::find((int) $id);
        if (!$employee) {
            redirect('/employees');
        }

        $data = $this->collect();
        $data['employee_code'] = $employee['employee_code']; // code is immutable
        $errors = $this->validate($data, (int) $id);

        if ($errors) {
            flash('error', implode(' ', $errors));
            flash_old($_POST);
            redirect('/employees/' . $id . '/edit');
        }

        Employee::update((int) $id, $data);
        clear_old();
        flash('success', 'Employee details updated.');
        redirect('/employees/' . $id);
    }

    public function destroy(string $id): void
    {
        $employee = Employee::find((int) $id);
        if ($employee) {
            Employee::delete((int) $id);
            flash('success', 'Employee ' . $employee['employee_code'] . ' deleted.');
        }
        redirect('/employees');
    }

    // --- helpers -------------------------------------------------------------

    private function collect(): array
    {
        return [
            'employee_code' => $this->input('employee_code') ?: Employee::nextCode(),
            'name'          => $this->input('name'),
            'email'         => $this->input('email'),
            'phone'         => $this->input('phone'),
            'department'    => $this->input('department'),
            'designation'   => $this->input('designation'),
            'joining_date'  => $this->input('joining_date') ?: null,
            'basic_salary'  => (float) $this->input('basic_salary', '0'),
            'status'        => $this->input('status', 'active') === 'inactive' ? 'inactive' : 'active',
        ];
    }

    private function validate(array $data, ?int $ignoreId = null): array
    {
        $errors = [];
        if ($data['name'] === '') {
            $errors[] = 'Name is required.';
        }
        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        } else {
            $existing = Database::first('SELECT id FROM employees WHERE email = ?', [$data['email']]);
            if ($existing && (int) $existing['id'] !== $ignoreId) {
                $errors[] = 'That email is already assigned to another employee.';
            }
        }
        if ($data['basic_salary'] < 0) {
            $errors[] = 'Basic salary cannot be negative.';
        }
        return $errors;
    }
}
