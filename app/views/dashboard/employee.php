<?php
/** @var array|null $employee @var array|null $todayAtt @var int $leaveQuota
 *  @var int $leaveUsed @var int $leaveBalance @var array $recentAtt
 *  @var array $latestSlips @var string $month */
?>
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Welcome, <?= e(Auth::user()['name']) ?></h1>
        <p class="text-sm text-slate-500 mt-0.5"><?= e(date('l, d F Y')) ?></p>
    </div>
</div>

<?php if (!$employee): ?>
<div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-800 font-medium">
    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Your login isn't linked to an employee profile yet. Ask HR to link it.
</div>
<?php else: ?>

<!-- Stat cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-4.5 h-4.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
        </div>
        <div class="text-lg font-bold text-slate-900 font-mono"><?= e($employee['employee_code']) ?></div>
        <div class="text-xs text-slate-500 mt-0.5 font-medium">Employee Code</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div class="text-lg font-bold text-slate-900"><?= e($employee['department']) ?></div>
        <div class="text-xs text-slate-500 mt-0.5 font-medium">Department</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <div class="text-2xl font-bold text-slate-900"><?= e($leaveBalance) ?><span class="text-sm font-medium text-slate-400 ml-1">/ <?= e($leaveQuota) ?></span></div>
        <div class="text-xs text-slate-500 mt-0.5 font-medium">Leave Balance</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-4.5 h-4.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="mt-1">
            <?php if ($todayAtt): ?>
                <span class="badge badge-<?= e(strtolower(str_replace('-', '', $todayAtt['status']))) ?>"><?= e($todayAtt['status']) ?></span>
            <?php else: ?>
                <span class="text-sm font-medium text-slate-400">Not checked in</span>
            <?php endif; ?>
        </div>
        <div class="text-xs text-slate-500 mt-1.5 font-medium">Today's Status</div>
    </div>
</div>

<!-- Check in / out -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-5">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Today — <?= e(date('d M Y')) ?></h2>
    </div>
    <div class="px-5 py-5 flex flex-wrap items-center gap-5">
        <?php if (!$todayAtt): ?>
            <form method="post" action="<?= e(url('/attendance/checkin')) ?>">
                <?= csrf_field() ?>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Check In
                </button>
            </form>
            <span class="text-sm text-slate-400">You haven't checked in today.</span>
        <?php else: ?>
            <div class="flex items-center gap-6 text-sm">
                <div>
                    <div class="text-xs text-slate-400 font-medium mb-0.5">Check-in</div>
                    <div class="font-bold text-slate-800 font-mono"><?= e($todayAtt['check_in'] ?: '—') ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 font-medium mb-0.5">Check-out</div>
                    <div class="font-bold text-slate-800 font-mono"><?= e($todayAtt['check_out'] ?: '—') ?></div>
                </div>
            </div>
            <?php if (empty($todayAtt['check_out']) && !empty($todayAtt['check_in'])): ?>
                <form method="post" action="<?= e(url('/attendance/checkout')) ?>">
                    <?= csrf_field() ?>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Check Out
                    </button>
                </form>
            <?php else: ?>
                <div class="inline-flex items-center gap-1.5 text-sm text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Day complete · <?= e($todayAtt['work_hours']) ?>h worked
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-5">

<!-- Attendance this month -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Attendance — <?= e($month) ?></h2>
        <a href="<?= e(url('/attendance/report?month=' . $month)) ?>"
           class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Report →</a>
    </div>
    <?php if (!$recentAtt): ?>
        <div class="px-5 py-8 text-center text-sm text-slate-400">No attendance recorded this month.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">In / Out</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Hours</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php foreach (array_slice(array_reverse($recentAtt), 0, 8) as $a): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-slate-600"><?= e($a['attendance_date']) ?></td>
                    <td class="px-5 py-3 font-mono text-xs text-slate-600">
                        <?= e($a['check_in'] ?: '—') ?> / <?= e($a['check_out'] ?: '—') ?>
                    </td>
                    <td class="px-5 py-3 text-slate-600"><?= e($a['work_hours']) ?>h</td>
                    <td class="px-5 py-3">
                        <span class="badge badge-<?= e(strtolower(str_replace('-', '', $a['status']))) ?>"><?= e($a['status']) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Recent payslips -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Recent Payslips</h2>
    </div>
    <?php if (!$latestSlips): ?>
        <div class="px-5 py-8 text-center text-sm text-slate-400">No payslips generated yet.</div>
    <?php else: ?>
    <div class="divide-y divide-slate-50">
        <?php foreach ($latestSlips as $s): ?>
        <div class="flex items-center justify-between px-5 py-4 hover:bg-slate-50/50 transition-colors">
            <div>
                <div class="font-semibold text-slate-800 text-sm"><?= e($s['month']) ?></div>
                <div class="text-xs text-slate-400 mt-0.5">Net salary</div>
            </div>
            <div class="flex items-center gap-3">
                <span class="font-bold text-slate-900"><?= e(money($s['net_salary'])) ?></span>
                <a href="<?= e(url('/payslips/' . $s['id'])) ?>" target="_blank"
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

</div>
<?php endif; ?>