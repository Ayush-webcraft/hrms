<?php /** @var array $leaves @var int $leaveQuota @var int $leaveUsed @var int $leaveBalance */ ?>
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">My Leaves</h1>
        <p class="text-sm text-slate-500 mt-0.5">Your leave requests and balance for <?= e(date('Y')) ?></p>
    </div>
    <a href="<?= e(url('/leaves/create')) ?>"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Apply for Leave
    </a>
</div>

<!-- Leave balance stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm text-center">
        <div class="text-3xl font-bold text-slate-700"><?= e($leaveQuota) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Annual Quota</div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm text-center">
        <div class="text-3xl font-bold text-amber-500"><?= e($leaveUsed) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Used in <?= e(date('Y')) ?></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm text-center">
        <div class="text-3xl font-bold text-emerald-600"><?= e($leaveBalance) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Balance</div>
    </div>
</div>

<!-- Leave bar -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-2">
        <span>Leave used</span>
        <span><?= e($leaveUsed) ?> of <?= e($leaveQuota) ?> days</span>
    </div>
    <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full bg-indigo-500 rounded-full transition-all"
             style="width: <?= e($leaveQuota > 0 ? min(100, round($leaveUsed / $leaveQuota * 100)) : 0) ?>%"></div>
    </div>
</div>

<!-- My requests -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">My Requests</h2>
    </div>
    <?php if (!$leaves): ?>
        <div class="py-14 text-center">
            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm text-slate-400">You haven't applied for any leave yet.</p>
            <a href="<?= e(url('/leaves/create')) ?>"
               class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                Apply now →
            </a>
        </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Type</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Dates</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Days</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Reason</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php foreach ($leaves as $l): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg"><?= e($l['leave_type']) ?></span>
                    </td>
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-600">
                        <?= e($l['start_date']) ?> → <?= e($l['end_date']) ?>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600"><?= e($l['days']) ?></td>
                    <td class="px-5 py-3.5 text-xs text-slate-400 max-w-xs hidden lg:table-cell"><?= e($l['reason'] ?: '—') ?></td>
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