<?php

namespace App\Services\AI;

use App\Models\Application;

interface AIServiceInterface
{
    /**
     * Generates a concise AI summary for an admission application.
     *
     * @param Application $application
     * @return array{summary: string, recommendation: string, status: string, generated_at: string, provider: string}
     */
    public function generateSummary(Application $application): array;
}
