<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\College;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $collegeQuery = College::query();
        $courseQuery = Course::query();
        $applicationQuery = Application::query();

        if ($user->isCollegeAdmin()) {
            $collegeId = $user->college_id;
            $courseQuery->where('college_id', $collegeId);
            $applicationQuery->where('college_id', $collegeId);
        }

        $totalColleges = $user->isCollegeAdmin() ? 1 : College::count();
        $activeColleges = $user->isCollegeAdmin() 
            ? College::where('id', $user->college_id)->where('status', 'ACTIVE')->count()
            : College::where('status', 'ACTIVE')->count();

        $totalCourses = $courseQuery->count();
        $totalSeats = (int) (clone $courseQuery)->sum('total_seats');
        $availableSeats = (int) (clone $courseQuery)->sum('available_seats');

        $totalApplications = $applicationQuery->count();
        $submittedApps = (clone $applicationQuery)->where('status', 'SUBMITTED')->count();
        $underReviewApps = (clone $applicationQuery)->where('status', 'UNDER_REVIEW')->count();
        $approvedApps = (clone $applicationQuery)->where('status', 'APPROVED')->count();
        $rejectedApps = (clone $applicationQuery)->where('status', 'REJECTED')->count();

        $totalStudents = Student::count();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard analytics retrieved successfully',
            'data' => [
                'colleges' => [
                    'total' => $totalColleges,
                    'active' => $activeColleges,
                ],
                'courses' => [
                    'total' => $totalCourses,
                    'total_seats' => $totalSeats,
                    'available_seats' => $availableSeats,
                    'filled_seats' => $totalSeats - $availableSeats,
                ],
                'applications' => [
                    'total' => $totalApplications,
                    'submitted' => $submittedApps,
                    'under_review' => $underReviewApps,
                    'approved' => $approvedApps,
                    'rejected' => $rejectedApps,
                ],
                'students' => [
                    'total' => $totalStudents,
                ],
            ],
        ]);
    }

    public function auditLogs(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $query = AuditLog::with('user');

        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }

        $logs = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Audit logs retrieved successfully',
            'data' => $logs,
        ]);
    }
}
