<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\College;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentAdmissionSystemTest extends TestCase
{
    use RefreshDatabase;

    protected College $college;
    protected Course $course;
    protected User $admin;
    protected User $collegeAdmin;
    protected User $studentUser1;
    protected Student $student1;
    protected User $studentUser2;
    protected Student $student2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create College & Course
        $this->college = College::create([
            'name' => 'Test University',
            'code' => 'TU-100',
            'address' => 'Test Address',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'status' => 'ACTIVE',
        ]);

        $this->course = Course::create([
            'college_id' => $this->college->id,
            'name' => 'B.Tech CS',
            'code' => 'CS-01',
            'duration' => '4 Years',
            'total_seats' => 2,
            'available_seats' => 2,
            'status' => 'ACTIVE',
        ]);

        // 2. Create Users
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        $this->collegeAdmin = User::create([
            'name' => 'College Admin',
            'email' => 'cadmin@test.com',
            'password' => Hash::make('password'),
            'role' => 'COLLEGE_ADMIN',
            'college_id' => $this->college->id,
        ]);

        $this->studentUser1 = User::create([
            'name' => 'Student One',
            'email' => 'student1@test.com',
            'password' => Hash::make('password'),
            'role' => 'STUDENT',
        ]);

        $this->student1 = Student::create([
            'user_id' => $this->studentUser1->id,
            'registration_no' => 'STU-001',
            'name' => 'Student One',
            'email' => 'student1@test.com',
            'status' => 'ACTIVE',
        ]);

        $this->studentUser2 = User::create([
            'name' => 'Student Two',
            'email' => 'student2@test.com',
            'password' => Hash::make('password'),
            'role' => 'STUDENT',
        ]);

        $this->student2 = Student::create([
            'user_id' => $this->studentUser2->id,
            'registration_no' => 'STU-002',
            'name' => 'Student Two',
            'email' => 'student2@test.com',
            'status' => 'ACTIVE',
        ]);
    }

    /** 1. Student can register and login */
    public function test_student_can_register_and_login(): void
    {
        $regResponse = $this->postJson('/api/register', [
            'name' => 'New Student',
            'email' => 'newstudent@test.com',
            'password' => 'secret123',
            'mobile' => '9998887770',
        ]);

        $regResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['user', 'token']]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'newstudent@test.com',
            'password' => 'secret123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token']]);
    }

    /** 2. Unauthorized student cannot access another student's application */
    public function test_unauthorized_student_cannot_access_another_students_application(): void
    {
        $application = Application::create([
            'application_no' => 'APP-TEST-001',
            'student_id' => $this->student1->id,
            'college_id' => $this->college->id,
            'course_id' => $this->course->id,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        // Student 2 tries to access Student 1's application
        $response = $this->actingAs($this->studentUser2, 'sanctum')
            ->getJson("/api/applications/{$application->id}");

        $response->assertStatus(403);
    }

    /** 3. Application cannot be submitted if course has no seats */
    public function test_application_cannot_be_submitted_if_course_has_no_seats(): void
    {
        // Set course available seats to 0
        $this->course->update(['available_seats' => 0]);

        $response = $this->actingAs($this->studentUser1, 'sanctum')
            ->postJson('/api/applications', [
                'college_id' => $this->college->id,
                'course_id' => $this->course->id,
            ]);

        $response->assertStatus(400)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'No seats available for this course.');
    }

    /** 4. Duplicate active application is rejected */
    public function test_duplicate_application_is_rejected(): void
    {
        // First submission
        Application::create([
            'application_no' => 'APP-TEST-002',
            'student_id' => $this->student1->id,
            'college_id' => $this->college->id,
            'course_id' => $this->course->id,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        // Duplicate submission attempt by same student for same course
        $response = $this->actingAs($this->studentUser1, 'sanctum')
            ->postJson('/api/applications', [
                'college_id' => $this->college->id,
                'course_id' => $this->course->id,
            ]);

        $response->assertStatus(409)
            ->assertJsonPath('success', false);
    }

    /** 5. Authorized admin can approve an application */
    public function test_authorized_admin_can_approve_application(): void
    {
        $application = Application::create([
            'application_no' => 'APP-TEST-003',
            'student_id' => $this->student1->id,
            'college_id' => $this->college->id,
            'course_id' => $this->course->id,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/applications/{$application->id}/approve", [
                'remarks' => 'Approved by System Admin',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'APPROVED');

        // Verify seat was decremented
        $this->assertEquals(1, $this->course->fresh()->available_seats);
    }

    /** 6. Unauthorized user cannot approve an application */
    public function test_unauthorized_user_cannot_approve_application(): void
    {
        $application = Application::create([
            'application_no' => 'APP-TEST-004',
            'student_id' => $this->student1->id,
            'college_id' => $this->college->id,
            'course_id' => $this->course->id,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        // Student tries to approve application
        $response = $this->actingAs($this->studentUser1, 'sanctum')
            ->postJson("/api/applications/{$application->id}/approve");

        $response->assertStatus(403);
    }

    /** 7. AI Application Summary Endpoint */
    public function test_ai_application_summary_endpoint(): void
    {
        $application = Application::create([
            'application_no' => 'APP-TEST-005',
            'student_id' => $this->student1->id,
            'college_id' => $this->college->id,
            'course_id' => $this->course->id,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/applications/{$application->id}/ai-summary");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['ai_analysis' => ['summary', 'recommendation', 'provider']]]);
    }
}
