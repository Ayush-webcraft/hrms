<?php
/** @var int $totalEmployees @var int $presentToday @var int $absentToday
 *  @var int $onLeaveToday @var int $pendingLeaves @var float $monthPayroll
 *  @var string $month @var array $recentLeaves */
?>
<!-- Page header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-0.5"><?= e(date('l, d F Y')) ?></p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= e(url('/attendance')) ?>"
           class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Attendance
        </a>
        <a href="<?= e(url('/employees/create')) ?>"
           class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Employee
        </a>
    </div>
</div>

<!-- Stats grid -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-slate-900"><?= e($totalEmployees) ?></div>
        <div class="text-sm text-slate-500 mt-1 font-medium">Total Employees</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Today</span>
        </div>
        <div class="text-3xl font-bold text-slate-900"><?= e($presentToday) ?></div>
        <div class="text-sm text-slate-500 mt-1 font-medium">Present</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-1 rounded-lg">Today</span>
        </div>
        <div class="text-3xl font-bold text-slate-900"><?= e($absentToday) ?></div>
        <div class="text-sm text-slate-500 mt-1 font-medium">Absent</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Today</span>
        </div>
        <div class="text-3xl font-bold text-slate-900"><?= e($onLeaveToday) ?></div>
        <div class="text-sm text-slate-500 mt-1 font-medium">On Leave</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <?php if ($pendingLeaves > 0): ?>
            <span class="text-xs font-bold text-white bg-blue-600 px-2 py-1 rounded-lg"><?= e($pendingLeaves) ?> pending</span>
            <?php endif; ?>
        </div>
        <div class="text-3xl font-bold text-slate-900"><?= e($pendingLeaves) ?></div>
        <div class="text-sm text-slate-500 mt-1 font-medium">Leave Requests</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg"><?= e($month) ?></span>
        </div>
        <div class="text-2xl font-bold text-slate-900"><?= e(money($monthPayroll)) ?></div>
        <div class="text-sm text-slate-500 mt-1 font-medium">Payroll</div>
    </div>

</div>

<!-- Recent leave requests -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Recent Leave Requests</h2>
        <a href="<?= e(url('/leaves')) ?>"
           class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">View all →</a>
    </div>
    <?php if (!$recentLeaves): ?>
        <div class="px-5 py-10 text-center text-sm text-slate-400">No leave requests yet.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Employee</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Type</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Dates</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Days</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($recentLeaves as $l): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-slate-800"><?= e($l['name']) ?></span>
                        <span class="ml-1.5 text-xs text-slate-400 font-mono"><?= e($l['employee_code']) ?></span>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600"><?= e($l['leave_type']) ?></td>
                    <td class="px-5 py-3.5 text-slate-600 font-mono text-xs">
                        <?= e($l['start_date']) ?> → <?= e($l['end_date']) ?>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600"><?= e($l['days']) ?></td>
                    <td class="px-5 py-3.5">
                        <span class="badge badge-<?= e(strtolower($l['status'])) ?>"><?= e($l['status']) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Quick actions -->
<div class="mt-6 flex flex-wrap gap-3">
    <a href="<?= e(url('/payroll')) ?>"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Run Payroll
    </a>
    <a href="<?= e(url('/attendance/report')) ?>"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Attendance Report
    </a>
</div>