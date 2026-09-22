<?php

namespace App\Services\AI;

use App\Models\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAIService implements AIServiceInterface
{
    protected ?string $apiKey;
    protected MockAIService $fallback;

    public function __construct(MockAIService $fallback)
    {
        $this->apiKey = config('services.groq.api_key') ?: env('GROQ_API_KEY');
        $this->fallback = $fallback;
    }

    public function generateSummary(Application $application): array
    {
        $key = trim((string) $this->apiKey);

        if (empty($key)) {
            Log::info('GROQ_API_KEY missing, falling back to MockAIService.');
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

            $configuredModel = config('services.groq.model') ?: env('GROQ_MODEL');

            $models = array_unique(array_filter([
                $configuredModel,
                'qwen/qwen3.8-27b',
                'openai/gpt-oss-120b',
                'openai/gpt-oss-20b',
            ]));

            foreach ($models as $model) {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $key,
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are an admissions assistant. Generate concise summaries.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 250,
                    ]);

                if ($response->successful()) {
                    $text = $response->json('choices.0.message.content');

                    if (!empty($text)) {
                        return [
                            'summary' => trim($text),
                            'recommendation' => match ($application->status) {
                                'APPROVED' => 'RECOMMENDED_FOR_ENROLLMENT',
                                'REJECTED' => 'APPLICATION_REJECTED',
                                default => 'ELIGIBLE_FOR_ADMISSION_REVIEW',
                            },
                            'status' => 'SUCCESS',
                            'generated_at' => now()->toIso8601String(),
                            'provider' => "Groq AI API ({$model})",
                        ];
                    }
                }

                Log::warning("Groq model {$model} response: " . $response->status() . ' - ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error('Groq API Exception: ' . $e->getMessage() . '. Falling back to MockAIService.');
        }

        return $this->fallback->generateSummary($application);
    }
}
