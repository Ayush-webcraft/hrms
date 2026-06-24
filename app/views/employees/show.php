<?php
/** @var array $employee @var array $attendance @var array $leaves
 *  @var array $salaries @var string $month */
?>
<div class="flex items-center gap-3 mb-6">
    <a href="<?= e(url('/employees')) ?>"
       class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="text-2xl font-bold text-slate-900 truncate"><?= e($employee['name']) ?></h1>
        <p class="text-sm text-slate-500 mt-0.5 font-mono"><?= e($employee['employee_code']) ?></p>
    </div>
    <a href="<?= e(url('/employees/' . $employee['id'] . '/edit')) ?>"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit
    </a>
</div>

<!-- Profile card -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-5">
    <div class="flex items-center gap-4 mb-6 pb-5 border-b border-slate-100">
        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center shrink-0 text-3xl font-bold text-indigo-600">
            <?= strtoupper(substr($employee['name'], 0, 1)) ?>
        </div>
        <div>
            <div class="text-xl font-bold text-slate-900"><?= e($employee['name']) ?></div>
            <div class="text-sm text-slate-500 mt-0.5"><?= e($employee['designation']) ?> · <?= e($employee['department']) ?></div>
            <span class="mt-1.5 inline-block badge badge-<?= $employee['status'] === 'active' ? 'approved' : 'rejected' ?>"><?= e($employee['status']) ?></span>
        </div>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div>
            <div class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Email</div>
            <div class="text-sm font-medium text-slate-800"><?= e($employee['email']) ?></div>
        </div>
        <div>
            <div class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Phone</div>
            <div class="text-sm font-medium text-slate-800"><?= e($employee['phone'] ?: '—') ?></div>
        </div>
        <div>
            <div class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Joining Date</div>
            <div class="text-sm font-medium text-slate-800"><?= e($employee['joining_date']) ?></div>
        </div>
        <div>
            <div class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Basic Salary</div>
            <div class="text-sm font-bold text-slate-800"><?= e(money($employee['basic_salary'])) ?></div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-5">

<!-- Attendance -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Attendance — <?= e($month) ?></h2>
        <a href="<?= e(url('/attendance/report?employee_id=' . $employee['id'] . '&month=' . $month)) ?>"
           class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Report →</a>
    </div>
    <?php if (!$attendance): ?>
        <div class="px-5 py-8 text-center text-sm text-slate-400">No attendance this month.</div>
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
            <?php foreach ($attendance as $a): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
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

<!-- Leave history -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Leave History</h2>
    </div>
    <?php if (!$leaves): ?>
        <div class="px-5 py-8 text-center text-sm text-slate-400">No leave requests.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Type</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Dates</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Days</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php foreach ($leaves as $l): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-3 font-medium text-slate-700"><?= e($l['leave_type']) ?></td>
                    <td class="px-5 py-3 font-mono text-xs text-slate-600">
                        <?= e($l['start_date']) ?> → <?= e($l['end_date']) ?>
                    </td>
                    <td class="px-5 py-3 text-slate-600"><?= e($l['days']) ?></td>
                    <td class="px-5 py-3">
                        <span class="badge badge-<?= e(strtolower($l['status'])) ?>"><?= e($l['status']) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

</div>

<!-- Payslips -->
<?php if ($salaries): ?>
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mt-5">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Payslips</h2>
    </div>
    <div class="divide-y divide-slate-50">
        <?php foreach ($salaries as $s): ?>
        <div class="flex items-center justify-between px-5 py-4 hover:bg-slate-50/40 transition-colors">
            <div>
                <div class="font-semibold text-slate-800 text-sm"><?= e($s['month']) ?></div>
                <div class="text-xs text-slate-400 mt-0.5">Deductions: <?= e(money($s['deductions'])) ?></div>
            </div>
            <div class="flex items-center gap-3">
                <span class="font-bold text-slate-900"><?= e(money($s['net_salary'])) ?></span>
                <a href="<?= e(url('/payslips/' . $s['id'])) ?>" target="_blank"
                   class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                    Payslip
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>