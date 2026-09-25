<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\College;
use App\Models\Course;
use App\Services\AI\AIServiceInterface;
use App\Services\SeatManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollegeAdminWebController extends Controller
{
    use AuthorizesRequests;

    protected SeatManagementService $seatService;
    protected AIServiceInterface $aiService;

    public function __construct(SeatManagementService $seatService, AIServiceInterface $aiService)
    {
        $this->seatService = $seatService;
        $this->aiService = $aiService;
    }

    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $college = College::with('courses')->find($user->college_id);

        if (!$college) {
            abort(404, 'No college associated with this College Admin account.');
        }

        $totalCourses = $college->courses()->count();
        $totalSeats = (int) $college->courses()->sum('total_seats');
        $availableSeats = (int) $college->courses()->sum('available_seats');
        $filledSeats = max(0, $totalSeats - $availableSeats);

        $totalApps = Application::where('college_id', $college->id)->count();
        $submittedApps = Application::where('college_id', $college->id)->where('status', 'SUBMITTED')->count();
        $underReviewApps = Application::where('college_id', $college->id)->where('status', 'UNDER_REVIEW')->count();
        $approvedApps = Application::where('college_id', $college->id)->where('status', 'APPROVED')->count();
        $rejectedApps = Application::where('college_id', $college->id)->where('status', 'REJECTED')->count();

        $recentApplications = Application::with(['student', 'course'])
            ->where('college_id', $college->id)
            ->latest()
            ->take(8)
            ->get();

        return view('college_admin.dashboard', compact(
            'college', 'totalCourses', 'totalSeats', 'availableSeats', 'filledSeats',
            'totalApps', 'submittedApps', 'underReviewApps', 'approvedApps', 'rejectedApps',
            'recentApplications'
        ));
    }

    public function applications(Request $request): View
    {
        $user = $request->user();
        $college = College::findOrFail($user->college_id);

        $query = Application::with(['student', 'course', 'approvedByUser'])
            ->where('college_id', $college->id);

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

        $applications = $query->latest()->paginate(15);

        return view('college_admin.applications', compact('college', 'applications'));
    }

    public function approveApplication(Request $request, $id): RedirectResponse
    {
        $application = Application::findOrFail($id);

        // Server-side guard: College Admin can only approve apps for their college
        if ($application->college_id != $request->user()->college_id) {
            abort(403, 'Unauthorized. You can only manage applications for your assigned college.');
        }

        try {
            $remarks = $request->input('remarks', 'Approved by College Admission Committee.');
            $this->seatService->approveApplication($application, $request->user(), $remarks);
            return back()->with('success', 'Application ' . $application->application_no . ' APPROVED! Course seat updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    public function rejectApplication(Request $request, $id): RedirectResponse
    {
        $application = Application::findOrFail($id);

        if ($application->college_id != $request->user()->college_id) {
            abort(403, 'Unauthorized. You can only manage applications for your assigned college.');
        }

        $reason = $request->input('reason', 'Application rejected by college admission board.');

        try {
            $this->seatService->rejectApplication($application, $request->user(), $reason);
            return back()->with('warning', 'Application ' . $application->application_no . ' REJECTED.');
        } catch (\Exception $e) {
            return back()->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }
}
