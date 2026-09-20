<?php

namespace App\Services\AI;

use App\Models\Application;

class MockAIService implements AIServiceInterface
{
    public function generateSummary(Application $application): array
    {
        $student = $application->student;
        $course = $application->course;
        $college = $application->college;

        $studentName = $student?->name ?? 'Applicant';
        $courseName = $course?->name ?? 'Selected Course';
        $collegeName = $college?->name ?? 'College';
        $status = $application->status;

        $recommendation = match ($status) {
            'APPROVED' => 'RECOMMENDED_FOR_ENROLLMENT',
            'REJECTED' => 'APPLICATION_REJECTED',
            'UNDER_REVIEW' => 'PRIORITY_INTERVIEW_RECOMMENDED',
            default => 'REQUIRES_DOCUMENT_VERIFICATION',
        };

        $summary = sprintf(
            "Student '%s' (Reg No: %s) has submitted an application for '%s' at '%s'. Current Status is '%s'. Profile completeness score is 100%% with verified contact details. Available seat status: %d remaining seats in this course.",
            $studentName,
            $student?->registration_no ?? 'N/A',
            $courseName,
            $collegeName,
            $status,
            $course?->available_seats ?? 0
        );

        return [
            'summary' => $summary,
            'recommendation' => $recommendation,
            'status' => 'SUCCESS',
            'generated_at' => now()->toIso8601String(),
            'provider' => 'MockAIService (Offline Deterministic LLM Driver)',
        ];
    }
}
