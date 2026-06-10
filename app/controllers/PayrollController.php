<?php

declare(strict_types=1);

class PayrollController extends Controller
{
    /** Admin payroll list for a month. */
    public function index(): void
    {
        $month = $this->validMonth($_GET['month'] ?? date('Y-m'));
        $this->view('payroll.index', [
            'month'     => $month,
            'salaries'  => Salary::forMonth($month),
            'total'     => Salary::totalForMonth($month),
            'employees' => Employee::all(),
        ], 'Payroll');
    }

    /** Generate payslips for all active employees for a month. */
    public function generate(): void
    {
        $this->requirePost();
        $month = $this->validMonth($this->input('month', date('Y-m')));

        $employees = Employee::all();
        $count = 0;
        foreach ($employees as $employee) {
            if (($employee['status'] ?? 'active') !== 'active') {
                continue;
            }
            $slip = Salary::compute($employee, $month);
            Salary::save($slip);
            $count++;
        }

        flash('success', "Payroll generated for {$count} employee(s) for {$month}.");
        redirect('/payroll?month=' . $month);
    }

    /** Printable payslip (employee can view their own; admin can view any). */
    public function payslip(string $id): void
    {
        $salary = Salary::find((int) $id);
        if (!$salary) {
            redirect('/payroll');
        }

        if (!Auth::isAdmin() && (int) $salary['employee_id'] !== (int) Auth::employeeId()) {
            http_response_code(403);
            exit('403 — You can only view your own payslips.');
        }

        $employee = Employee::find((int) $salary['employee_id']);

        // Standalone print layout (no app chrome) — "Save as PDF" from the browser.
        $title = 'Payslip';
        extract(['salary' => $salary, 'employee' => $employee]);
        require ROOT_PATH . '/app/views/payroll/payslip.php';
    }

    private function validMonth(string $month): string
    {
        return preg_match('/^\d{4}-\d{2}$/', $month) ? $month : date('Y-m');
    }
}
