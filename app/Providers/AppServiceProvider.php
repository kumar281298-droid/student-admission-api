<?php

namespace App\Providers;

use App\Services\AI\AIServiceInterface;
use App\Services\AI\GeminiAIService;
use App\Services\AI\GroqAIService;
use App\Services\AI\MockAIService;
use App\Services\AI\OpenAIService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AIServiceInterface::class, function ($app) {
            $provider = strtolower((string) env('AI_PROVIDER', 'groq'));

            return match ($provider) {
                'gemini' => $app->make(GeminiAIService::class),
                'openai' => $app->make(OpenAIService::class),
                'mock' => $app->make(MockAIService::class),
                default => $app->make(GroqAIService::class),
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
