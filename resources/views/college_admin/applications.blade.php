@extends('layouts.app')

@section('title', 'College Applications')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Applications for {{ $college->name }}</h1>
            <p class="text-xs text-slate-400">Review student details, AI candidate summaries, and make admission decisions.</p>
        </div>
    </div>

    <!-- Search & Filters -->
    <form action="{{ route('college_admin.applications') }}" method="GET" class="glass-card p-4 rounded-2xl flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search App # or Student..." class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-xs text-white">
        
        <select name="status" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200">
            <option value="">All Statuses</option>
            <option value="SUBMITTED" {{ request('status') === 'SUBMITTED' ? 'selected' : '' }}>SUBMITTED</option>
            <option value="UNDER_REVIEW" {{ request('status') === 'UNDER_REVIEW' ? 'selected' : '' }}>UNDER_REVIEW</option>
            <option value="APPROVED" {{ request('status') === 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
            <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
        </select>

        <button type="submit" class="px-5 py-2 bg-brand-600 text-white font-semibold text-xs rounded-xl">Filter</button>
        <a href="{{ route('college_admin.applications') }}" class="px-3 py-2 bg-slate-800 text-slate-300 text-xs rounded-xl">Reset</a>
    </form>

    <!-- Table -->
    <div class="glass-card rounded-3xl overflow-hidden border border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800 uppercase">
                    <tr>
                        <th class="py-3.5 px-4">App #</th>
                        <th class="py-3.5 px-4">Student</th>
                        <th class="py-3.5 px-4">Course Applied</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Submitted At</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($applications as $app)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-white">{{ $app->application_no }}</td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-white">{{ $app->student->name ?? 'Student' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $app->student->email ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-white font-semibold">{{ $app->course->name ?? '-' }}</div>
                                <div class="text-[11px] text-brand-400">{{ $app->course->available_seats ?? 0 }} / {{ $app->course->total_seats ?? 0 }} seats left</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 text-[10px] font-bold border rounded-full {{ $app->status === 'APPROVED' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30' : ($app->status === 'REJECTED' ? 'bg-rose-500/10 text-rose-300 border-rose-500/30' : 'bg-brand-500/10 text-brand-300 border-brand-500/30') }}">
                                    {{ $app->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">{{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <button onclick="document.getElementById('modal-app-{{ $app->id }}').classList.remove('hidden')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors">
                                    Review & Decision
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500 text-xs">No applications submitted to your college yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-900/60 border-t border-slate-800">
            {{ $applications->links() }}
        </div>
    </div>
</div>

<!-- Review Modals -->
@foreach($applications as $app)
    <div id="modal-app-{{ $app->id }}" onclick="if(event.target === this) this.classList.add('hidden')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
        <div class="glass-modal w-full max-w-2xl rounded-3xl border border-slate-800 relative text-left max-h-[90vh] flex flex-col shadow-2xl overflow-hidden my-auto">
            <!-- Modal Header -->
            <div class="p-5 border-b border-slate-800 flex items-center justify-between shrink-0 bg-slate-900/90">
                <div>
                    <span class="text-xs font-mono font-bold text-amber-400 uppercase tracking-widest">{{ $app->application_no }}</span>
                    <h3 class="text-base font-bold text-white">{{ $app->student->name ?? 'Student' }}</h3>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 font-bold text-xs rounded-full border {{ $app->status === 'APPROVED' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30' : ($app->status === 'REJECTED' ? 'bg-rose-500/10 text-rose-300 border-rose-500/30' : 'bg-brand-500/10 text-brand-300 border-brand-500/30') }}">
                        {{ $app->status }}
                    </span>
                    <button onclick="document.getElementById('modal-app-{{ $app->id }}').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center text-sm transition-colors">✕</button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800 space-y-1">
                        <h4 class="font-bold text-slate-400 uppercase text-[10px]">Student Profile</h4>
                        <p class="text-white"><strong class="text-slate-400">Email:</strong> {{ $app->student->email ?? '-' }}</p>
                        <p class="text-white"><strong class="text-slate-400">Mobile:</strong> {{ $app->student->mobile ?? 'N/A' }}</p>
                        <p class="text-white"><strong class="text-slate-400">Gender:</strong> {{ $app->student->gender ?? 'N/A' }}</p>
                    </div>

                    <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800 space-y-1">
                        <h4 class="font-bold text-slate-400 uppercase text-[10px]">Course Applied</h4>
                        <p class="text-white"><strong class="text-slate-400">Course:</strong> {{ $app->course->name ?? '-' }}</p>
                        <p class="text-white"><strong class="text-slate-400">Duration:</strong> {{ $app->course->duration ?? '-' }}</p>
                        <p class="text-white"><strong class="text-slate-400">Available Seats:</strong> {{ $app->course->available_seats ?? 0 }} / {{ $app->course->total_seats ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-slate-900/90 p-3.5 rounded-xl border border-slate-800 text-xs">
                    <h4 class="font-bold text-slate-400 uppercase text-[10px] mb-1">Student Statement</h4>
                    <p class="text-slate-200 italic">{{ $app->remarks ?? 'No remarks provided.' }}</p>
                </div>

                <!-- Dynamic AI Summary Feature Callout -->
                <div class="p-4 bg-purple-950/60 border border-purple-500/30 rounded-2xl text-xs text-purple-200 space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span class="p-1 bg-purple-500/20 text-purple-300 rounded-md text-sm">🤖</span>
                            <span class="font-bold text-purple-300">AI Candidate Evaluation Assistant:</span>
                        </div>
                        <button type="button" onclick="fetchCollegeAdminAISummary({{ $app->id }}, 'ai-box-{{ $app->id }}')" class="px-3.5 py-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold text-[11px] rounded-xl shadow-lg shadow-purple-500/20 transition-all flex items-center gap-1.5">
                            ⚡ Generate Dynamic AI Summary
                        </button>
                    </div>
                    <div id="ai-box-{{ $app->id }}" class="text-slate-300 space-y-1">
                        <div class="p-3 bg-purple-900/30 rounded-xl border border-purple-500/20 flex items-center justify-between">
                            <span class="text-slate-300 italic">Student {{ $app->student->name ?? 'Applicant' }} profile for {{ $app->course->name ?? 'Course' }}. Click above to query LLM.</span>
                            <span class="px-2 py-0.5 text-[9px] font-bold bg-purple-500/20 text-purple-200 border border-purple-500/30 rounded-full shrink-0">RECOMMENDATION: REVIEW</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/90 flex items-center justify-between shrink-0">
                <button onclick="document.getElementById('modal-app-{{ $app->id }}').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-xl transition-colors">
                    Close Window
                </button>

                @if($app->status === 'SUBMITTED' || $app->status === 'UNDER_REVIEW')
                    <div class="flex items-center gap-2">
                        <form action="{{ route('college_admin.applications.reject', $app->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="reason" value="Application rejected by college admission board.">
                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs rounded-xl shadow-md transition-all">Reject Application</button>
                        </form>

                        <form action="{{ route('college_admin.applications.approve', $app->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="remarks" value="Approved by College Admission Board.">
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-md transition-all">Approve & Allocate Seat</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach

<script>
    async function fetchCollegeAdminAISummary(appId, containerId) {
        const box = document.getElementById(containerId);
        box.innerHTML = '<div class="flex items-center gap-2 p-3 bg-purple-900/40 rounded-xl border border-purple-500/30 text-purple-200 animate-pulse"><span class="text-base">⏳</span><span class="font-medium">Querying AI LLM Engine... Synthesizing student profile & seat metrics...</span></div>';
        
        try {
            const response = await fetch(`/college-admin/applications/${appId}/ai-summary`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            const json = await response.json();
            
            if (json.success && json.data && json.data.ai_analysis) {
                const ai = json.data.ai_analysis;
                box.innerHTML = `
                    <div class="p-3.5 bg-purple-900/50 rounded-xl border border-purple-500/40 space-y-2 text-xs shadow-inner">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 text-[9px] font-bold bg-purple-500/30 text-purple-200 border border-purple-500/40 rounded-full tracking-wider">RECOMMENDATION: ${ai.recommendation}</span>
                            <span class="text-[10px] text-purple-300 font-mono bg-purple-950/80 px-2 py-0.5 rounded border border-purple-500/30">Engine: ${ai.provider}</span>
                        </div>
                        <p class="text-slate-100 font-medium leading-relaxed">${ai.summary}</p>
                    </div>
                `;
            } else {
                box.innerHTML = `<div class="p-3 bg-rose-950/60 rounded-xl border border-rose-500/40 text-rose-200">⚠️ AI Evaluation: ${json.message || 'Unable to reach AI service'}</div>`;
            }
        } catch (err) {
            box.innerHTML = `<div class="p-3 bg-rose-950/60 rounded-xl border border-rose-500/40 text-rose-200">⚠️ Connection error: ${err.message}</div>`;
        }
    }
</script>
@endsection
