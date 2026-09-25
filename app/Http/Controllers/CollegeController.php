<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Course;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollegeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = College::withCount('courses');
        
        // Non-admin users only see ACTIVE colleges by default unless specified
        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('status', 'ACTIVE');
        }

        $colleges = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Colleges retrieved successfully',
            'data' => $colleges,
        ]);
    }

    public function show($id): JsonResponse
    {
        $college = College::with(['courses'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'College details retrieved successfully',
            'data' => $college,
        ]);
    }

    public function courses($id): JsonResponse
    {
        $college = College::findOrFail($id);
        $courses = $college->courses()->get();

        return response()->json([
            'success' => true,
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
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

        AuditLogService::log($request->user(), 'COLLEGE_CREATED', 'College', $college->id);

        return response()->json([
            'success' => true,
            'message' => 'College created successfully',
            'data' => $college,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $college = College::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:colleges,code,' . $id,
            'address' => 'sometimes|string|max:500',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'sometimes|in:ACTIVE,INACTIVE',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
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

        AuditLogService::log($request->user(), 'COLLEGE_UPDATED', 'College', $college->id);

        return response()->json([
            'success' => true,
            'message' => 'College updated successfully',
            'data' => $college,
        ]);
    }

    public function storeCourse(Request $request, $collegeId): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $college = College::findOrFail($collegeId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'duration' => 'required|string|max:50',
            'total_seats' => 'required|integer|min:1',
            'status' => 'sometimes|in:ACTIVE,INACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['college_id'] = $college->id;
        $data['available_seats'] = $data['total_seats'];
        $data['status'] = $data['status'] ?? 'ACTIVE';

        $course = Course::create($data);

        AuditLogService::log($request->user(), 'COURSE_CREATED', 'Course', $course->id);

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => $course,
        ], 201);
    }

    public function updateCourse(Request $request, $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $course = Course::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50',
            'duration' => 'sometimes|string|max:50',
            'total_seats' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:ACTIVE,INACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        
        // Adjust available_seats if total_seats increased/decreased
        if (isset($data['total_seats']) && $data['total_seats'] != $course->total_seats) {
            $diff = $data['total_seats'] - $course->total_seats;
            $data['available_seats'] = max(0, $course->available_seats + $diff);
        }

        $course->update($data);

        AuditLogService::log($request->user(), 'COURSE_UPDATED', 'Course', $course->id);

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully',
            'data' => $course,
        ]);
    }
}
