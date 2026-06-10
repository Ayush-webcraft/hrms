<?php /** @var string $nextCode */ ?>
<div class="page-head"><h1>Add Employee</h1></div>

<div class="card">
    <form method="post" action="<?= e(url('/employees')) ?>" class="form-grid">
        <?= csrf_field() ?>

        <label>Employee Code
            <input type="text" name="employee_code" value="<?= e(old('employee_code', $nextCode)) ?>" readonly>
        </label>
        <label>Full Name *
            <input type="text" name="name" value="<?= e(old('name')) ?>" required>
        </label>
        <label>Email *
            <input type="email" name="email" value="<?= e(old('email')) ?>" required>
        </label>
        <label>Phone
            <input type="text" name="phone" value="<?= e(old('phone')) ?>">
        </label>
        <label>Department
            <input type="text" name="department" value="<?= e(old('department')) ?>" placeholder="Engineering">
        </label>
        <label>Designation
            <input type="text" name="designation" value="<?= e(old('designation')) ?>" placeholder="Software Engineer">
        </label>
        <label>Joining Date
            <input type="date" name="joining_date" value="<?= e(old('joining_date', date('Y-m-d'))) ?>">
        </label>
        <label>Basic Salary (monthly)
            <input type="number" step="0.01" min="0" name="basic_salary" value="<?= e(old('basic_salary', '0')) ?>">
        </label>
        <label>Status
            <select name="status">
                <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </label>

        <fieldset class="form-fieldset full">
            <legend>Login account (optional)</legend>
            <p class="muted sm">Create an employee login so they can check in and apply for leave. Leave blank to skip.</p>
            <div class="form-grid">
                <label>Login Email
                    <input type="email" name="login_email" value="<?= e(old('login_email')) ?>">
                </label>
                <label>Login Password
                    <input type="text" name="login_password" placeholder="min 4 chars">
                </label>
            </div>
        </fieldset>

        <div class="form-actions full">
            <button class="btn btn-primary" type="submit">Save Employee</button>
            <a class="btn btn-ghost" href="<?= e(url('/employees')) ?>">Cancel</a>
        </div>
    </form>
</div>
