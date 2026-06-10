<?php /** @var array $employee */ ?>
<div class="page-head"><h1>Edit Employee</h1></div>

<div class="card">
    <form method="post" action="<?= e(url('/employees/' . $employee['id'])) ?>" class="form-grid">
        <?= csrf_field() ?>

        <label>Employee Code
            <input type="text" value="<?= e($employee['employee_code']) ?>" readonly>
        </label>
        <label>Full Name *
            <input type="text" name="name" value="<?= e(old('name', $employee['name'])) ?>" required>
        </label>
        <label>Email *
            <input type="email" name="email" value="<?= e(old('email', $employee['email'])) ?>" required>
        </label>
        <label>Phone
            <input type="text" name="phone" value="<?= e(old('phone', $employee['phone'])) ?>">
        </label>
        <label>Department
            <input type="text" name="department" value="<?= e(old('department', $employee['department'])) ?>">
        </label>
        <label>Designation
            <input type="text" name="designation" value="<?= e(old('designation', $employee['designation'])) ?>">
        </label>
        <label>Joining Date
            <input type="date" name="joining_date" value="<?= e(old('joining_date', $employee['joining_date'])) ?>">
        </label>
        <label>Basic Salary (monthly)
            <input type="number" step="0.01" min="0" name="basic_salary" value="<?= e(old('basic_salary', $employee['basic_salary'])) ?>">
        </label>
        <label>Status
            <?php $st = old('status', $employee['status']); ?>
            <select name="status">
                <option value="active" <?= $st === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $st === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </label>

        <div class="form-actions full">
            <button class="btn btn-primary" type="submit">Update Employee</button>
            <a class="btn btn-ghost" href="<?= e(url('/employees/' . $employee['id'])) ?>">Cancel</a>
        </div>
    </form>
</div>
