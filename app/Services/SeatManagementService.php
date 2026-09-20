<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use ValidationException;

class SeatManagementService
{
    /**
     * Approves an application atomically, reducing available seats safely under race conditions.
     * Uses pessimistic DB locking (lockForUpdate) on the target course row.
     */
    public function approveApplication(Application $application, User $approver, ?string $remarks = null): Application
    {
        return DB::transaction(function () use ($application, $approver, $remarks) {
            // 1. Lock course row for update to prevent concurrent race condition seat consumption
            $course = Course::where('id', $application->course_id)->lockForUpdate()->firstOrFail();

            // 2. Validate current application status
            if ($application->status === 'APPROVED') {
                throw new \InvalidArgumentException('Application is already approved.');
            }

            if ($application->status === 'REJECTED') {
                throw new \InvalidArgumentException('Cannot approve a rejected application directly.');
            }

            // 3. Verify seat availability
            if ($course->available_seats <= 0) {
                throw new \DomainException('Cannot approve application: No available seats remaining for this course.');
            }

            // 4. Atomically decrement course available seats
            $course->decrement('available_seats');

            // 5. Update application status
            $application->update([
                'status' => 'APPROVED',
                'remarks' => $remarks ?? $application->remarks,
                'approved_at' => now(),
                'approved_by' => $approver->id,
            ]);

            // 6. Log Audit Trail
            AuditLogService::log($approver, 'APPLICATION_APPROVED', 'Application', $application->id, [
                'course_id' => $course->id,
                'remaining_seats' => $course->fresh()->available_seats,
            ]);

            return $application->fresh(['student', 'college', 'course', 'approvedByUser']);
        });
    }

    /**
     * Rejects an application. If it was previously approved, releases the seat back to the course.
     */
    public function rejectApplication(Application $application, User $rejecter, string $reason): Application
    {
        return DB::transaction(function () use ($application, $rejecter, $reason) {
            $wasApproved = ($application->status === 'APPROVED');

            if ($wasApproved) {
                $course = Course::where('id', $application->course_id)->lockForUpdate()->firstOrFail();
                $course->increment('available_seats');
            }

            $application->update([
                'status' => 'REJECTED',
                'remarks' => 'Rejected: ' . $reason,
            ]);

            AuditLogService::log($rejecter, 'APPLICATION_REJECTED', 'Application', $application->id, [
                'reason' => $reason,
                'released_seat' => $wasApproved,
            ]);

            return $application->fresh(['student', 'college', 'course']);
        });
    }
}
