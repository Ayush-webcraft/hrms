<?php
/** @var array $employee @var array $attendance @var array $leaves
 *  @var array $salaries @var string $month */
?>
<div class="page-head">
    <h1><?= e($employee['name']) ?> <span class="muted">· <?= e($employee['employee_code']) ?></span></h1>
    <a class="btn" href="<?= e(url('/employees/' . $employee['id'] . '/edit')) ?>">Edit</a>
</div>

<div class="card">
    <div class="detail-grid">
        <div><span class="muted">Email</span><div><?= e($employee['email']) ?></div></div>
        <div><span class="muted">Phone</span><div><?= e($employee['phone'] ?: '—') ?></div></div>
        <div><span class="muted">Department</span><div><?= e($employee['department']) ?></div></div>
        <div><span class="muted">Designation</span><div><?= e($employee['designation']) ?></div></div>
        <div><span class="muted">Joining Date</span><div><?= e($employee['joining_date']) ?></div></div>
        <div><span class="muted">Basic Salary</span><div><?= e(money($employee['basic_salary'])) ?></div></div>
        <div><span class="muted">Status</span><div><span class="badge badge-<?= $employee['status'] === 'active' ? 'approved' : 'rejected' ?>"><?= e($employee['status']) ?></span></div></div>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h2>Attendance — <?= e($month) ?></h2>
        <a class="btn btn-sm" href="<?= e(url('/attendance/report?employee_id=' . $employee['id'] . '&month=' . $month)) ?>">Monthly report</a>
    </div>
    <?php if (!$attendance): ?>
        <p class="muted">No attendance this month.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Date</th><th>In</th><th>Out</th><th>Hours</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($attendance as $a): ?>
            <tr>
                <td><?= e($a['attendance_date']) ?></td>
                <td><?= e($a['check_in'] ?: '—') ?></td>
                <td><?= e($a['check_out'] ?: '—') ?></td>
                <td><?= e($a['work_hours']) ?></td>
                <td><span class="badge badge-<?= e(strtolower(str_replace('-', '', $a['status']))) ?>"><?= e($a['status']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-head"><h2>Leave History</h2></div>
    <?php if (!$leaves): ?>
        <p class="muted">No leave requests.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Type</th><th>Dates</th><th>Days</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($leaves as $l): ?>
            <tr>
                <td><?= e($l['leave_type']) ?></td>
                <td><?= e($l['start_date']) ?> → <?= e($l['end_date']) ?></td>
                <td><?= e($l['days']) ?></td>
                <td><span class="badge badge-<?= e(strtolower($l['status'])) ?>"><?= e($l['status']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-head"><h2>Payslips</h2></div>
    <?php if (!$salaries): ?>
        <p class="muted">No payslips generated.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Month</th><th>Net Salary</th><th>Deductions</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($salaries as $s): ?>
            <tr>
                <td><?= e($s['month']) ?></td>
                <td><?= e(money($s['net_salary'])) ?></td>
                <td><?= e(money($s['deductions'])) ?></td>
                <td><a class="btn btn-sm" target="_blank" href="<?= e(url('/payslips/' . $s['id'])) ?>">Payslip</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
