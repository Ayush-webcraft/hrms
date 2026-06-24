<?php /** @var array $salary @var array $employee @var string $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip — <?= e($employee['name']) ?> — <?= e($salary['month']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body class="bg-slate-100 text-slate-900 antialiased">

<!-- Actions (no-print) -->
<div class="no-print flex items-center justify-center gap-3 p-4">
    <button onclick="window.print()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print / Save as PDF
    </button>
    <a href="<?= e(url('/dashboard')) ?>"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
        ← Back
    </a>
</div>

<!-- Payslip document -->
<div class="max-w-2xl mx-auto mb-8 bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden payslip-doc">

    <!-- Header -->
    <div class="bg-slate-900 px-8 py-6 flex items-start justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg">HRMS Pvt. Ltd.</span>
            </div>
            <div class="text-slate-400 text-sm">HR Attendance & Payroll Management</div>
        </div>
        <div class="text-right">
            <div class="text-white font-bold text-xl">Payslip</div>
            <div class="text-slate-300 text-sm mt-1"><?= e(date('F Y', strtotime($salary['month'] . '-01'))) ?></div>
        </div>
    </div>

    <!-- Employee info -->
    <div class="px-8 py-5 bg-slate-50 border-b border-slate-200">
        <div class="grid grid-cols-2 gap-x-8 gap-y-3">
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Employee Name</div>
                <div class="font-semibold text-slate-900"><?= e($employee['name']) ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Employee Code</div>
                <div class="font-mono font-semibold text-slate-900"><?= e($employee['employee_code']) ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Department</div>
                <div class="font-semibold text-slate-900"><?= e($employee['department']) ?></div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Designation</div>
                <div class="font-semibold text-slate-900"><?= e($employee['designation']) ?></div>
            </div>
        </div>
    </div>

    <div class="px-8 py-6">

        <!-- Attendance summary -->
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Attendance Summary</h3>
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="text-center p-3 bg-slate-50 rounded-xl">
                <div class="text-lg font-bold text-slate-900"><?= e($salary['working_days']) ?></div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Working Days</div>
            </div>
            <div class="text-center p-3 bg-emerald-50 rounded-xl">
                <div class="text-lg font-bold text-emerald-700"><?= e($salary['present_days']) ?></div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Present</div>
            </div>
            <div class="text-center p-3 bg-blue-50 rounded-xl">
                <div class="text-lg font-bold text-blue-700"><?= e($salary['leave_days']) ?></div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Paid Leave</div>
            </div>
            <div class="text-center p-3 bg-amber-50 rounded-xl">
                <div class="text-lg font-bold text-amber-700"><?= e($salary['half_days']) ?></div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Half-Days</div>
            </div>
            <div class="text-center p-3 bg-red-50 rounded-xl">
                <div class="text-lg font-bold text-red-600"><?= e($salary['absent_days']) ?></div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Absent</div>
            </div>
            <div class="text-center p-3 bg-slate-50 rounded-xl">
                <div class="text-lg font-bold text-slate-700"><?= e(money($salary['per_day_salary'])) ?></div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">Per Day</div>
            </div>
        </div>

        <!-- Earnings & Deductions -->
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Earnings & Deductions</h3>
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100">
                <span class="text-sm text-slate-600">Basic Salary</span>
                <span class="font-semibold text-slate-900"><?= e(money($salary['basic_salary'])) ?></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100">
                <span class="text-sm text-slate-600">Overtime (<?= e($salary['overtime_hours']) ?> hrs)</span>
                <span class="font-semibold text-emerald-600">+ <?= e(money($salary['overtime_amount'])) ?></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-200">
                <span class="text-sm text-slate-600">Deductions (absence)</span>
                <span class="font-semibold text-red-500">− <?= e(money($salary['deductions'])) ?></span>
            </div>
            <div class="flex items-center justify-between px-5 py-4 bg-slate-900">
                <span class="text-sm font-bold text-white">Net Salary</span>
                <span class="text-xl font-bold text-white"><?= e(money($salary['net_salary'])) ?></span>
            </div>
        </div>

        <p class="mt-5 text-xs text-slate-400 text-center">
            Generated on <?= e(date('d M Y', strtotime($salary['generated_at'] ?? 'now'))) ?>.
            This is a computer-generated payslip and requires no signature.
        </p>
    </div>
</div>

</body>
</html>