<?php /** @var array|null $employee @var array|null $today @var string $month @var array $records */ ?>
<div class="page-head">
    <h1>My Attendance</h1>
    <a class="btn btn-sm" href="<?= e(url('/attendance/report?month=' . $month)) ?>">Monthly report</a>
</div>

<?php if (!$employee): ?>
    <div class="alert alert-error">Your login isn't linked to an employee profile yet.</div>
<?php else: ?>

<div class="card">
    <div class="card-head"><h2>Today — <?= e(date('d M Y')) ?></h2></div>
    <div class="checkin-row">
        <?php if (!$today): ?>
            <form method="post" action="<?= e(url('/attendance/checkin')) ?>">
                <?= csrf_field() ?>
                <button class="btn btn-primary" type="submit">Check In</button>
            </form>
            <span class="muted">Not checked in yet.</span>
        <?php else: ?>
            <div>
                <div>Check-in: <strong><?= e($today['check_in'] ?: '—') ?></strong></div>
                <div>Check-out: <strong><?= e($today['check_out'] ?: '—') ?></strong></div>
                <div>Status: <span class="badge badge-<?= e(strtolower(str_replace('-', '', $today['status']))) ?>"><?= e($today['status']) ?></span></div>
            </div>
            <?php if (empty($today['check_out']) && !empty($today['check_in'])): ?>
                <form method="post" action="<?= e(url('/attendance/checkout')) ?>">
                    <?= csrf_field() ?>
                    <button class="btn btn-primary" type="submit">Check Out</button>
                </form>
            <?php else: ?>
                <span class="muted">Day complete · <?= e($today['work_hours']) ?>h worked, <?= e($today['overtime_hours']) ?>h OT</span>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-head"><h2>This Month (<?= e($month) ?>)</h2></div>
    <?php if (!$records): ?>
        <p class="muted">No attendance recorded this month.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Date</th><th>In</th><th>Out</th><th>Hours</th><th>OT</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach (array_reverse($records) as $a): ?>
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

<?php endif; ?>
