<div class="flex items-center gap-3 mb-6">
    <a href="<?= e(url('/leaves')) ?>"
       class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Apply for Leave</h1>
        <p class="text-sm text-slate-500 mt-0.5">Submit a leave request for approval</p>
    </div>
</div>

<div class="max-w-xl">
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <form method="post" action="<?= e(url('/leaves')) ?>">
        <?= csrf_field() ?>
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Leave Type</label>
                <select name="leave_type"
                        class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-white transition-colors">
                    <?php foreach (['Casual', 'Sick', 'Earned', 'Unpaid'] as $type): ?>
                        <option value="<?= e($type) ?>" <?= old('leave_type') === $type ? 'selected' : '' ?>><?= e($type) ?> Leave</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="<?= e(old('start_date', date('Y-m-d'))) ?>" required
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" value="<?= e(old('end_date', date('Y-m-d'))) ?>" required
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Reason <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea name="reason" rows="3" placeholder="Briefly describe the reason for your leave…"
                          class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 placeholder:text-slate-400 resize-none transition-colors"><?= e(old('reason')) ?></textarea>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    Submit Request
                </button>
                <a href="<?= e(url('/leaves')) ?>"
                   class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-xl transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
</div>