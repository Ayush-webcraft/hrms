<?php /** @var string $month @var array $salaries @var float $total @var array $employees */ ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Payroll</h1>
    <p class="text-sm text-slate-500 mt-0.5">Generate and view monthly payslips</p>
</div>

<!-- Generate -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-5">
    <h2 class="font-semibold text-slate-900 mb-1">Generate Payroll</h2>
    <p class="text-xs text-slate-400 mb-4">
        Calculates each active employee's salary from their attendance:
        per-day = basic ÷ days-in-month, deducts absences, adds overtime, keeps paid leave.
        Re-running overwrites that month's payslips.
    </p>
    <form method="post" action="<?= e(url('/payroll/generate')) ?>" class="flex flex-wrap items-end gap-3">
        <?= csrf_field() ?>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Month</label>
            <input type="month" name="month" value="<?= e($month) ?>" required
                   class="px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
        </div>
        <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Generate Payslips
        </button>
    </form>
</div>

<!-- View month filter -->
<form method="get" action="<?= e(url('/payroll')) ?>" class="mb-5">
    <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm w-fit">
        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <label class="text-sm font-semibold text-slate-600">View month</label>
        <input type="month" name="month" value="<?= e($month) ?>" onchange="this.form.submit()"
               class="border-none text-sm font-medium text-slate-800 focus:outline-none bg-transparent cursor-pointer">
    </div>
</form>

<!-- Payslips table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Payslips — <?= e($month) ?></h2>
        <?php if ($total > 0): ?>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-indigo-50 rounded-xl">
            <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wide">Total</span>
            <span class="font-bold text-indigo-700"><?= e(money($total)) ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php if (!$salaries): ?>
        <div class="py-14 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-slate-400 font-medium">No payroll generated for this month yet.</p>
            <p class="text-xs text-slate-400 mt-1">Use "Generate Payslips" above to create them.</p>
        </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Employee</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden md:table-cell">Basic</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Present</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Absent</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Leave</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden xl:table-cell">OT (₹)</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden md:table-cell">Deductions</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Net Salary</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php foreach ($salaries as $s): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-800"><?= e($s['name']) ?></div>
                        <div class="text-xs font-mono text-slate-400 mt-0.5"><?= e($s['employee_code']) ?></div>
                    </td>
                    <td class="px-5 py-4 text-slate-600 hidden md:table-cell"><?= e(money($s['basic_salary'])) ?></td>
                    <td class="px-5 py-4 text-slate-600 hidden lg:table-cell"><?= e($s['present_days']) ?></td>
                    <td class="px-5 py-4 text-slate-600 hidden lg:table-cell"><?= e($s['absent_days']) ?></td>
                    <td class="px-5 py-4 text-slate-600 hidden lg:table-cell"><?= e($s['leave_days']) ?></td>
                    <td class="px-5 py-4 text-slate-600 hidden xl:table-cell"><?= e(number_format($s['overtime_amount'], 0)) ?></td>
                    <td class="px-5 py-4 text-red-500 font-medium hidden md:table-cell">−<?= e(money($s['deductions'])) ?></td>
                    <td class="px-5 py-4">
                        <span class="font-bold text-slate-900"><?= e(money($s['net_salary'])) ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <a href="<?= e(url('/payslips/' . $s['id'])) ?>" target="_blank"
                           class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                            Payslip
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>