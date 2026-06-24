<?php /** @var array $employee */ ?>
<div class="flex items-center gap-3 mb-6">
    <a href="<?= e(url('/employees/' . $employee['id'])) ?>"
       class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Edit Employee</h1>
        <p class="text-sm text-slate-500 mt-0.5"><?= e($employee['name']) ?> · <span class="font-mono"><?= e($employee['employee_code']) ?></span></p>
    </div>
</div>

<div class="max-w-3xl">
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <form method="post" action="<?= e(url('/employees/' . $employee['id'])) ?>">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Employee Code</label>
                <input type="text" value="<?= e($employee['employee_code']) ?>" readonly
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-400 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?= e(old('name', $employee['name'])) ?>" required
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="<?= e(old('email', $employee['email'])) ?>" required
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Phone</label>
                <input type="text" name="phone" value="<?= e(old('phone', $employee['phone'])) ?>"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Department</label>
                <input type="text" name="department" value="<?= e(old('department', $employee['department'])) ?>"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Designation</label>
                <input type="text" name="designation" value="<?= e(old('designation', $employee['designation'])) ?>"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Joining Date</label>
                <input type="date" name="joining_date" value="<?= e(old('joining_date', $employee['joining_date'])) ?>"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Basic Salary (monthly)</label>
                <input type="number" step="0.01" min="0" name="basic_salary"
                       value="<?= e(old('basic_salary', $employee['basic_salary'])) ?>"
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
            </div>

            <div class="sm:col-span-2">
                <?php $st = old('status', $employee['status']); ?>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                <select name="status"
                        class="w-full sm:w-48 px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors bg-white">
                    <option value="active" <?= $st === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $st === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div class="mt-6 pt-5 border-t border-slate-100 flex items-center gap-3">
            <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                Update Employee
            </button>
            <a href="<?= e(url('/employees/' . $employee['id'])) ?>"
               class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-xl transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
</div>