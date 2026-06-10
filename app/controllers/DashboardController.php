<?php

declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        $today = date('Y-m-d');
        $month = date('Y-m');

        if (Auth::isAdmin()) {
            $totalEmployees = Employee::count();
            $presentToday   = Attendance::countByStatus($today, 'Present')
                            + Attendance::countByStatus($today, 'Half-Day');
            $onLeaveToday   = Attendance::countByStatus($today, 'Leave');
            $absentToday    = max(0, $totalEmployees - $presentToday - $onLeaveToday);

            $this->view('dashboard.admin', [
                'totalEmployees' => $totalEmployees,
                'presentToday'   => $presentToday,
                'absentToday'    => $absentToday,
                'onLeaveToday'   => $onLeaveToday,
                'pendingLeaves'  => LeaveRequest::pendingCount(),
                'monthPayroll'   => Salary::totalForMonth($month),
                'month'          => $month,
                'recentLeaves'   => array_slice(LeaveRequest::all(), 0, 5),
            ], 'Dashboard');
            return;
        }

        // Employee dashboard — only their own data.
        $employeeId = Auth::employeeId();
        $employee   = $employeeId ? Employee::find($employeeId) : null;
        $today_att  = $employeeId ? Attendance::todayFor($employeeId) : null;

        $quota = (int) config('payroll.annual_leave_quota');
        $used  = $employeeId ? LeaveRequest::approvedDaysThisYear($employeeId) : 0;

        $this->view('dashboard.employee', [
            'employee'      => $employee,
            'todayAtt'      => $today_att,
            'leaveQuota'    => $quota,
            'leaveUsed'     => $used,
            'leaveBalance'  => max(0, $quota - $used),
            'recentAtt'     => $employeeId ? Attendance::forMonth($employeeId, $month) : [],
            'latestSlips'   => $employeeId ? array_slice(Salary::forEmployee($employeeId), 0, 3) : [],
            'month'         => $month,
        ], 'Dashboard');
    }
}
