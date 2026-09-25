@extends('layouts.app')

@section('title', 'Registered Students')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Registered Student Profiles</h1>
            <p class="text-xs text-slate-400">View registered student accounts, contact details, profile photos, and submitted applications.</p>
        </div>
        <span class="px-3 py-1 bg-brand-500/10 text-brand-300 border border-brand-500/20 rounded-full text-xs font-bold">
            Total Students: {{ count($students) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <div class="glass-card p-5 rounded-3xl space-y-4 border border-slate-800">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-slate-900 border-2 border-brand-500/30 flex items-center justify-center overflow-hidden shrink-0 text-2xl font-bold">
                        @if($student->profile_photo)
                            <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            🎓
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] font-mono font-bold text-brand-400 block">{{ $student->registration_no }}</span>
                        <h3 class="text-sm font-bold text-white">{{ $student->name }}</h3>
                        <p class="text-[11px] text-slate-400">{{ $student->email }}</p>
                    </div>
                </div>

                <div class="p-3 bg-slate-900/90 rounded-2xl border border-slate-800/80 text-xs space-y-1">
                    <p class="text-slate-300"><strong class="text-slate-400">Mobile:</strong> {{ $student->mobile ?? 'N/A' }}</p>
                    <p class="text-slate-300"><strong class="text-slate-400">DOB:</strong> {{ $student->dob ?? 'N/A' }} • <strong class="text-slate-400">Gender:</strong> {{ $student->gender ?? 'N/A' }}</p>
                    <p class="text-slate-300"><strong class="text-slate-400">Address:</strong> {{ $student->address ?? 'N/A' }}</p>
                </div>

                <div class="pt-2 border-t border-slate-800/80 space-y-1 text-xs">
                    <span class="font-bold text-slate-400 text-[10px] uppercase">Submitted Applications ({{ count($student->applications) }})</span>
                    @forelse($student->applications as $app)
                        <div class="flex items-center justify-between text-[11px] py-1 border-b border-slate-900 last:border-0">
                            <span class="text-white font-semibold">{{ $app->college->name ?? '-' }} ({{ $app->course->name ?? '-' }})</span>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-full border {{ $app->status === 'APPROVED' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : ($app->status === 'REJECTED' ? 'bg-rose-500/10 text-rose-400 border-rose-500/20' : 'bg-brand-500/10 text-brand-300 border-brand-500/20') }}">
                                {{ $app->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-[10px] text-slate-500 italic">No applications submitted yet.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 text-xs">No student accounts registered yet.</div>
        @endforelse
    </div>
</div>
@endsection
