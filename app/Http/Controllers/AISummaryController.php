<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Services\AI\AIServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AISummaryController extends Controller
{
    use AuthorizesRequests;

    protected AIServiceInterface $aiService;

    public function __construct(AIServiceInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request, $id): JsonResponse
    {
        $application = Application::with(['student', 'college', 'course'])->findOrFail($id);

        $this->authorize('view', $application);

        $summaryResult = $this->aiService->generateSummary($application);

        return response()->json([
            'success' => true,
            'message' => 'AI Application Summary generated successfully',
            'data' => [
                'application_id' => $application->id,
                'application_no' => $application->application_no,
                'student_name' => $application->student?->name,
                'college_name' => $application->college?->name,
                'course_name' => $application->course?->name,
                'ai_analysis' => $summaryResult,
            ],
        ]);
    }
}
