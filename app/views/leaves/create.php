<div class="page-head"><h1>Apply for Leave</h1></div>

<div class="card">
    <form method="post" action="<?= e(url('/leaves')) ?>" class="form-grid">
        <?= csrf_field() ?>
        <label>Leave Type
            <select name="leave_type">
                <?php foreach (['Casual', 'Sick', 'Earned', 'Unpaid'] as $type): ?>
                    <option value="<?= e($type) ?>" <?= old('leave_type') === $type ? 'selected' : '' ?>><?= e($type) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Start Date *
            <input type="date" name="start_date" value="<?= e(old('start_date', date('Y-m-d'))) ?>" required>
        </label>
        <label>End Date *
            <input type="date" name="end_date" value="<?= e(old('end_date', date('Y-m-d'))) ?>" required>
        </label>
        <label class="full">Reason
            <textarea name="reason" rows="3" placeholder="Optional"><?= e(old('reason')) ?></textarea>
        </label>
        <div class="form-actions full">
            <button class="btn btn-primary" type="submit">Submit Request</button>
            <a class="btn btn-ghost" href="<?= e(url('/leaves')) ?>">Cancel</a>
        </div>
    </form>
</div>
