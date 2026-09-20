<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Http\JsonResponse;

class CollegeController extends Controller
{
    public function index(): JsonResponse
    {
        $colleges = College::withCount('courses')
            ->where('status', 'ACTIVE')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Colleges retrieved successfully',
            'data' => $colleges,
        ]);
    }

    public function show($id): JsonResponse
    {
        $college = College::with(['courses' => function ($query) {
            $query->where('status', 'ACTIVE');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'College details retrieved successfully',
            'data' => $college,
        ]);
    }

    public function courses($id): JsonResponse
    {
        $college = College::findOrFail($id);
        $courses = $college->courses()->where('status', 'ACTIVE')->get();

        return response()->json([
            'success' => true,
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
        ]);
    }
}
