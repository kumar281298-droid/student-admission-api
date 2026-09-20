<?php

namespace App\Services\AI;

use App\Models\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService implements AIServiceInterface
{
    protected ?string $apiKey;
    protected MockAIService $fallback;

    public function __construct(MockAIService $fallback)
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        $this->fallback = $fallback;
    }

    public function generateSummary(Application $application): array
    {
        if (empty($this->apiKey)) {
            Log::info('OpenAI API key missing, falling back to MockAIService.');
            return $this->fallback->generateSummary($application);
        }

        try {
            $student = $application->student;
            $course = $application->course;
            $college = $application->college;

            $prompt = sprintf(
                "Generate a concise 2-sentence admission summary for Student: %s, RegNo: %s applying for Course: %s at College: %s. Application status: %s.",
                $student?->name ?? 'N/A',
                $student?->registration_no ?? 'N/A',
                $course?->name ?? 'N/A',
                $college?->name ?? 'N/A',
                $application->status
            );

            $response = Http::withToken($this->apiKey)
                ->timeout(5)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an admissions assistant. Generate concise summaries.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 150,
                ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');
                return [
                    'summary' => trim($text),
                    'recommendation' => 'PROCESSED_BY_LLM',
                    'status' => 'SUCCESS',
                    'generated_at' => now()->toIso8601String(),
                    'provider' => 'OpenAIService (gpt-3.5-turbo)',
                ];
            }

            Log::warning('OpenAI API call failed with status ' . $response->status() . '. Falling back to MockAIService.');
        } catch (\Throwable $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage() . '. Falling back to MockAIService.');
        }

        return $this->fallback->generateSummary($application);
    }
}
