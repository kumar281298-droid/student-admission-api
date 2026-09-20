<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\College;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Services\SeatManagementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeatManagementServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_seat_management_service_decrements_seats_atomically_on_approval(): void
    {
        $college = College::create([
            'name' => 'Unit College',
            'code' => 'UC-1',
            'address' => 'Addr',
            'city' => 'City',
            'state' => 'State',
        ]);

        $course = Course::create([
            'college_id' => $college->id,
            'name' => 'Unit Course',
            'code' => 'UC-101',
            'duration' => '3 Years',
            'total_seats' => 1,
            'available_seats' => 1,
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@unit.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        $userStudent = User::create([
            'name' => 'Student Unit',
            'email' => 'student@unit.com',
            'password' => Hash::make('password'),
            'role' => 'STUDENT',
        ]);

        $student = Student::create([
            'user_id' => $userStudent->id,
            'registration_no' => 'REG-U1',
            'name' => 'Student Unit',
            'email' => 'student@unit.com',
        ]);

        $application = Application::create([
            'application_no' => 'APP-U1',
            'student_id' => $student->id,
            'college_id' => $college->id,
            'course_id' => $course->id,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        $service = new SeatManagementService();
        $approvedApp = $service->approveApplication($application, $admin, 'Approved in Unit Test');

        $this->assertEquals('APPROVED', $approvedApp->status);
        $this->assertEquals(0, $course->fresh()->available_seats);
    }

    public function test_seat_management_service_throws_exception_when_no_seats_available(): void
    {
        $this->expectException(\DomainException::class);

        $college = College::create([
            'name' => 'Unit College 2',
            'code' => 'UC-2',
            'address' => 'Addr',
            'city' => 'City',
            'state' => 'State',
        ]);

        $course = Course::create([
            'college_id' => $college->id,
            'name' => 'Unit Course 2',
            'code' => 'UC-102',
            'duration' => '3 Years',
            'total_seats' => 1,
            'available_seats' => 0, // No seats
        ]);

        $admin = User::create([
            'name' => 'Admin2',
            'email' => 'admin2@unit.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        $userStudent = User::create([
            'name' => 'Student Unit 2',
            'email' => 'student2@unit.com',
            'password' => Hash::make('password'),
            'role' => 'STUDENT',
        ]);

        $student = Student::create([
            'user_id' => $userStudent->id,
            'registration_no' => 'REG-U2',
            'name' => 'Student Unit 2',
            'email' => 'student2@unit.com',
        ]);

        $application = Application::create([
            'application_no' => 'APP-U2',
            'student_id' => $student->id,
            'college_id' => $college->id,
            'course_id' => $course->id,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        $service = new SeatManagementService();
        $service->approveApplication($application, $admin);
    }
}
