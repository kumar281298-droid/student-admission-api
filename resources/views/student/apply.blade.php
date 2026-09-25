@extends('layouts.app')

@section('title', 'Apply for Admission')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6 border border-slate-800 shadow-2xl">
        <div class="space-y-1">
            <h1 class="text-xl font-bold text-white tracking-tight">📝 Submit Admission Application</h1>
            <p class="text-xs text-slate-400">Select target institute & program. Course seat availability will be validated automatically.</p>
        </div>

        <form action="{{ route('student.apply.post') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            
            <div>
                <label for="college_id" class="block font-medium text-slate-300 mb-1">Target College *</label>
                <select id="college_id" name="college_id" required onchange="updateCourses(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    <option value="">-- Select Participating College --</option>
                    @foreach($colleges as $c)
                        <option value="{{ $c->id }}" {{ old('college_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }}) - {{ $c->city }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="course_id" class="block font-medium text-slate-300 mb-1">Target Course Program *</label>
                <select id="course_id" name="course_id" required onchange="checkSeats(this)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">
                    <option value="">-- Select College First --</option>
                </select>
            </div>

            <!-- Seat Availability Live Banner -->
            <div id="seat-status-box" class="p-3.5 rounded-2xl border text-xs hidden"></div>

            <div>
                <label for="remarks" class="block font-medium text-slate-300 mb-1">Applicant Statement / Motivation Remarks</label>
                <textarea id="remarks" name="remarks" rows="3" placeholder="Describe why you want to pursue this program..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white">{{ old('remarks') }}</textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <a href="{{ route('student.dashboard') }}" class="px-4 py-2.5 bg-slate-800 text-slate-300 rounded-xl font-medium">Cancel</a>
                <button type="submit" id="btn-submit-apply" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-brand-500/20">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const collegesData = @json($colleges);

    function updateCourses(collegeId) {
        const courseSelect = document.getElementById('course_id');
        const seatBox = document.getElementById('seat-status-box');
        seatBox.classList.add('hidden');

        if (!collegeId) {
            courseSelect.innerHTML = `<option value="">-- Select College First --</option>`;
            return;
        }

        const selectedCol = collegesData.find(c => c.id == collegeId);
        if (selectedCol && selectedCol.courses) {
            courseSelect.innerHTML = `<option value="">-- Choose Course --</option>` + selectedCol.courses.map(crs => `
                <option value="${crs.id}" data-seats="${crs.available_seats}" data-totalseats="${crs.total_seats}">
                    ${crs.name} (${crs.code}) - ${crs.available_seats} seats left
                </option>
            `).join('');
        } else {
            courseSelect.innerHTML = `<option value="">No courses available</option>`;
        }
    }

    function checkSeats(selectElem) {
        const opt = selectElem.options[selectElem.selectedIndex];
        const seatBox = document.getElementById('seat-status-box');
        const submitBtn = document.getElementById('btn-submit-apply');

        if (selectElem.value && opt) {
            const seats = parseInt(opt.getAttribute('data-seats') || 0);
            const total = parseInt(opt.getAttribute('data-totalseats') || 0);
            seatBox.classList.remove('hidden');

            if (seats > 0) {
                seatBox.className = 'p-3.5 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-xs text-emerald-300 flex items-center justify-between';
                seatBox.innerHTML = `<span>✅ <strong>${seats} of ${total} Seats Remaining</strong> — Status: Available for Submission</span>`;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                seatBox.className = 'p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-xs text-rose-300 flex items-center justify-between';
                seatBox.innerHTML = `<span>❌ <strong>0 Seats Remaining</strong> — Course Full. Submission blocked.</span>`;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        } else {
            seatBox.classList.add('hidden');
        }
    }
</script>
@endsection
