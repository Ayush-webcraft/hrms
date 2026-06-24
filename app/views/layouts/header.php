<?php /** @var string $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app.name')) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

<?php if (Auth::check()):
    $role = Auth::role();
    $user = Auth::user();
    $cp   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $nav  = fn(string $p): string => strpos($cp, $p) === 0
        ? 'bg-indigo-500/20 text-indigo-300'
        : 'text-slate-400 hover:bg-white/5 hover:text-slate-200';
?>
<div x-data="{ open: false }">

    <!-- Mobile overlay -->
    <div x-show="open" x-cloak x-transition.opacity @click="open=false"
         class="fixed inset-0 bg-black/50 z-20 lg:hidden"></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 bg-slate-900 transition-transform duration-200 lg:translate-x-0"
           :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        <!-- Brand -->
        <div class="flex items-center gap-3 h-16 px-5 border-b border-white/10 shrink-0">
            <div class="w-9 h-9 bg-indigo-500 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-white font-bold text-sm leading-tight">HRMS</div>
                <div class="text-slate-500 text-xs">HR Management</div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            <a href="<?= e(url('/dashboard')) ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $nav('/dashboard') ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <?php if ($role === 'admin'): ?>
            <a href="<?= e(url('/employees')) ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $nav('/employees') ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Employees
            </a>
            <?php endif; ?>

            <a href="<?= e(url('/attendance')) ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $nav('/attendance') ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <?= $role === 'admin' ? 'Attendance' : 'My Attendance' ?>
            </a>

            <a href="<?= e(url('/leaves')) ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $nav('/leaves') ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <?= $role === 'admin' ? 'Leave Requests' : 'My Leaves' ?>
            </a>

            <?php if ($role === 'admin'): ?>
            <a href="<?= e(url('/payroll')) ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $nav('/payroll') ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Payroll
            </a>
            <?php endif; ?>
        </nav>

        <!-- User / logout -->
        <div class="px-3 py-4 border-t border-white/10 shrink-0">
            <div class="flex items-center gap-3 px-3 py-2 mb-1">
                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center shrink-0 text-white text-sm font-bold">
                    <?= strtoupper(substr(e($user['name']), 0, 1)) ?>
                </div>
                <div class="min-w-0">
                    <div class="text-slate-200 text-sm font-semibold truncate"><?= e($user['name']) ?></div>
                    <div class="text-slate-500 text-xs capitalize"><?= e($role) ?></div>
                </div>
            </div>
            <a href="<?= e(url('/logout')) ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-slate-200 transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign out
            </a>
        </div>
    </aside>

    <!-- Main content -->
    <div class="lg:ml-64 flex flex-col min-h-screen">

        <!-- Topbar -->
        <header class="flex items-center h-14 px-4 lg:px-6 bg-white border-b border-slate-200 sticky top-0 z-10 shrink-0 gap-3">
            <button @click="open = !open"
                    class="lg:hidden p-1.5 -ml-1.5 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="ml-auto text-sm text-slate-400"><?= e(date('l, d M Y')) ?></span>
        </header>

        <!-- Page content -->
        <main class="flex-1 p-4 lg:p-6 w-full max-w-screen-xl mx-auto">

            <?php if ($msg = flash('success')): ?>
            <div x-data="{ v: true }" x-show="v" x-cloak x-transition
                 class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800">
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="flex-1 font-medium"><?= e($msg) ?></span>
                <button @click="v=false" class="text-emerald-400 hover:text-emerald-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <?php endif; ?>

            <?php if ($msg = flash('error')): ?>
            <div x-data="{ v: true }" x-show="v" x-cloak x-transition
                 class="mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-800">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="flex-1 font-medium"><?= e($msg) ?></span>
                <button @click="v=false" class="text-red-400 hover:text-red-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <?php endif; ?>

<?php else: ?>
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-slate-100 flex items-center justify-center p-4">
<main class="w-full max-w-sm">
    <?php if ($msg = flash('error')): ?>
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-800 font-medium"><?= e($msg) ?></div>
    <?php endif; ?>
<?php endif; ?>
