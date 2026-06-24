<div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8">
    <div class="flex flex-col items-center mb-8">
        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-indigo-300/40">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Welcome back</h1>
        <p class="text-slate-500 text-sm mt-1">Sign in to HR Management System</p>
    </div>

    <form method="post" action="<?= e(url('/login')) ?>" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
            <input type="email" name="email" value="<?= e(old('email')) ?>" autofocus required
                   placeholder="you@company.com"
                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white
                          focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500
                          placeholder:text-slate-400 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
            <input type="password" name="password" required
                   placeholder="••••••••"
                   class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm bg-white
                          focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500
                          placeholder:text-slate-400 transition-colors">
        </div>
        <button type="submit"
                class="w-full mt-2 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                       text-white font-semibold text-sm rounded-xl transition-colors
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-sm">
            Sign in to HRMS
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-100">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3 text-center">Demo accounts</p>
        <div class="space-y-2 text-xs">
            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-3.5 py-2.5">
                <span class="font-semibold text-slate-700">HR Admin</span>
                <span class="font-mono text-slate-500">admin@hrms.test / password</span>
            </div>
            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-3.5 py-2.5">
                <span class="font-semibold text-slate-700">Employee</span>
                <span class="font-mono text-slate-500">aarav@hrms.test / password</span>
            </div>
        </div>
    </div>
</div>
