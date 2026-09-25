<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isStudent() || !$user->student) {
            return response()->json([
                'success' => false,
                'message' => 'Profile only available for student accounts.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Student profile retrieved',
            'data' => $user->student->load('user'),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isStudent() || !$user->student) {
            return response()->json([
                'success' => false,
                'message' => 'Only student accounts can update student profiles.',
            ], 403);
        }

        $student = $user->student;

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Handle Profile Photo Upload safely as per assignment specs
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $extension = $file->getClientOriginalExtension();
            $safeFilename = 'profile_' . $student->id . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

            // Delete old photo if exists
            if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
                Storage::disk('public')->delete($student->profile_photo);
            }

            $path = $file->storeAs('profile_photos', $safeFilename, 'public');
            $data['profile_photo'] = $path;
        }

        $student->update($data);

        if (isset($data['name'])) {
            $user->update(['name' => $data['name']]);
        }

        AuditLogService::log($user, 'PROFILE_UPDATED', 'Student', $student->id);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $student->fresh(),
        ]);
    }
}
