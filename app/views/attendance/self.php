<?php /** @var array|null $employee @var array|null $today @var string $month @var array $records */ ?>
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">My Attendance</h1>
        <p class="text-sm text-slate-500 mt-0.5"><?= e(date('l, d F Y')) ?></p>
    </div>
    <a href="<?= e(url('/attendance/report?month=' . $month)) ?>"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Monthly Report
    </a>
</div>

<?php if (!$employee): ?>
<div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-800 font-medium">
    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Your login isn't linked to an employee profile yet.
</div>
<?php else: ?>

<!-- Today's card -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-5">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Today — <?= e(date('d M Y')) ?></h2>
    </div>
    <div class="px-6 py-6">
        <?php if (!$today): ?>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <form method="post" action="<?= e(url('/attendance/checkin')) ?>">
                <?= csrf_field() ?>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Check In Now
                </button>
            </form>
            <p class="text-sm text-slate-400">You haven't checked in today.</p>
        </div>
        <?php else: ?>
        <div class="flex flex-wrap items-center gap-8">
            <div class="flex items-center gap-8">
                <div>
                    <div class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Check-in</div>
                    <div class="text-2xl font-bold text-slate-900 font-mono"><?= e($today['check_in'] ?: '—') ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Check-out</div>
                    <div class="text-2xl font-bold text-slate-900 font-mono"><?= e($today['check_out'] ?: '—') ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Status</div>
                    <span class="badge badge-<?= e(strtolower(str_replace('-', '', $today['status']))) ?>"><?= e($today['status']) ?></span>
                </div>
            </div>
            <?php if (empty($today['check_out']) && !empty($today['check_in'])): ?>
                <form method="post" action="<?= e(url('/attendance/checkout')) ?>">
                    <?= csrf_field() ?>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Check Out
                    </button>
                </form>
            <?php else: ?>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Day complete · <?= e($today['work_hours']) ?>h worked, <?= e($today['overtime_hours']) ?>h OT
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- This month's records -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">This Month — <?= e($month) ?></h2>
    </div>
    <?php if (!$records): ?>
        <div class="py-12 text-center text-sm text-slate-400">No attendance recorded this month.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">In</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Out</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Hours</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">OT</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php foreach (array_reverse($records) as $a): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-slate-600"><?= e($a['attendance_date']) ?></td>
                    <td class="px-5 py-3 font-mono text-xs text-slate-600"><?= e($a['check_in'] ?: '—') ?></td>
                    <td class="px-5 py-3 font-mono text-xs text-slate-600"><?= e($a['check_out'] ?: '—') ?></td>
                    <td class="px-5 py-3 text-slate-600"><?= e($a['work_hours']) ?>h</td>
                    <td class="px-5 py-3 text-slate-600 hidden lg:table-cell"><?= e($a['overtime_hours']) ?>h</td>
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

<?php endif; ?>