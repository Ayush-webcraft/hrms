<?php

declare(strict_types=1);

class LeaveController extends Controller
{
    public function index(): void
    {
        if (Auth::isAdmin()) {
            $this->view('leaves.index', [
                'leaves' => LeaveRequest::all(),
            ], 'Leave Requests');
            return;
        }

        $employeeId = (int) Auth::employeeId();
        $quota = (int) config('payroll.annual_leave_quota');
        $used  = $employeeId ? LeaveRequest::approvedDaysThisYear($employeeId) : 0;

        $this->view('leaves.mine', [
            'leaves'       => $employeeId ? LeaveRequest::forEmployee($employeeId) : [],
            'leaveQuota'   => $quota,
            'leaveUsed'    => $used,
            'leaveBalance' => max(0, $quota - $used),
        ], 'My Leaves');
    }

    public function create(): void
    {
        $this->view('leaves.create', [], 'Apply for Leave');
    }

    public function store(): void
    {
        $employeeId = Auth::employeeId();
        if (!$employeeId) {
            flash('error', 'No employee profile is linked to your account.');
            redirect('/leaves');
        }

        $type   = $this->input('leave_type', 'Casual');
        $start  = $this->input('start_date');
        $end    = $this->input('end_date');
        $reason = $this->input('reason');

        $days = LeaveRequest::daysBetween($start, $end);
        if ($days < 1) {
            flash('error', 'Please provide a valid date range (end date on or after start date).');
            flash_old($_POST);
            redirect('/leaves/create');
        }

        LeaveRequest::create([
            'employee_id' => $employeeId,
            'leave_type'  => $type,
            'start_date'  => $start,
            'end_date'    => $end,
            'days'        => $days,
            'reason'      => $reason,
        ]);

        clear_old();
        flash('success', 'Leave request submitted for approval.');
        redirect('/leaves');
    }

    public function approve(string $id): void
    {
        $this->decide((int) $id, 'Approved');
    }

    public function reject(string $id): void
    {
        $this->decide((int) $id, 'Rejected');
    }

    private function decide(int $id, string $status): void
    {
        $leave = LeaveRequest::find($id);
        if (!$leave) {
            redirect('/leaves');
        }

        LeaveRequest::setStatus($id, $status, (int) Auth::id());

        // On approval, stamp the leave days onto the attendance sheet so payroll
        // treats them as paid leave rather than absences.
        if ($status === 'Approved') {
            $cursor = strtotime($leave['start_date']);
            $end    = strtotime($leave['end_date']);
            while ($cursor !== false && $cursor <= $end) {
                Attendance::mark((int) $leave['employee_id'], date('Y-m-d', $cursor), 'Leave');
                $cursor = strtotime('+1 day', $cursor);
            }
        }

        flash('success', 'Leave request ' . strtolower($status) . '.');
        redirect('/leaves');
    }
}
