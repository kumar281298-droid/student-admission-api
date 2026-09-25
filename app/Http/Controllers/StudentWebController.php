<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\College;
use App\Models\Course;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class StudentWebController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $student = $user->student;

        $applications = $student
            ? Application::with(['college', 'course', 'approvedByUser'])
                ->where('student_id', $student->id)
                ->latest()
                ->get()
            : collect([]);

        return view('student.dashboard', compact('user', 'student', 'applications'));
    }

    public function profile(Request $request): View
    {
        $user = $request->user();
        $student = $user->student;
        return view('student.profile', compact('user', 'student'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return back()->with('error', 'Student profile not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Profile update failed. Please check form inputs.');
        }

        $data = $validator->validated();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $extension = $file->getClientOriginalExtension();
            $safeFilename = 'profile_' . $student->id . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

            if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
                Storage::disk('public')->delete($student->profile_photo);
            }

            $path = $file->storeAs('profile_photos', $safeFilename, 'public');
            $data['profile_photo'] = $path;
        }

        $student->update($data);
        $user->update(['name' => $data['name']]);

        AuditLogService::log($user, 'PROFILE_UPDATED_WEB', 'Student', $student->id);

        return redirect()->route('student.profile')->with('success', 'Profile and avatar photo updated successfully!');
    }

    public function showApplyForm(): View
    {
        $colleges = College::with(['courses' => function ($q) {
            $q->where('status', 'ACTIVE');
        }])->where('status', 'ACTIVE')->get();

        return view('student.apply', compact('colleges'));
    }

    public function submitApplication(Request $request): RedirectResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return back()->with('error', 'Please complete your student profile before applying.');
        }

        $validator = Validator::make($request->all(), [
            'college_id' => 'required|exists:colleges,id',
            'course_id' => 'required|exists:courses,id',
            'remarks' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Application submission failed.');
        }

        $validated = $validator->validated();
        $course = Course::with('college')->where('id', $validated['course_id'])->firstOrFail();

        // 1. Validate course belongs to college
        if ($course->college_id != $validated['college_id']) {
            return back()->withInput()->with('error', 'Validation Error: Selected course does not belong to the specified college.');
        }

        // 2. Validate course & college status
        if ($course->status !== 'ACTIVE' || $course->college->status !== 'ACTIVE') {
            return back()->withInput()->with('error', 'Validation Error: The selected college or course is currently inactive.');
        }

        // 3. Validate seat availability
        if ($course->available_seats <= 0) {
            return back()->withInput()->with('error', 'Validation Error: No seats available for this course.');
        }

        // 4. Validate duplicate active application
        $existing = Application::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['DRAFT', 'SUBMITTED', 'UNDER_REVIEW', 'APPROVED'])
            ->exists();

        if ($existing) {
            return back()->withInput()->with('error', 'Conflict Error: You have already submitted an active application for this course.');
        }

        $appNo = 'APP-' . date('Y') . '-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        $application = Application::create([
            'application_no' => $appNo,
            'student_id' => $student->id,
            'college_id' => $validated['college_id'],
            'course_id' => $validated['course_id'],
            'status' => 'SUBMITTED',
            'remarks' => $validated['remarks'] ?? 'Application submitted via student portal',
            'submitted_at' => now(),
        ]);

        AuditLogService::log($user, 'APPLICATION_SUBMITTED_WEB', 'Application', $application->id);

        return redirect()->route('student.dashboard')->with('success', 'Application ' . $appNo . ' submitted successfully!');
    }
}
