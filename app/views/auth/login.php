<div class="auth-card">
    <h1 class="auth-title">🧑‍💼 HRMS</h1>
    <p class="auth-sub">HR Attendance &amp; Payroll Management</p>

    <form method="post" action="<?= e(url('/login')) ?>">
        <?= csrf_field() ?>
        <label>Email
            <input type="email" name="email" value="<?= e(old('email')) ?>" autofocus required>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <button class="btn btn-primary btn-block" type="submit">Sign in</button>
    </form>

    <div class="auth-hint">
        <strong>Demo accounts</strong>
        <div>Admin — <code>admin@hrms.test</code> / <code>password</code></div>
        <div>Employee — <code>aarav@hrms.test</code> / <code>password</code></div>
    </div>
</div>
