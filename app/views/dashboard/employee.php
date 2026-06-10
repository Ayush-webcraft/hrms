<?php
/** @var array|null $employee @var array|null $todayAtt @var int $leaveQuota
 *  @var int $leaveUsed @var int $leaveBalance @var array $recentAtt
 *  @var array $latestSlips @var string $month */
?>
<div class="page-head">
    <h1>Welcome, <?= e(Auth::user()['name']) ?></h1>
    <span class="muted"><?= e(date('l, d M Y')) ?></span>
</div>

<?php if (!$employee): ?>
    <div class="alert alert-error">Your login isn't linked to an employee profile yet. Ask HR to link it.</div>
<?php else: ?>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Employee Code</div>
        <div class="stat-value sm"><?= e($employee['employee_code']) ?></div>
    </div>
    <div class="stat-card stat-blue">
        <div class="stat-label">Department</div>
        <div class="stat-value sm"><?= e($employee['department']) ?></div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-label">Leave Balance</div>
        <div class="stat-value"><?= e($leaveBalance) ?><span class="stat-unit">/ <?= e($leaveQuota) ?></span></div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-label">Today's Status</div>
        <div class="stat-value sm">
            <?php if ($todayAtt): ?>
                <span class="badge badge-<?= e(strtolower(str_replace('-', '', $todayAtt['status']))) ?>"><?= e($todayAtt['status']) ?></span>
            <?php else: ?>
                <span class="muted">Not checked in</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-head"><h2>Check In / Out</h2></div>
    <div class="checkin-row">
        <?php if (!$todayAtt): ?>
            <form method="post" action="<?= e(url('/attendance/checkin')) ?>">
                <?= csrf_field() ?>
                <button class="btn btn-primary" type="submit">Check In</button>
            </form>
            <span class="muted">You haven't checked in today.</span>
        <?php else: ?>
            <div>
                <div>Check-in: <strong><?= e($todayAtt['check_in'] ?: '—') ?></strong></div>
                <div>Check-out: <strong><?= e($todayAtt['check_out'] ?: '—') ?></strong></div>
            </div>
            <?php if (empty($todayAtt['check_out']) && !empty($todayAtt['check_in'])): ?>
                <form method="post" action="<?= e(url('/attendance/checkout')) ?>">
                    <?= csrf_field() ?>
                    <button class="btn btn-primary" type="submit">Check Out</button>
                </form>
            <?php else: ?>
                <span class="muted">Day complete · <?= e($todayAtt['work_hours']) ?>h worked</span>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h2>This Month's Attendance (<?= e($month) ?>)</h2>
        <a class="btn btn-sm" href="<?= e(url('/attendance/report?month=' . $month)) ?>">Full report</a>
    </div>
    <?php if (!$recentAtt): ?>
        <p class="muted">No attendance recorded this month.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Date</th><th>In</th><th>Out</th><th>Hours</th><th>OT</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach (array_reverse($recentAtt) as $a): ?>
            <tr>
                <td><?= e($a['attendance_date']) ?></td>
                <td><?= e($a['check_in'] ?: '—') ?></td>
                <td><?= e($a['check_out'] ?: '—') ?></td>
                <td><?= e($a['work_hours']) ?></td>
                <td><?= e($a['overtime_hours']) ?></td>
                <td><span class="badge badge-<?= e(strtolower(str_replace('-', '', $a['status']))) ?>"><?= e($a['status']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-head"><h2>Recent Payslips</h2></div>
    <?php if (!$latestSlips): ?>
        <p class="muted">No payslips generated yet.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Month</th><th>Net Salary</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($latestSlips as $s): ?>
            <tr>
                <td><?= e($s['month']) ?></td>
                <td><?= e(money($s['net_salary'])) ?></td>
                <td><a class="btn btn-sm" target="_blank" href="<?= e(url('/payslips/' . $s['id'])) ?>">View payslip</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php endif; ?>
