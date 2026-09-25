@extends('layouts.app')

@section('title', 'Student Registration')

@section('content')
<div class="max-w-lg mx-auto my-4">
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6 border border-slate-800 shadow-2xl">
        <div class="text-center space-y-1">
            <h2 class="text-xl font-bold text-white tracking-tight">Student Registration</h2>
            <p class="text-xs text-slate-400">Register to submit applications to participating colleges</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label for="name" class="block font-medium text-slate-300 mb-1">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Amit Sharma" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand-500">
            </div>

            <div>
                <label for="email" class="block font-medium text-slate-300 mb-1">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="student@example.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block font-medium text-slate-300 mb-1">Password * (Min 6 chars)</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label for="password_confirmation" class="block font-medium text-slate-300 mb-1">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label for="mobile" class="block font-medium text-slate-300 mb-1">Mobile Number</label>
                    <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="9876543210" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label for="dob" class="block font-medium text-slate-300 mb-1">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="{{ old('dob') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label for="gender" class="block font-medium text-slate-300 mb-1">Gender</label>
                    <select id="gender" name="gender" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-brand-500">
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="address" class="block font-medium text-slate-300 mb-1">Address</label>
                <textarea id="address" name="address" rows="2" placeholder="Connaught Place, New Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-brand-500">{{ old('address') }}</textarea>
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                Complete Student Registration
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-800">
            <p class="text-xs text-slate-400">Already registered? <a href="{{ route('login') }}" class="text-brand-400 hover:underline font-semibold">Sign In Here</a></p>
        </div>
    </div>
</div>
@endsection
