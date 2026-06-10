<?php /** @var array $leaves */ ?>
<div class="page-head"><h1>Leave Requests</h1></div>

<div class="card">
    <?php if (!$leaves): ?>
        <p class="muted">No leave requests.</p>
    <?php else: ?>
    <table class="table">
        <thead><tr><th>Employee</th><th>Type</th><th>Dates</th><th>Days</th><th>Reason</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($leaves as $l): ?>
            <tr>
                <td><?= e($l['name']) ?> <span class="muted">(<?= e($l['employee_code']) ?>)</span></td>
                <td><?= e($l['leave_type']) ?></td>
                <td><?= e($l['start_date']) ?> → <?= e($l['end_date']) ?></td>
                <td><?= e($l['days']) ?></td>
                <td class="muted"><?= e($l['reason'] ?: '—') ?></td>
                <td><span class="badge badge-<?= e(strtolower($l['status'])) ?>"><?= e($l['status']) ?></span></td>
                <td class="actions">
                    <?php if ($l['status'] === 'Pending'): ?>
                        <form method="post" action="<?= e(url('/leaves/' . $l['id'] . '/approve')) ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-success" type="submit">Approve</button>
                        </form>
                        <form method="post" action="<?= e(url('/leaves/' . $l['id'] . '/reject')) ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-danger" type="submit">Reject</button>
                        </form>
                    <?php else: ?>
                        <span class="muted sm">Reviewed</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
