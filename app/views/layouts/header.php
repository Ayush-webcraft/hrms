<?php /** @var string $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app.name')) ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>
<?php if (Auth::check()): ?>
    <?php $role = Auth::role(); ?>
    <header class="topbar">
        <a class="brand" href="<?= e(url('/dashboard')) ?>">🧑‍💼 HRMS</a>
        <nav class="nav">
            <a href="<?= e(url('/dashboard')) ?>">Dashboard</a>
            <?php if ($role === 'admin'): ?>
                <a href="<?= e(url('/employees')) ?>">Employees</a>
                <a href="<?= e(url('/attendance')) ?>">Attendance</a>
                <a href="<?= e(url('/leaves')) ?>">Leaves</a>
                <a href="<?= e(url('/payroll')) ?>">Payroll</a>
            <?php else: ?>
                <a href="<?= e(url('/attendance')) ?>">My Attendance</a>
                <a href="<?= e(url('/leaves')) ?>">My Leaves</a>
            <?php endif; ?>
        </nav>
        <div class="user-menu">
            <span class="user-name"><?= e(Auth::user()['name']) ?> <span class="role-badge"><?= e($role) ?></span></span>
            <a class="btn btn-ghost btn-sm" href="<?= e(url('/logout')) ?>">Log out</a>
        </div>
    </header>
<?php endif; ?>

<main class="<?= Auth::check() ? 'container' : 'container-narrow' ?>">
    <?php if ($msg = flash('success')): ?>
        <div class="alert alert-success"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="alert alert-error"><?= e($msg) ?></div>
    <?php endif; ?>
