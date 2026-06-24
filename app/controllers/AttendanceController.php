<?php

declare(strict_types=1);

class AttendanceController extends Controller
{
    /** Daily board (admin) or self check-in screen (employee). */
    public function index(): void
    {
        if (Auth::isAdmin()) {
            $date = $this->validDate($_GET['date'] ?? date('Y-m-d'));
            $this->view('attendance.index', [
                'date'      => $date,
                'records'   => Attendance::forDate($date),
                'employees' => Employee::all(),
            ], 'Attendance');
            return;
        }

        $employeeId = Auth::employeeId();
        $this->view('attendance.self', [
            'employee' => $employeeId ? Employee::find($employeeId) : null,
            'today'    => $employeeId ? Attendance::todayFor($employeeId) : null,
            'month'    => date('Y-m'),
            'records'  => $employeeId ? Attendance::forMonth($employeeId, date('Y-m')) : [],
        ], 'My Attendance');
    }

    public function checkIn(): void
    {
        $this->requirePost();
        $employeeId = Auth::employeeId();
        if (!$employeeId) {
            flash('error', 'No employee profile is linked to your account.');
            redirect('/attendance');
        }
        if (Attendance::todayFor($employeeId)) {
            flash('error', 'You have already checked in today.');
        } else {
            Attendance::checkIn($employeeId);
            flash('success', 'Checked in at ' . date('H:i') . '.');
        }
        redirect('/attendance');
    }

    public function checkOut(): void
    {
        $this->requirePost();
        $employeeId = Auth::employeeId();
        $today = $employeeId ? Attendance::todayFor($employeeId) : null;

        if (!$today) {
            flash('error', 'You need to check in first.');
        } elseif (!empty($today['check_out'])) {
            flash('error', 'You have already checked out today.');
        } else {
            Attendance::checkOut($employeeId);
            flash('success', 'Checked out at ' . date('H:i') . '.');
        }
        redirect('/attendance');
    }

    /** Admin: manually set a status (Present/Absent/Leave/Half-Day). */
    public function mark(): void
    {
        $this->requirePost();
        $employeeId = (int) $this->input('employee_id');
        $date       = $this->validDate($this->input('date', date('Y-m-d')));
        $status     = $this->input('status');

        $allowed = ['Present', 'Absent', 'Leave', 'Half-Day'];
        if (!$employeeId || !in_array($status, $allowed, true)) {
            flash('error', 'Please choose an employee and a valid status.');
        } else {
            Attendance::mark($employeeId, $date, $status);
            flash('success', 'Attendance updated.');
        }
        redirect('/attendance?date=' . $date);
    }

    /** Monthly report for one employee (admin) or self (employee). */
    public function report(): void
    {
        $month = $this->validMonth($_GET['month'] ?? date('Y-m'));

        if (Auth::isAdmin()) {
            $employeeId = (int) ($_GET['employee_id'] ?? 0);
            $employees  = Employee::all();
            if (!$employeeId && $employees) {
                $employeeId = (int) $employees[0]['id'];
            }
        } else {
            $employeeId = (int) Auth::employeeId();
            $employees  = [];
        }

        $employee = $employeeId ? Employee::find($employeeId) : null;
        $records  = $employeeId ? Attendance::forMonth($employeeId, $month) : [];
        $summary  = $employeeId ? Attendance::monthlySummary($employeeId, $month) : null;

        $this->view('attendance.report', [
            'month'      => $month,
            'employee'   => $employee,
            'employeeId' => $employeeId,
            'employees'  => $employees,
            'records'    => $records,
            'summary'    => $summary,
        ], 'Attendance Report');
    }

    // --- helpers -------------------------------------------------------------

    private function validDate(string $date): string
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : date('Y-m-d');
    }

    private function validMonth(string $month): string
    {
        return preg_match('/^\d{4}-\d{2}$/', $month) ? $month : date('Y-m');
    }
}
