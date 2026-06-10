<?php /** @var string $date @var array $records @var array $employees */ ?>
<div class="page-head">
    <h1>Daily Attendance</h1>
    <a class="btn btn-sm" href="<?= e(url('/attendance/report')) ?>">Monthly report</a>
</div>

<form class="search-bar" method="get" action="<?= e(url('/attendance')) ?>">
    <label class="inline">Date
        <input type="date" name="date" value="<?= e($date) ?>" onchange="this.form.submit()">
    </label>
</form>

<div class="card">
    <div class="card-head"><h2>Mark / Update Attendance</h2></div>
    <form class="row-form" method="post" action="<?= e(url('/attendance/mark')) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="date" value="<?= e($date) ?>">
        <select name="employee_id" required>
            <option value="">Select employee…</option>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= e($emp['id']) ?>"><?= e($emp['employee_code']) ?> — <?= e($emp['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="status" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Half-Day">Half-Day</option>
            <option value="Leave">Leave</option>
        </select>
        <button class="btn btn-primary" type="submit">Save</button>
    </form>
</div>

<div class="card">
    <div class="card-head"><h2>Records for <?= e($date) ?></h2></div>
    <?php if (!$records): ?>
        <p class="muted">No attendance recorded for this date yet.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Code</th><th>Name</th><th>Department</th><th>In</th><th>Out</th><th>Hours</th><th>OT</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($records as $r): ?>
            <tr>
                <td><code><?= e($r['employee_code']) ?></code></td>
                <td><?= e($r['name']) ?></td>
                <td><?= e($r['department']) ?></td>
                <td><?= e($r['check_in'] ?: '—') ?></td>
                <td><?= e($r['check_out'] ?: '—') ?></td>
                <td><?= e($r['work_hours']) ?></td>
                <td><?= e($r['overtime_hours']) ?></td>
                <td><span class="badge badge-<?= e(strtolower(str_replace('-', '', $r['status']))) ?>"><?= e($r['status']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
