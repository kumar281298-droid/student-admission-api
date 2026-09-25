@extends('layouts.app')

@section('title', 'College & Course Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Colleges & Course Management</h1>
            <p class="text-xs text-slate-400">Add institutions, upload logos, toggle statuses, and set course seat limits.</p>
        </div>
        <button onclick="document.getElementById('modal-add-college').classList.remove('hidden')" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-1.5">
            + Register New College
        </button>
    </div>

    <!-- Colleges Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($colleges as $col)
            <div class="glass-card p-6 rounded-3xl space-y-4 border border-slate-800 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center overflow-hidden shrink-0 text-xl font-bold text-brand-400">
                                @if($col->logo)
                                    <img src="{{ asset('storage/' . $col->logo) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($col->code, 0, 2) }}
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white">{{ $col->name }}</h3>
                                <p class="text-[10px] font-mono text-slate-400">{{ $col->code }} • {{ $col->city }}, {{ $col->state }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $col->status === 'ACTIVE' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                            {{ $col->status }}
                        </span>
                    </div>

                    <div class="text-xs text-slate-400 space-y-1">
                        <p class="flex items-center gap-1.5">📍 {{ $col->address }}</p>
                        <p class="flex items-center gap-1.5">✉️ {{ $col->email }}</p>
                        <p class="flex items-center gap-1.5">📞 {{ $col->phone ?? 'N/A' }}</p>
                    </div>

                    <!-- Courses Listing -->
                    <div class="pt-3 border-t border-slate-800/80 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-white">{{ count($col->courses) }} Courses Offered</span>
                            <button onclick="document.getElementById('modal-add-course-{{ $col->id }}').classList.remove('hidden')" class="text-[11px] font-semibold text-brand-400 hover:underline">
                                + Add Course
                            </button>
                        </div>

                        <div class="space-y-1.5 max-h-40 overflow-y-auto custom-scrollbar">
                            @forelse($col->courses as $crs)
                                <div class="p-2.5 bg-slate-900/90 rounded-xl border border-slate-800/80 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="font-semibold text-slate-200">{{ $crs->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $crs->code }} • {{ $crs->duration }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-lg border {{ $crs->available_seats > 0 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                                        {{ $crs->available_seats }} / {{ $crs->total_seats }} Seats
                                    </span>
                                </div>
                            @empty
                                <p class="text-[11px] text-slate-500 italic py-2">No courses registered under this college.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-800 flex justify-end gap-2">
                    <button onclick="document.getElementById('modal-edit-college-{{ $col->id }}').classList.remove('hidden')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl">
                        Edit College
                    </button>
                </div>
            </div>

            <!-- MODAL: Edit College -->
            <div id="modal-edit-college-{{ $col->id }}" onclick="if(event.target === this) this.classList.add('hidden')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
                <div class="glass-modal w-full max-w-lg p-6 rounded-3xl space-y-4 border border-slate-800 relative text-left max-h-[90vh] overflow-y-auto custom-scrollbar my-auto">
                    <button onclick="document.getElementById('modal-edit-college-{{ $col->id }}').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm transition-colors">✕</button>
                    <h3 class="text-base font-bold text-white">Edit College Details</h3>

                    <form action="{{ route('admin.colleges.update', $col->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-400 mb-1">College Name *</label>
                                <input type="text" name="name" value="{{ $col->name }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">College Code *</label>
                                <input type="text" name="code" value="{{ $col->code }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-400 mb-1">City *</label>
                                <input type="text" name="city" value="{{ $col->city }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">State *</label>
                                <input type="text" name="state" value="{{ $col->state }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-400 mb-1">Email *</label>
                                <input type="email" name="email" value="{{ $col->email }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">Phone</label>
                                <input type="text" name="phone" value="{{ $col->phone }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-400 mb-1">Address *</label>
                            <input type="text" name="address" value="{{ $col->address }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-400 mb-1">Status *</label>
                                <select name="status" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                                    <option value="ACTIVE" {{ $col->status === 'ACTIVE' ? 'selected' : '' }}>ACTIVE</option>
                                    <option value="INACTIVE" {{ $col->status === 'INACTIVE' ? 'selected' : '' }}>INACTIVE</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">Update Logo Image</label>
                                <input type="file" name="logo" accept="image/jpeg,image/png" class="w-full text-slate-400">
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('modal-edit-college-{{ $col->id }}').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl">Cancel</button>
                            <button type="submit" class="px-5 py-2 bg-brand-600 text-white font-medium rounded-xl">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL: Add Course to College -->
            <div id="modal-add-course-{{ $col->id }}" onclick="if(event.target === this) this.classList.add('hidden')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
                <div class="glass-modal w-full max-w-md p-6 rounded-3xl space-y-4 border border-slate-800 relative text-left max-h-[90vh] overflow-y-auto custom-scrollbar my-auto">
                    <button onclick="document.getElementById('modal-add-course-{{ $col->id }}').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm transition-colors">✕</button>
                    <div>
                        <h3 class="text-base font-bold text-white">Add Course under {{ $col->name }}</h3>
                        <p class="text-xs text-slate-400">Set course name, code, duration, and seat capacity.</p>
                    </div>

                    <form action="{{ route('admin.courses.store', $col->id) }}" method="POST" class="space-y-3 text-xs">
                        @csrf
                        <div>
                            <label class="block text-slate-400 mb-1">Course Name *</label>
                            <input type="text" name="name" required placeholder="B.Tech Computer Science" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-slate-400 mb-1">Course Code *</label>
                                <input type="text" name="code" required placeholder="CS-101" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">Duration *</label>
                                <input type="text" name="duration" required placeholder="4 Years" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-400 mb-1">Total Available Seats *</label>
                            <input type="number" name="total_seats" min="1" required placeholder="10" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('modal-add-course-{{ $col->id }}').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl">Cancel</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 text-white font-medium rounded-xl">Add Course</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 text-xs">No colleges registered yet.</div>
        @endforelse
    </div>
</div>

<!-- MODAL: Add New College -->
<div id="modal-add-college" onclick="if(event.target === this) this.classList.add('hidden')" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md hidden flex items-start sm:items-center justify-center my-auto">
    <div class="glass-modal w-full max-w-lg p-6 rounded-3xl space-y-4 border border-slate-800 relative text-left max-h-[90vh] overflow-y-auto custom-scrollbar my-auto">
        <button onclick="document.getElementById('modal-add-college').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm transition-colors">✕</button>
        <div>
            <h3 class="text-base font-bold text-white">Register New College</h3>
            <p class="text-xs text-slate-400">Fill details and upload logo image.</p>
        </div>

        <form action="{{ route('admin.colleges.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 mb-1">College Name *</label>
                    <input type="text" name="name" required placeholder="IIT Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Code (Unique) *</label>
                    <input type="text" name="code" required placeholder="IITD-004" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 mb-1">City *</label>
                    <input type="text" name="city" required placeholder="New Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">State *</label>
                    <input type="text" name="state" required placeholder="Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-400 mb-1">Email *</label>
                    <input type="email" name="email" required placeholder="admissions@iitd.ac.in" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Phone</label>
                    <input type="text" name="phone" placeholder="011-26597135" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                </div>
            </div>

            <div>
                <label class="block text-slate-400 mb-1">Address *</label>
                <input type="text" name="address" required placeholder="Hauz Khas, New Delhi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
            </div>

            <div>
                <label class="block text-slate-400 mb-1">College Logo (Max 2MB, JPG/PNG)</label>
                <input type="file" name="logo" accept="image/jpeg,image/png" class="w-full text-slate-400">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add-college').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-brand-600 text-white font-medium rounded-xl">Register College</button>
            </div>
        </form>
    </div>
</div>
@endsection
