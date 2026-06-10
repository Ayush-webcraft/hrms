<?php /** @var string $month @var array $salaries @var float $total @var array $employees */ ?>
<div class="page-head"><h1>Payroll</h1></div>

<div class="card">
    <div class="card-head"><h2>Generate Payroll</h2></div>
    <p class="muted sm">
        Calculates each active employee's salary for the selected month from their attendance:
        <code>per-day = basic ÷ days-in-month</code>, deducts absent &amp; half-days, adds overtime, keeps paid leave.
        Re-running overwrites that month's payslips.
    </p>
    <form class="row-form" method="post" action="<?= e(url('/payroll/generate')) ?>">
        <?= csrf_field() ?>
        <label class="inline">Month
            <input type="month" name="month" value="<?= e($month) ?>" required>
        </label>
        <button class="btn btn-primary" type="submit">Generate Payslips</button>
    </form>
</div>

<form class="search-bar" method="get" action="<?= e(url('/payroll')) ?>">
    <label class="inline">View month
        <input type="month" name="month" value="<?= e($month) ?>" onchange="this.form.submit()">
    </label>
</form>

<div class="card">
    <div class="card-head">
        <h2>Payslips — <?= e($month) ?></h2>
        <span class="total-pill">Total: <?= e(money($total)) ?></span>
    </div>
    <?php if (!$salaries): ?>
        <p class="muted">No payroll generated for this month yet. Use “Generate Payslips” above.</p>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr><th>Code</th><th>Name</th><th>Basic</th><th>Present</th><th>Absent</th><th>Leave</th><th>OT (₹)</th><th>Deductions</th><th>Net</th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($salaries as $s): ?>
            <tr>
                <td><code><?= e($s['employee_code']) ?></code></td>
                <td><?= e($s['name']) ?></td>
                <td><?= e(money($s['basic_salary'])) ?></td>
                <td><?= e($s['present_days']) ?></td>
                <td><?= e($s['absent_days']) ?></td>
                <td><?= e($s['leave_days']) ?></td>
                <td><?= e(number_format($s['overtime_amount'], 0)) ?></td>
                <td><?= e(money($s['deductions'])) ?></td>
                <td><strong><?= e(money($s['net_salary'])) ?></strong></td>
                <td><a class="btn btn-sm" target="_blank" href="<?= e(url('/payslips/' . $s['id'])) ?>">Payslip</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
