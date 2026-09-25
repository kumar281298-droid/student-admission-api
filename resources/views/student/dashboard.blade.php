@extends('layouts.app')

@section('title', 'Student Portal')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="glass-card p-6 rounded-3xl border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-l-4 border-l-emerald-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-slate-900 border-2 border-emerald-500/30 flex items-center justify-center overflow-hidden shrink-0 text-2xl font-bold">
                @if($student && $student->profile_photo)
                    <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    🎓
                @endif
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold text-emerald-400 block">{{ $student->registration_no ?? 'Unregistered' }}</span>
                <h1 class="text-xl font-bold text-white tracking-tight">Welcome, {{ $user->name }}</h1>
                <p class="text-xs text-slate-400">Track your submitted college applications and status progression.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('student.apply') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-1.5">
                📝 Apply for Admission
            </a>
            <a href="{{ route('student.profile') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs rounded-xl transition-all">
                👤 Edit Profile & Photo
            </a>
        </div>
    </div>

    <!-- Application Status Workflow Guide -->
    <div class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3">Admission Application Status Tracker</h3>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs">
            <div class="p-3 bg-slate-900/90 rounded-2xl border border-slate-800">
                <span class="text-lg">📄</span>
                <div class="font-bold text-white mt-1">DRAFT</div>
                <p class="text-[10px] text-slate-400">Application initialized</p>
            </div>
            <div class="p-3 bg-slate-900/90 rounded-2xl border border-brand-500/30">
                <span class="text-lg">📩</span>
                <div class="font-bold text-brand-300 mt-1">SUBMITTED</div>
                <p class="text-[10px] text-slate-400">Sent to college</p>
            </div>
            <div class="p-3 bg-slate-900/90 rounded-2xl border border-amber-500/30">
                <span class="text-lg">⏳</span>
                <div class="font-bold text-amber-300 mt-1">UNDER_REVIEW</div>
                <p class="text-[10px] text-slate-400">Under evaluation</p>
            </div>
            <div class="p-3 bg-slate-900/90 rounded-2xl border border-emerald-500/30">
                <span class="text-lg">🎉</span>
                <div class="font-bold text-emerald-300 mt-1">APPROVED / REJECTED</div>
                <p class="text-[10px] text-slate-400">Final decision</p>
            </div>
        </div>
    </div>

    <!-- My Applications Table -->
    <div class="glass-card rounded-3xl overflow-hidden border border-slate-800">
        <div class="p-4 bg-slate-900/80 border-b border-slate-800 font-bold text-xs text-white">
            My Submitted Applications History ({{ count($applications) }})
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800 uppercase">
                    <tr>
                        <th class="py-3.5 px-4">Application #</th>
                        <th class="py-3.5 px-4">College</th>
                        <th class="py-3.5 px-4">Course Applied</th>
                        <th class="py-3.5 px-4">Current Status</th>
                        <th class="py-3.5 px-4">Submitted Date</th>
                        <th class="py-3.5 px-4 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($applications as $app)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-white">{{ $app->application_no }}</td>
                            <td class="py-3.5 px-4 font-semibold text-white">{{ $app->college->name ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-brand-400 font-semibold">{{ $app->course->name ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 text-[10px] font-bold border rounded-full {{ $app->status === 'APPROVED' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30' : ($app->status === 'REJECTED' ? 'bg-rose-500/10 text-rose-300 border-rose-500/30' : 'bg-brand-500/10 text-brand-300 border-brand-500/30') }}">
                                    {{ $app->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">{{ $app->submitted_at ? $app->submitted_at->format('M d, Y H:i') : '-' }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <button onclick="document.getElementById('modal-student-app-{{ $app->id }}').classList.remove('hidden')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors">
                                    View Status
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500 text-xs">You have not submitted any admission applications yet. <a href="{{ route('student.apply') }}" class="text-brand-400 font-bold hover:underline">Apply Now →</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals Container -->
@foreach($applications as $app)
    <div id="modal-student-app-{{ $app->id }}" onclick="if(event.target === this) this.classList.add('hidden')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
        <div class="glass-modal w-full max-w-lg rounded-3xl border border-slate-800 relative text-left shadow-2xl overflow-hidden my-auto max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="p-5 border-b border-slate-800 flex items-center justify-between shrink-0 bg-slate-900/90">
                <div>
                    <span class="text-xs font-mono font-bold text-emerald-400 uppercase tracking-widest">{{ $app->application_no }}</span>
                    <h3 class="text-base font-bold text-white">{{ $app->college->name ?? '-' }}</h3>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 font-bold text-xs rounded-full border {{ $app->status === 'APPROVED' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30' : ($app->status === 'REJECTED' ? 'bg-rose-500/10 text-rose-300 border-rose-500/30' : 'bg-brand-500/10 text-brand-300 border-brand-500/30') }}">
                        {{ $app->status }}
                    </span>
                    <button onclick="document.getElementById('modal-student-app-{{ $app->id }}').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center text-sm transition-colors">✕</button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <div class="space-y-2 text-xs bg-slate-900/90 p-4 rounded-xl border border-slate-800">
                    <p class="text-white"><strong class="text-slate-400">Course Applied:</strong> {{ $app->course->name ?? '-' }} ({{ $app->course->duration ?? '-' }})</p>
                    <p class="text-white"><strong class="text-slate-400">Submitted On:</strong> {{ $app->submitted_at ? $app->submitted_at->format('M d, Y H:i:s') : '-' }}</p>
                    <p class="text-white"><strong class="text-slate-400">Statement/Remarks:</strong> {{ $app->remarks ?? 'None' }}</p>
                </div>

                @if($app->status === 'REJECTED')
                    <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-xs text-rose-300 space-y-1">
                        <strong class="text-rose-400 block font-bold">REJECTION REASON:</strong>
                        <p>{{ $app->remarks }}</p>
                    </div>
                @elseif($app->status === 'APPROVED')
                    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-xs text-emerald-300 space-y-1">
                        <strong class="text-emerald-400 block font-bold">🎉 CONGRATULATIONS!</strong>
                        <p>Your application has been approved and a seat has been reserved for you.</p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/90 flex items-center justify-end shrink-0">
                <button onclick="document.getElementById('modal-student-app-{{ $app->id }}').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-xl transition-colors">
                    Close Window
                </button>
            </div>
        </div>
    </div>
@endforeach
@endsection
