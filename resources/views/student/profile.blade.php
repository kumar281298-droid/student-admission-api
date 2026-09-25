@extends('layouts.app')

@section('title', 'My Student Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6 border border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight">Student Profile & Documents</h1>
                <p class="text-xs text-slate-400">Keep your information and photo updated for college verification.</p>
            </div>
            <span class="px-3 py-1 bg-brand-500/10 text-brand-300 border border-brand-500/20 rounded-full font-mono text-xs font-bold">
                {{ $student->registration_no ?? 'REG-PENDING' }}
            </span>
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            
            <!-- Photo Upload Area -->
            <div class="p-4 bg-slate-900/90 rounded-2xl border border-slate-800 flex items-center gap-6">
                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-800 border-2 border-brand-500/40 flex items-center justify-center shrink-0">
                    @if($student && $student->profile_photo)
                        <img id="photo-preview" src="{{ asset('storage/' . $student->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <span id="avatar-placeholder" class="text-3xl">🎓</span>
                        <img id="photo-preview" class="w-full h-full object-cover hidden">
                    @endif
                </div>
                <div class="space-y-1.5 flex-1">
                    <label for="profile_photo" class="block font-medium text-slate-300">Upload Profile Photo (JPG/PNG, Max 2MB)</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png" onchange="previewImage(event)" class="block w-full text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-500 cursor-pointer">
                    <p class="text-[10px] text-slate-500">Security Check: Validates image MIME type, generates safe filename hash, and stores via Laravel Storage disk.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block font-medium text-slate-400 mb-1">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $student->name ?? $user->name) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-white">
                </div>
                <div>
                    <label class="block font-medium text-slate-400 mb-1">Email Address</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2 text-slate-500 cursor-not-allowed">
                </div>
                <div>
                    <label for="mobile" class="block font-medium text-slate-400 mb-1">Mobile Number</label>
                    <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $student->mobile ?? '') }}" placeholder="9876543210" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-white">
                </div>
                <div>
                    <label for="dob" class="block font-medium text-slate-400 mb-1">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="{{ old('dob', $student->dob ?? '') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-white">
                </div>
                <div>
                    <label for="gender" class="block font-medium text-slate-400 mb-1">Gender</label>
                    <select id="gender" name="gender" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white">
                        <option value="Male" {{ old('gender', $student->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $student->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $student->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="block font-medium text-slate-400 mb-1">Address</label>
                    <textarea id="address" name="address" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-white">{{ old('address', $student->address ?? '') }}</textarea>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all">
                    Save Profile & Photo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('photo-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
