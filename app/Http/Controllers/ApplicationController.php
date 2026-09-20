<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationApprovalRequest;
use App\Http\Requests\ApplicationSubmitRequest;
use App\Models\Application;
use App\Models\Course;
use App\Services\AuditLogService;
use App\Services\SeatManagementService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    use AuthorizesRequests;

    protected SeatManagementService $seatService;

    public function __construct(SeatManagementService $seatService)
    {
        $this->seatService = $seatService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Application::with(['student', 'college', 'course', 'approvedByUser']);

        // Server-Side Authorization Scope
        if ($user->isCollegeAdmin()) {
            $query->where('college_id', $user->college_id);
        } elseif ($user->isStudent()) {
            $studentId = $user->student?->id;
            if (!$studentId) {
                return response()->json([
                    'success' => true,
                    'message' => 'Applications retrieved',
                    'data' => [],
                ]);
            }
            $query->where('student_id', $studentId);
        }

        // Filtering
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_no', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('college_id') && !empty($request->college_id)) {
            $query->where('college_id', $request->college_id);
        }

        if ($request->has('course_id') && !empty($request->course_id)) {
            $query->where('course_id', $request->course_id);
        }

        $perPage = (int) $request->get('per_page', 15);
        $applications = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Applications retrieved successfully',
            'data' => $applications,
        ]);
    }

    public function store(ApplicationSubmitRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isStudent() || !$user->student) {
            return response()->json([
                'success' => false,
                'message' => 'Only registered students with complete profiles can submit applications.',
            ], 403);
        }

        $student = $user->student;
        $validated = $request->validated();

        $course = Course::with('college')->where('id', $validated['course_id'])->firstOrFail();

        // 1. Validate course belongs to college
        if ($course->college_id != $validated['college_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => ['course_id' => ['Selected course does not belong to the specified college.']],
            ], 422);
        }

        // 2. Validate course active & available seats
        if ($course->status !== 'ACTIVE' || $course->college->status !== 'ACTIVE') {
            return response()->json([
                'success' => false,
                'message' => 'The selected college or course is currently inactive.',
            ], 400);
        }

        if ($course->available_seats <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No seats available for this course.',
            ], 400);
        }

        // 3. Validate duplicate active application for same course
        $existing = Application::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['DRAFT', 'SUBMITTED', 'UNDER_REVIEW', 'APPROVED'])
            ->exists();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted an active application for this course.',
            ], 409); // 409 Conflict
        }

        $appNo = 'APP-' . date('Y') . '-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        $application = Application::create([
            'application_no' => $appNo,
            'student_id' => $student->id,
            'college_id' => $validated['college_id'],
            'course_id' => $validated['course_id'],
            'status' => 'SUBMITTED',
            'remarks' => $validated['remarks'] ?? 'Application submitted successfully',
            'submitted_at' => now(),
        ]);

        AuditLogService::log($user, 'APPLICATION_SUBMITTED', 'Application', $application->id);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'data' => $application->load(['student', 'college', 'course']),
        ], 201);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $application = Application::with(['student', 'college', 'course', 'approvedByUser'])->findOrFail($id);

        $this->authorize('view', $application);

        return response()->json([
            'success' => true,
            'message' => 'Application details retrieved successfully',
            'data' => $application,
        ]);
    }

    public function approve(ApplicationApprovalRequest $request, $id): JsonResponse
    {
        $application = Application::findOrFail($id);

        $this->authorize('approve', $application);

        try {
            $approvedApp = $this->seatService->approveApplication(
                $application,
                $request->user(),
                $request->input('remarks')
            );

            return response()->json([
                'success' => true,
                'message' => 'Application approved successfully and course seat updated.',
                'data' => $approvedApp,
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function reject(ApplicationApprovalRequest $request, $id): JsonResponse
    {
        $application = Application::findOrFail($id);

        $this->authorize('reject', $application);

        $reason = $request->input('reason', 'Application rejected by admission board.');

        $rejectedApp = $this->seatService->rejectApplication(
            $application,
            $request->user(),
            $reason
        );

        return response()->json([
            'success' => true,
            'message' => 'Application rejected successfully.',
            'data' => $rejectedApp,
        ]);
    }
}
