<?php /** @var array $leaves */ ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Leave Requests</h1>
    <p class="text-sm text-slate-500 mt-0.5">Review and action pending leave applications</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <?php if (!$leaves): ?>
        <div class="py-16 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <p class="text-sm text-slate-400 font-medium">No leave requests.</p>
        </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/60">
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Employee</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden md:table-cell">Type</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Dates & Days</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Reason</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <?php foreach ($leaves as $l): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-800"><?= e($l['name']) ?></div>
                        <div class="text-xs font-mono text-slate-400 mt-0.5"><?= e($l['employee_code']) ?></div>
                    </td>
                    <td class="px-5 py-4 text-slate-600 hidden md:table-cell">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg"><?= e($l['leave_type']) ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-mono text-xs text-slate-600"><?= e($l['start_date']) ?> → <?= e($l['end_date']) ?></div>
                        <div class="text-xs text-slate-400 mt-0.5"><?= e($l['days']) ?> day<?= $l['days'] > 1 ? 's' : '' ?></div>
                    </td>
                    <td class="px-5 py-4 text-xs text-slate-500 max-w-xs hidden lg:table-cell">
                        <?= e($l['reason'] ?: '—') ?>
                    </td>
                    <td class="px-5 py-4">
                        <span class="badge badge-<?= e(strtolower($l['status'])) ?>"><?= e($l['status']) ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <?php if ($l['status'] === 'Pending'): ?>
                        <div class="flex items-center gap-2">
                            <form method="post" action="<?= e(url('/leaves/' . $l['id'] . '/approve')) ?>">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors">
                                    Approve
                                </button>
                            </form>
                            <form method="post" action="<?= e(url('/leaves/' . $l['id'] . '/reject')) ?>">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors">
                                    Reject
                                </button>
                            </form>
                        </div>
                        <?php else: ?>
                            <span class="text-xs text-slate-400 font-medium">Reviewed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>