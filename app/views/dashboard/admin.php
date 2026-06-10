<?php
/** @var int $totalEmployees @var int $presentToday @var int $absentToday
 *  @var int $onLeaveToday @var int $pendingLeaves @var float $monthPayroll
 *  @var string $month @var array $recentLeaves */
?>
<div class="page-head">
    <h1>Dashboard</h1>
    <span class="muted"><?= e(date('l, d M Y')) ?></span>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Employees</div>
        <div class="stat-value"><?= e($totalEmployees) ?></div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-label">Present Today</div>
        <div class="stat-value"><?= e($presentToday) ?></div>
    </div>
    <div class="stat-card stat-red">
        <div class="stat-label">Absent Today</div>
        <div class="stat-value"><?= e($absentToday) ?></div>
    </div>
    <div class="stat-card stat-amber">
        <div class="stat-label">On Leave Today</div>
        <div class="stat-value"><?= e($onLeaveToday) ?></div>
    </div>
    <div class="stat-card stat-blue">
        <div class="stat-label">Pending Leave Requests</div>
        <div class="stat-value"><?= e($pendingLeaves) ?></div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-label">Payroll (<?= e($month) ?>)</div>
        <div class="stat-value"><?= e(money($monthPayroll)) ?></div>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h2>Recent Leave Requests</h2>
        <a class="btn btn-sm" href="<?= e(url('/leaves')) ?>">View all</a>
    </div>
    <?php if (!$recentLeaves): ?>
        <p class="muted">No leave requests yet.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Employee</th><th>Type</th><th>Dates</th><th>Days</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($recentLeaves as $l): ?>
            <tr>
                <td><?= e($l['name']) ?> <span class="muted">(<?= e($l['employee_code']) ?>)</span></td>
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

<div class="quick-actions">
    <a class="btn btn-primary" href="<?= e(url('/employees/create')) ?>">+ Add Employee</a>
    <a class="btn" href="<?= e(url('/attendance')) ?>">Mark Attendance</a>
    <a class="btn" href="<?= e(url('/payroll')) ?>">Run Payroll</a>
</div>
