<?php
/** @var string $month @var array|null $employee @var int $employeeId
 *  @var array $employees @var array $records @var array|null $summary */
?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Monthly Attendance Report</h1>
    <p class="text-sm text-slate-500 mt-0.5">View attendance summary and daily records</p>
</div>

<!-- Filters -->
<form method="get" action="<?= e(url('/attendance/report')) ?>" class="mb-6">
    <div class="flex flex-wrap items-end gap-3">
        <?php if (Auth::isAdmin()): ?>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Employee</label>
            <select name="employee_id" onchange="this.form.submit()"
                    class="px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-white transition-colors min-w-56">
                <?php foreach ($employees as $emp): ?>
                    <option value="<?= e($emp['id']) ?>" <?= (int) $emp['id'] === $employeeId ? 'selected' : '' ?>>
                        <?= e($emp['employee_code']) ?> — <?= e($emp['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Month</label>
            <input type="month" name="month" value="<?= e($month) ?>" onchange="this.form.submit()"
                   class="px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
        </div>
    </div>
</form>

<!-- Summary stats -->
<?php if ($summary): ?>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
        <div class="text-3xl font-bold text-emerald-600"><?= e($summary['present']) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Present</div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
        <div class="text-3xl font-bold text-amber-500"><?= e($summary['half']) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Half-Days</div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
        <div class="text-3xl font-bold text-red-500"><?= e($summary['absent']) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Absent</div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
        <div class="text-3xl font-bold text-blue-500"><?= e($summary['leave']) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">On Leave</div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
        <div class="text-3xl font-bold text-indigo-600"><?= e(number_format($summary['overtime_hours'], 1)) ?></div>
        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">OT Hours</div>
    </div>
</div>
<?php endif; ?>

<!-- Records table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">
            <?= $employee ? e($employee['name']) : 'Employee' ?> — <?= e($month) ?>
        </h2>
    </div>
    <?php if (!$records): ?>
        <div class="py-14 text-center text-sm text-slate-400">No attendance recorded for this period.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Day</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Check In</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Check Out</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Hours</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">OT</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php foreach ($records as $a): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-slate-600"><?= e($a['attendance_date']) ?></td>
                    <td class="px-5 py-3 text-slate-500"><?= e(date('D', strtotime($a['attendance_date']))) ?></td>
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