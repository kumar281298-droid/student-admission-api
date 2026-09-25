<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\College;
use App\Models\Course;
use App\Models\Student;
use App\Services\AI\AIServiceInterface;
use App\Services\AuditLogService;
use App\Services\SeatManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AdminWebController extends Controller
{
    protected SeatManagementService $seatService;
    protected AIServiceInterface $aiService;

    public function __construct(SeatManagementService $seatService, AIServiceInterface $aiService)
    {
        $this->seatService = $seatService;
        $this->aiService = $aiService;
    }

    public function dashboard(): View
    {
        $totalColleges = College::count();
        $activeColleges = College::where('status', 'ACTIVE')->count();

        $totalCourses = Course::count();
        $totalSeats = (int) Course::sum('total_seats');
        $availableSeats = (int) Course::sum('available_seats');
        $filledSeats = max(0, $totalSeats - $availableSeats);

        $totalApplications = Application::count();
        $submittedApps = Application::where('status', 'SUBMITTED')->count();
        $underReviewApps = Application::where('status', 'UNDER_REVIEW')->count();
        $approvedApps = Application::where('status', 'APPROVED')->count();
        $rejectedApps = Application::where('status', 'REJECTED')->count();

        $totalStudents = Student::count();
        $recentAuditLogs = AuditLog::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalColleges', 'activeColleges',
            'totalCourses', 'totalSeats', 'availableSeats', 'filledSeats',
            'totalApplications', 'submittedApps', 'underReviewApps', 'approvedApps', 'rejectedApps',
            'totalStudents', 'recentAuditLogs'
        ));
    }

    public function colleges(): View
    {
        $colleges = College::with(['courses', 'admins'])->withCount('courses')->get();
        return view('admin.colleges', compact('colleges'));
    }

    public function storeCollege(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:colleges,code|max:50',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'sometimes|in:ACTIVE,INACTIVE',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validation failed for College creation.');
        }

        $data = $validator->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $safeFilename = 'college_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
            $path = $file->storeAs('college_logos', $safeFilename, 'public');
            $data['logo'] = $path;
        }

        $college = College::create($data);
        AuditLogService::log($request->user(), 'COLLEGE_CREATED_WEB', 'College', $college->id);

        return redirect()->route('admin.colleges')->with('success', 'College registered successfully: ' . $college->name);
    }

    public function updateCollege(Request $request, $id): RedirectResponse
    {
        $college = College::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:colleges,code,' . $id,
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:ACTIVE,INACTIVE',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validation failed for College update.');
        }

        $data = $validator->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $safeFilename = 'college_' . $college->id . '_' . time() . '.' . $extension;
            $path = $file->storeAs('college_logos', $safeFilename, 'public');
            $data['logo'] = $path;
        }

        $college->update($data);
        AuditLogService::log($request->user(), 'COLLEGE_UPDATED_WEB', 'College', $college->id);

        return redirect()->route('admin.colleges')->with('success', 'College updated successfully.');
    }

    public function storeCourse(Request $request, $collegeId): RedirectResponse
    {
        $college = College::findOrFail($collegeId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'duration' => 'required|string|max:50',
            'total_seats' => 'required|integer|min:1',
            'status' => 'sometimes|in:ACTIVE,INACTIVE',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Course creation validation failed.');
        }

        $data = $validator->validated();
        $data['college_id'] = $college->id;
        $data['available_seats'] = $data['total_seats'];
        $data['status'] = $data['status'] ?? 'ACTIVE';

        $course = Course::create($data);
        AuditLogService::log($request->user(), 'COURSE_CREATED_WEB', 'Course', $course->id);

        return redirect()->route('admin.colleges')->with('success', 'Course added successfully to ' . $college->name);
    }

    public function students(): View
    {
        $students = Student::with(['user', 'applications.college', 'applications.course'])->latest()->get();
        return view('admin.students', compact('students'));
    }

    public function applications(Request $request): View
    {
        $query = Application::with(['student', 'college', 'course', 'approvedByUser']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_no', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('college_id')) {
            $query->where('college_id', $request->college_id);
        }

        $applications = $query->latest()->paginate(15);
        $colleges = College::all();

        return view('admin.applications', compact('applications', 'colleges'));
    }

    public function approveApplication(Request $request, $id): RedirectResponse
    {
        $application = Application::findOrFail($id);

        try {
            $remarks = $request->input('remarks', 'Approved by System Admin.');
            $this->seatService->approveApplication($application, $request->user(), $remarks);
            return back()->with('success', 'Application ' . $application->application_no . ' APPROVED successfully! Course seat allocated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    public function rejectApplication(Request $request, $id): RedirectResponse
    {
        $application = Application::findOrFail($id);
        $reason = $request->input('reason', 'Application rejected by admission committee.');

        try {
            $this->seatService->rejectApplication($application, $request->user(), $reason);
            return back()->with('warning', 'Application ' . $application->application_no . ' REJECTED.');
        } catch (\Exception $e) {
            return back()->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }

    public function auditLogs(): View
    {
        $auditLogs = AuditLog::with('user')->latest()->paginate(20);
        return view('admin.audit-logs', compact('auditLogs'));
    }
}
