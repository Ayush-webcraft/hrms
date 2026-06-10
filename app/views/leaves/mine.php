<?php /** @var array $leaves @var int $leaveQuota @var int $leaveUsed @var int $leaveBalance */ ?>
<div class="page-head">
    <h1>My Leaves</h1>
    <a class="btn btn-primary" href="<?= e(url('/leaves/create')) ?>">+ Apply for Leave</a>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-label">Annual Quota</div><div class="stat-value"><?= e($leaveQuota) ?></div></div>
    <div class="stat-card stat-amber"><div class="stat-label">Used (<?= e(date('Y')) ?>)</div><div class="stat-value"><?= e($leaveUsed) ?></div></div>
    <div class="stat-card stat-green"><div class="stat-label">Balance</div><div class="stat-value"><?= e($leaveBalance) ?></div></div>
</div>

<div class="card">
    <div class="card-head"><h2>My Requests</h2></div>
    <?php if (!$leaves): ?>
        <p class="muted">You haven't applied for any leave yet.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Type</th><th>Dates</th><th>Days</th><th>Reason</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($leaves as $l): ?>
            <tr>
                <td><?= e($l['leave_type']) ?></td>
                <td><?= e($l['start_date']) ?> → <?= e($l['end_date']) ?></td>
                <td><?= e($l['days']) ?></td>
                <td class="muted"><?= e($l['reason'] ?: '—') ?></td>
                <td><span class="badge badge-<?= e(strtolower($l['status'])) ?>"><?= e($l['status']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
