<?php /** @var string $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 — Not Found</title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>
<main class="container-narrow">
    <div class="auth-card">
        <h1 class="auth-title">404</h1>
        <p class="auth-sub">The page you’re looking for doesn’t exist.</p>
        <a class="btn btn-primary btn-block" href="<?= e(url('/dashboard')) ?>">Go to dashboard</a>
    </div>
</main>
</body>
</html>
