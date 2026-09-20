<?php

namespace App\Services\AI;

use App\Models\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAIService implements AIServiceInterface
{
    protected ?string $apiKey;
    protected MockAIService $fallback;

    public function __construct(MockAIService $fallback)
    {
        $this->apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        $this->fallback = $fallback;
    }

    public function generateSummary(Application $application): array
    {
        $key = trim((string) $this->apiKey);

        if (empty($key)) {
            Log::info('GEMINI_API_KEY missing, falling back to MockAIService.');
            return $this->fallback->generateSummary($application);
        }

        try {
            $student = $application->student;
            $course = $application->course;
            $college = $application->college;

            $prompt = sprintf(
                "You are an AI admissions assistant for a college. Generate a concise 2-3 sentence evaluation summary for Student: %s (Reg No: %s) who applied for '%s' at '%s'. Current application status is '%s'. Highlight applicant profile readiness and seat status (%d remaining seats).",
                $student?->name ?? 'Applicant',
                $student?->registration_no ?? 'N/A',
                $course?->name ?? 'Selected Course',
                $college?->name ?? 'College',
                $application->status,
                $course?->available_seats ?? 0
            );

            // Active Gemini models
            $models = ['gemini-1.5-pro', 'gemini-1.5-flash', 'gemini-2.0-flash', 'gemini-3.8-flash'];

            foreach ($models as $model) {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $key;

                $response = Http::timeout(10)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ]
                    ]);

                if ($response->successful()) {
                    $candidates = $response->json('candidates');
                    $text = $candidates[0]['content']['parts'][0]['text'] ?? null;

                    if ($text) {
                        return [
                            'summary' => trim($text),
                            'recommendation' => match ($application->status) {
                                'APPROVED' => 'RECOMMENDED_FOR_ENROLLMENT',
                                'REJECTED' => 'APPLICATION_REJECTED',
                                default => 'ELIGIBLE_FOR_ADMISSION_REVIEW',
                            },
                            'status' => 'SUCCESS',
                            'generated_at' => now()->toIso8601String(),
                            'provider' => "Google Gemini AI API ({$model})",
                        ];
                    }
                }

                Log::warning("Gemini model {$model} response: " . $response->status() . ' - ' . $response->body());
            }

        } catch (\Throwable $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage() . '. Falling back to MockAIService.');
        }

        return $this->fallback->generateSummary($application);
    }
}
