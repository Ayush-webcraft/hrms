<?php /** @var array $salary @var array $employee @var string $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip — <?= e($employee['name']) ?> — <?= e($salary['month']) ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body class="payslip-body">
<div class="payslip">
    <div class="payslip-actions no-print">
        <button class="btn btn-primary" onclick="window.print()">🖨 Print / Save as PDF</button>
        <a class="btn btn-ghost" href="<?= e(url('/dashboard')) ?>">Back</a>
    </div>

    <div class="payslip-head">
        <div>
            <h1>HRMS Pvt. Ltd.</h1>
            <p class="muted">HR Attendance &amp; Payroll Management</p>
        </div>
        <div class="payslip-title">
            <h2>Payslip</h2>
            <div><?= e(date('F Y', strtotime($salary['month'] . '-01'))) ?></div>
        </div>
    </div>

    <div class="payslip-meta">
        <div><span class="muted">Employee</span><div><?= e($employee['name']) ?></div></div>
        <div><span class="muted">Code</span><div><?= e($employee['employee_code']) ?></div></div>
        <div><span class="muted">Department</span><div><?= e($employee['department']) ?></div></div>
        <div><span class="muted">Designation</span><div><?= e($employee['designation']) ?></div></div>
    </div>

    <table class="table payslip-table">
        <tbody>
            <tr><td>Working Days</td><td class="num"><?= e($salary['working_days']) ?></td></tr>
            <tr><td>Present Days</td><td class="num"><?= e($salary['present_days']) ?></td></tr>
            <tr><td>Half-Days</td><td class="num"><?= e($salary['half_days']) ?></td></tr>
            <tr><td>Paid Leave Days</td><td class="num"><?= e($salary['leave_days']) ?></td></tr>
            <tr><td>Absent Days</td><td class="num"><?= e($salary['absent_days']) ?></td></tr>
            <tr><td>Per-Day Salary</td><td class="num"><?= e(money($salary['per_day_salary'])) ?></td></tr>
        </tbody>
    </table>

    <table class="table payslip-table">
        <thead><tr><th>Earnings &amp; Deductions</th><th class="num">Amount</th></tr></thead>
        <tbody>
            <tr><td>Basic Salary</td><td class="num"><?= e(money($salary['basic_salary'])) ?></td></tr>
            <tr><td>Overtime (<?= e($salary['overtime_hours']) ?> hrs)</td><td class="num">+ <?= e(money($salary['overtime_amount'])) ?></td></tr>
            <tr><td>Deductions (absence)</td><td class="num">− <?= e(money($salary['deductions'])) ?></td></tr>
            <tr class="payslip-net"><td>Net Salary</td><td class="num"><?= e(money($salary['net_salary'])) ?></td></tr>
        </tbody>
    </table>

    <p class="payslip-foot muted">
        Generated on <?= e(date('d M Y', strtotime($salary['generated_at'] ?? 'now'))) ?>.
        This is a system-generated payslip.
    </p>
</div>
</body>
</html>
