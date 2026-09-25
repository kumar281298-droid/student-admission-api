@extends('layouts.app')

@section('title', 'System Audit Trail')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">System Audit & Compliance Log</h1>
            <p class="text-xs text-slate-400">Complete audit trail of user logins, application status modifications, and seat updates.</p>
        </div>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden border border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Timestamp</th>
                        <th class="py-3.5 px-4">User</th>
                        <th class="py-3.5 px-4">Action</th>
                        <th class="py-3.5 px-4">Entity</th>
                        <th class="py-3.5 px-4">Entity ID</th>
                        <th class="py-3.5 px-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($auditLogs as $log)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3 px-4 text-slate-400 text-[11px] font-mono">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="py-3 px-4 font-semibold text-white">{{ $log->user->name ?? 'System' }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-brand-300 text-[11px]">{{ $log->action }}</td>
                            <td class="py-3 px-4 text-slate-300">{{ $log->entity ?? '-' }}</td>
                            <td class="py-3 px-4 font-mono text-slate-400">{{ $log->entity_id ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500 text-xs">No audit logs recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-900/60 border-t border-slate-800">
            {{ $auditLogs->links() }}
        </div>
    </div>
</div>
@endsection
