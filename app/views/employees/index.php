<?php /** @var array $employees @var string $search */ ?>
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Employees</h1>
        <p class="text-sm text-slate-500 mt-0.5"><?= e(count($employees)) ?> total employees</p>
    </div>
    <a href="<?= e(url('/employees/create')) ?>"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Employee
    </a>
</div>

<!-- Search -->
<form method="get" action="<?= e(url('/employees')) ?>" class="mb-5">
    <div class="flex gap-2 max-w-lg">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="<?= e($search) ?>"
                   placeholder="Search name, code, email, department…"
                   class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 placeholder:text-slate-400 transition-colors">
        </div>
        <button type="submit"
                class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
            Search
        </button>
        <?php if ($search !== ''): ?>
        <a href="<?= e(url('/employees')) ?>"
           class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-500 text-sm font-semibold rounded-xl transition-colors">
            Clear
        </a>
        <?php endif; ?>
    </div>
</form>

<!-- Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <?php if (!$employees): ?>
        <div class="py-16 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="text-sm text-slate-400 font-medium">No employees found.</p>
        </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/60">
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Employee</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden md:table-cell">Department</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Designation</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden lg:table-cell">Basic Salary</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <?php foreach ($employees as $emp): ?>
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0 font-bold text-indigo-700 text-sm">
                                <?= strtoupper(substr($emp['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <a href="<?= e(url('/employees/' . $emp['id'])) ?>"
                                   class="font-semibold text-slate-900 hover:text-indigo-600 transition-colors">
                                    <?= e($emp['name']) ?>
                                </a>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    <span class="font-mono"><?= e($emp['employee_code']) ?></span>
                                    <span class="mx-1">·</span>
                                    <?= e($emp['email']) ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-slate-600 hidden md:table-cell"><?= e($emp['department']) ?></td>
                    <td class="px-5 py-4 text-slate-600 hidden lg:table-cell"><?= e($emp['designation']) ?></td>
                    <td class="px-5 py-4 font-semibold text-slate-800 hidden lg:table-cell"><?= e(money($emp['basic_salary'])) ?></td>
                    <td class="px-5 py-4">
                        <span class="badge badge-<?= $emp['status'] === 'active' ? 'approved' : 'rejected' ?>"><?= e($emp['status']) ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="<?= e(url('/employees/' . $emp['id'] . '/edit')) ?>"
                               class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                Edit
                            </a>
                            <form method="post" action="<?= e(url('/employees/' . $emp['id'] . '/delete')) ?>"
                                  onsubmit="return confirm('Delete <?= e($emp['name']) ?>? This removes their attendance, leaves and payslips too.')">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>