@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="glass-card p-6 rounded-3xl border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-l-4 border-l-purple-500">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-300 border border-purple-500/30 flex items-center justify-center text-xl font-bold">
                👑
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">System Admin Control Center</h1>
                <p class="text-xs text-slate-400">Global overview across all colleges, courses, seat allocations, and student applications.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.colleges') }}" class="px-3.5 py-2 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all">
                + Register New College
            </a>
            <a href="{{ route('admin.applications') }}" class="px-3.5 py-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-purple-500/20 transition-all">
                📝 Applications Queue
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Colleges -->
        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Colleges Registered</span>
                <span class="p-2 bg-indigo-500/10 text-indigo-400 rounded-xl">🏛️</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ $totalColleges }}</div>
            <p class="text-xs text-emerald-400 flex items-center gap-1">
                ● {{ $activeColleges }} Active Colleges
            </p>
        </div>

        <!-- Seats -->
        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Courses & Seats</span>
                <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl">📚</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ $availableSeats }} / {{ $totalSeats }}</div>
            <p class="text-xs text-slate-400">
                {{ $filledSeats }} Seats Filled across {{ $totalCourses }} Courses
            </p>
        </div>

        <!-- Applications -->
        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Applications Submitted</span>
                <span class="p-2 bg-amber-500/10 text-amber-400 rounded-xl">📝</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ $totalApplications }}</div>
            <p class="text-xs text-amber-300">
                {{ $submittedApps + $underReviewApps }} Pending Review
            </p>
        </div>

        <!-- Admissions -->
        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Admissions Granted</span>
                <span class="p-2 bg-purple-500/10 text-purple-400 rounded-xl">✅</span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-400">{{ $approvedApps }}</div>
            <p class="text-xs text-slate-400">
                {{ $approvedApps }} Approved / {{ $rejectedApps }} Rejected
            </p>
        </div>
    </div>

    <!-- Quick Workflow Map & Live Audit Stream -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
            <h3 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                ⚡ Admin System Features & Quick Navigation
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <a href="{{ route('admin.colleges') }}" class="p-4 bg-slate-900/90 rounded-2xl border border-slate-800/80 hover:border-brand-500/40 transition-all block space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-white text-sm">🏛️ College & Course Management</span>
                        <span class="text-brand-400 font-bold">Manage →</span>
                    </div>
                    <p class="text-slate-400">Create new colleges with logo upload, update details, activate/deactivate institutes, and add courses with total seat capacities.</p>
                </a>

                <a href="{{ route('admin.applications') }}" class="p-4 bg-slate-900/90 rounded-2xl border border-slate-800/80 hover:border-brand-500/40 transition-all block space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-white text-sm">📝 Applications & AI Review</span>
                        <span class="text-brand-400 font-bold">Review Queue →</span>
                    </div>
                    <p class="text-slate-400">Search student applications, filter by status or college, generate AI Candidate Summaries, and approve/reject with atomic seat deduction.</p>
                </a>

                <a href="{{ route('admin.students') }}" class="p-4 bg-slate-900/90 rounded-2xl border border-slate-800/80 hover:border-brand-500/40 transition-all block space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-white text-sm">🎓 Registered Students Directory</span>
                        <span class="text-brand-400 font-bold">View Students →</span>
                    </div>
                    <p class="text-slate-400">Inspect registered student profiles, registration numbers, contact information, uploaded profile photos, and application histories.</p>
                </a>

                <a href="{{ route('admin.audit-logs') }}" class="p-4 bg-slate-900/90 rounded-2xl border border-slate-800/80 hover:border-brand-500/40 transition-all block space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-white text-sm">📋 Compliance Audit Trail</span>
                        <span class="text-brand-400 font-bold">View Logs →</span>
                    </div>
                    <p class="text-slate-400">Complete immutable record of all user logins, application status modifications, seat updates, and security events.</p>
                </a>
            </div>
        </div>

        <!-- Recent Audit Stream -->
        <div class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white">Live Activity Stream</h3>
                <a href="{{ route('admin.audit-logs') }}" class="text-xs text-brand-400 hover:underline">View All</a>
            </div>

            <div class="space-y-3 overflow-y-auto max-h-80 custom-scrollbar pr-1">
                @forelse($recentAuditLogs as $log)
                    <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800/80 text-xs space-y-1">
                        <div class="flex items-center justify-between text-[10px] text-slate-400">
                            <span class="font-bold text-brand-300 font-mono">{{ $log->action }}</span>
                            <span>{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-white font-semibold">{{ $log->user ? $log->user->name : 'System User' }}</p>
                        <p class="text-[10px] text-slate-500 font-mono">Entity: {{ $log->entity }} #{{ $log->entity_id }}</p>
                    </div>
                @empty
                    <div class="text-xs text-slate-500 text-center py-6">No audit records logged yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
