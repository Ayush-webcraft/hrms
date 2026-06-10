<?php
/** @var string $month @var array|null $employee @var int $employeeId
 *  @var array $employees @var array $records @var array|null $summary */
?>
<div class="page-head"><h1>Monthly Attendance Report</h1></div>

<form class="search-bar" method="get" action="<?= e(url('/attendance/report')) ?>">
    <?php if (Auth::isAdmin()): ?>
    <label class="inline">Employee
        <select name="employee_id" onchange="this.form.submit()">
            <?php foreach ($employees as $emp): ?>
                <option value="<?= e($emp['id']) ?>" <?= (int) $emp['id'] === $employeeId ? 'selected' : '' ?>>
                    <?= e($emp['employee_code']) ?> — <?= e($emp['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php endif; ?>
    <label class="inline">Month
        <input type="month" name="month" value="<?= e($month) ?>" onchange="this.form.submit()">
    </label>
</form>

<?php if ($summary): ?>
<div class="stat-grid">
    <div class="stat-card stat-green"><div class="stat-label">Present</div><div class="stat-value"><?= e($summary['present']) ?></div></div>
    <div class="stat-card stat-amber"><div class="stat-label">Half-Days</div><div class="stat-value"><?= e($summary['half']) ?></div></div>
    <div class="stat-card stat-red"><div class="stat-label">Absent</div><div class="stat-value"><?= e($summary['absent']) ?></div></div>
    <div class="stat-card stat-blue"><div class="stat-label">On Leave</div><div class="stat-value"><?= e($summary['leave']) ?></div></div>
    <div class="stat-card stat-purple"><div class="stat-label">Overtime (hrs)</div><div class="stat-value"><?= e(number_format($summary['overtime_hours'], 1)) ?></div></div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-head"><h2><?= $employee ? e($employee['name']) : 'Employee' ?> · <?= e($month) ?></h2></div>
    <?php if (!$records): ?>
        <p class="muted">No attendance recorded for this period.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Date</th><th>Day</th><th>In</th><th>Out</th><th>Hours</th><th>OT</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($records as $a): ?>
            <tr>
                <td><?= e($a['attendance_date']) ?></td>
                <td><?= e(date('D', strtotime($a['attendance_date']))) ?></td>
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
