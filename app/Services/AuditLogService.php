<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogService
{
    public static function log(?User $user, string $action, string $entity, ?int $entityId = null, ?array $metadata = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'metadata' => $metadata,
        ]);
    }
}
