<?php

namespace App\Providers;

use App\Services\AI\AIServiceInterface;
use App\Services\AI\GeminiAIService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AIServiceInterface::class, GeminiAIService::class);
    }

    public function boot(): void
    {
        //
    }
}
