<?php /** @var string $date @var array $records @var array $employees */ ?>
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Daily Attendance</h1>
        <p class="text-sm text-slate-500 mt-0.5">Mark and view attendance for a specific date</p>
    </div>
    <a href="<?= e(url('/attendance/report')) ?>"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Monthly Report
    </a>
</div>

<!-- Date picker -->
<form method="get" action="<?= e(url('/attendance')) ?>" class="mb-5">
    <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm w-fit">
        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <label class="text-sm font-semibold text-slate-600">Date</label>
        <input type="date" name="date" value="<?= e($date) ?>" onchange="this.form.submit()"
               class="border-none text-sm font-medium text-slate-800 focus:outline-none bg-transparent cursor-pointer">
    </div>
</form>

<!-- Mark attendance -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-5">
    <h2 class="font-semibold text-slate-900 mb-4">Mark / Update Attendance</h2>
    <form method="post" action="<?= e(url('/attendance/mark')) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="date" value="<?= e($date) ?>">
        <div class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Employee</label>
                <select name="employee_id" required
                        class="px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-white min-w-56 transition-colors">
                    <option value="">Select employee…</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= e($emp['id']) ?>"><?= e($emp['employee_code']) ?> — <?= e($emp['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
                <select name="status" required
                        class="px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-white transition-colors">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Half-Day">Half-Day</option>
                    <option value="Leave">Leave</option>
                </select>
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                Save
            </button>
        </div>
    </form>
</div>

<!-- Records -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-900">Records for <?= e($date) ?></h2>
    </div>
    <?php if (!$records): ?>
        <div class="py-14 text-center text-sm text-slate-400">No attendance recorded for this date yet.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Employee</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden md:table-cell">Department</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">In / Out</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Hours</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">OT</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            <?php foreach ($records as $r): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-semibold text-slate-800"><?= e($r['name']) ?></div>
                        <div class="text-xs font-mono text-slate-400"><?= e($r['employee_code']) ?></div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 hidden md:table-cell"><?= e($r['department']) ?></td>
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-600">
                        <?= e($r['check_in'] ?: '—') ?> / <?= e($r['check_out'] ?: '—') ?>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 hidden lg:table-cell"><?= e($r['work_hours']) ?>h</td>
                    <td class="px-5 py-3.5 text-slate-600 hidden lg:table-cell"><?= e($r['overtime_hours']) ?>h</td>
                    <td class="px-5 py-3.5">
                        <span class="badge badge-<?= e(strtolower(str_replace('-', '', $r['status']))) ?>"><?= e($r['status']) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>