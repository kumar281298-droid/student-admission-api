@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="max-w-md mx-auto my-6">
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6 border border-slate-800 shadow-2xl">
        <div class="text-center space-y-1">
            <h2 class="text-xl font-bold text-white tracking-tight">Portal Authentication</h2>
            <p class="text-xs text-slate-400">Sign in to access your role-specific dashboard</p>
        </div>

        <!-- Quick Demo Login Buttons -->
        <div class="p-3 bg-slate-900/90 rounded-2xl border border-slate-800 space-y-2">
            <span class="text-[11px] font-semibold text-slate-400 block text-center">⚡ One-Click Evaluator Login:</span>
            <div class="grid grid-cols-3 gap-1.5">
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="admin@system.com">
                    <input type="hidden" name="password" value="password">
                    <button type="submit" class="w-full py-2 text-[11px] font-bold bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 border border-purple-500/30 rounded-xl transition-all">
                        👑 Admin
                    </button>
                </form>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="collegeadmin@delhiuniv.ac.in">
                    <input type="hidden" name="password" value="password">
                    <button type="submit" class="w-full py-2 text-[11px] font-bold bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-xl transition-all">
                        🏛️ College
                    </button>
                </form>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="student1@gmail.com">
                    <input type="hidden" name="password" value="password">
                    <button type="submit" class="w-full py-2 text-[11px] font-bold bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-xl transition-all">
                        🎓 Student
                    </button>
                </form>
            </div>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-medium text-slate-300 mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@system.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand-500">
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-slate-300 mb-1">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand-500">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-950 border-slate-800 text-brand-600">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all">
                Sign In to Dashboard
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-800">
            <p class="text-xs text-slate-400">New student applicant? <a href="{{ route('register') }}" class="text-brand-400 hover:underline font-semibold">Create Student Account</a></p>
        </div>
    </div>
</div>
@endsection
