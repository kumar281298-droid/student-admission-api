@extends('layouts.app')

@section('title', 'College Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="glass-card p-6 rounded-3xl border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-l-4 border-l-amber-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center overflow-hidden shrink-0 text-xl font-bold text-amber-400">
                @if($college->logo)
                    <img src="{{ asset('storage/' . $college->logo) }}" class="w-full h-full object-cover">
                @else
                    🏛️
                @endif
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold text-amber-400 block">{{ $college->code }} • {{ $college->city }}, {{ $college->state }}</span>
                <h1 class="text-xl font-bold text-white tracking-tight">{{ $college->name }}</h1>
                <p class="text-xs text-slate-400">College Admission Portal (Scoped to {{ $college->name }} only)</p>
            </div>
        </div>
        <a href="{{ route('college_admin.applications') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all">
            📝 Review Applications ({{ $submittedApps + $underReviewApps }})
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Courses Offered</span>
                <span class="p-2 bg-amber-500/10 text-amber-400 rounded-xl">📚</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ $totalCourses }}</div>
            <p class="text-xs text-slate-400">Active Programs</p>
        </div>

        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Course Seats</span>
                <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl">🪑</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ $availableSeats }} / {{ $totalSeats }}</div>
            <p class="text-xs text-emerald-400">{{ $filledSeats }} Seats Allocated</p>
        </div>

        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Applications</span>
                <span class="p-2 bg-indigo-500/10 text-indigo-400 rounded-xl">📝</span>
            </div>
            <div class="text-3xl font-extrabold text-white">{{ $totalApps }}</div>
            <p class="text-xs text-amber-300">{{ $submittedApps }} Pending Action</p>
        </div>

        <div class="glass-card p-5 rounded-2xl space-y-2 border border-slate-800">
            <div class="flex justify-between items-center text-slate-400">
                <span class="text-[11px] font-bold uppercase tracking-wider">Approved Students</span>
                <span class="p-2 bg-purple-500/10 text-purple-400 rounded-xl">✅</span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-400">{{ $approvedApps }}</div>
            <p class="text-xs text-slate-400">{{ $approvedApps }} Approved / {{ $rejectedApps }} Rejected</p>
        </div>
    </div>

    <!-- Recent Applications Queue -->
    <div class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Recent Submitted Applications</h3>
            <a href="{{ route('college_admin.applications') }}" class="text-xs text-brand-400 hover:underline">View All Applications →</a>
        </div>

        <div class="space-y-3">
            @forelse($recentApplications as $app)
                <div class="p-4 bg-slate-900/90 rounded-2xl border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-brand-400">{{ $app->application_no }}</span>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-full border {{ $app->status === 'APPROVED' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30' : ($app->status === 'REJECTED' ? 'bg-rose-500/10 text-rose-300 border-rose-500/30' : 'bg-brand-500/10 text-brand-300 border-brand-500/30') }}">
                                {{ $app->status }}
                            </span>
                        </div>
                        <h4 class="font-bold text-white text-sm mt-1">{{ $app->student->name ?? 'Student' }}</h4>
                        <p class="text-slate-400">Course Applied: <strong class="text-brand-300">{{ $app->course->name ?? '-' }}</strong></p>
                    </div>

                    <a href="{{ route('college_admin.applications') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs rounded-xl text-center shadow">
                        Review Application
                    </a>
                </div>
            @empty
                <div class="py-8 text-center text-slate-500 text-xs">No applications submitted to this college yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
