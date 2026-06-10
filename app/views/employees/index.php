<?php /** @var array $employees @var string $search */ ?>
<div class="page-head">
    <h1>Employees</h1>
    <a class="btn btn-primary" href="<?= e(url('/employees/create')) ?>">+ Add Employee</a>
</div>

<form class="search-bar" method="get" action="<?= e(url('/employees')) ?>">
    <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search name, code, email, department…">
    <button class="btn" type="submit">Search</button>
    <?php if ($search !== ''): ?><a class="btn btn-ghost" href="<?= e(url('/employees')) ?>">Clear</a><?php endif; ?>
</form>

<div class="card">
    <?php if (!$employees): ?>
        <p class="muted">No employees found.</p>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Basic Salary</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($employees as $emp): ?>
            <tr>
                <td><code><?= e($emp['employee_code']) ?></code></td>
                <td>
                    <a href="<?= e(url('/employees/' . $emp['id'])) ?>"><?= e($emp['name']) ?></a>
                    <div class="muted sm"><?= e($emp['email']) ?></div>
                </td>
                <td><?= e($emp['department']) ?></td>
                <td><?= e($emp['designation']) ?></td>
                <td><?= e(money($emp['basic_salary'])) ?></td>
                <td><span class="badge badge-<?= $emp['status'] === 'active' ? 'approved' : 'rejected' ?>"><?= e($emp['status']) ?></span></td>
                <td class="actions">
                    <a class="btn btn-sm" href="<?= e(url('/employees/' . $emp['id'] . '/edit')) ?>">Edit</a>
                    <form method="post" action="<?= e(url('/employees/' . $emp['id'] . '/delete')) ?>"
                          onsubmit="return confirm('Delete <?= e($emp['name']) ?>? This removes their attendance, leaves and payslips too.');">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
