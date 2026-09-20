<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\College;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Colleges
        $college1 = College::create([
            'name' => 'Delhi University',
            'code' => 'DU-001',
            'address' => 'North Campus, University Enclave',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'email' => 'contact@du.ac.in',
            'phone' => '011-27667853',
            'status' => 'ACTIVE',
        ]);

        $college2 = College::create([
            'name' => 'IIT Bombay',
            'code' => 'IITB-002',
            'address' => 'Powai',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'email' => 'admissions@iitb.ac.in',
            'phone' => '022-25722545',
            'status' => 'ACTIVE',
        ]);

        $college3 = College::create([
            'name' => "St. Xavier's College",
            'code' => 'SXC-003',
            'address' => '5, Mahapalika Marg',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'email' => 'info@xaviers.edu',
            'phone' => '022-22620661',
            'status' => 'ACTIVE',
        ]);

        // 2. Create Courses (At least 2 per college)
        $course1 = Course::create([
            'college_id' => $college1->id,
            'name' => 'B.Sc Computer Science (Hons)',
            'code' => 'CS-101',
            'duration' => '3 Years',
            'total_seats' => 10,
            'available_seats' => 10,
            'status' => 'ACTIVE',
        ]);

        $course2 = Course::create([
            'college_id' => $college1->id,
            'name' => 'B.A. Economics (Hons)',
            'code' => 'ECO-102',
            'duration' => '3 Years',
            'total_seats' => 5,
            'available_seats' => 5,
            'status' => 'ACTIVE',
        ]);

        $course3 = Course::create([
            'college_id' => $college2->id,
            'name' => 'B.Tech Computer Science',
            'code' => 'BTECH-CS',
            'duration' => '4 Years',
            'total_seats' => 2,
            'available_seats' => 2,
            'status' => 'ACTIVE',
        ]);

        $course4 = Course::create([
            'college_id' => $college2->id,
            'name' => 'B.Tech Electrical Engineering',
            'code' => 'BTECH-EE',
            'duration' => '4 Years',
            'total_seats' => 4,
            'available_seats' => 4,
            'status' => 'ACTIVE',
        ]);

        $course5 = Course::create([
            'college_id' => $college3->id,
            'name' => 'Bachelor of Mass Media',
            'code' => 'BMM-301',
            'duration' => '3 Years',
            'total_seats' => 8,
            'available_seats' => 8,
            'status' => 'ACTIVE',
        ]);

        // 3. Create System Admin
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@system.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        // 4. Create College Admin for Delhi University
        $collegeAdmin = User::create([
            'name' => 'DU College Admin',
            'email' => 'collegeadmin@delhiuniv.ac.in',
            'password' => Hash::make('password'),
            'role' => 'COLLEGE_ADMIN',
            'college_id' => $college1->id,
        ]);

        // 5. Create Students & Profiles
        $userStudent1 = User::create([
            'name' => 'Rahul Sharma',
            'email' => 'student1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'STUDENT',
        ]);

        $student1 = Student::create([
            'user_id' => $userStudent1->id,
            'registration_no' => 'STU-2026-001',
            'name' => 'Rahul Sharma',
            'email' => 'student1@gmail.com',
            'mobile' => '9876543210',
            'dob' => '2004-05-15',
            'gender' => 'Male',
            'address' => 'Connaught Place, New Delhi',
            'status' => 'ACTIVE',
        ]);

        $userStudent2 = User::create([
            'name' => 'Priya Patel',
            'email' => 'student2@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'STUDENT',
        ]);

        $student2 = Student::create([
            'user_id' => $userStudent2->id,
            'registration_no' => 'STU-2026-002',
            'name' => 'Priya Patel',
            'email' => 'student2@gmail.com',
            'mobile' => '9812345678',
            'dob' => '2005-08-22',
            'gender' => 'Female',
            'address' => 'Andheri West, Mumbai',
            'status' => 'ACTIVE',
        ]);

        // 6. Create Sample Applications
        Application::create([
            'application_no' => 'APP-2026-001',
            'student_id' => $student1->id,
            'college_id' => $college1->id,
            'course_id' => $course1->id,
            'status' => 'SUBMITTED',
            'remarks' => 'Application submitted for B.Sc Computer Science',
            'submitted_at' => now(),
        ]);

        Application::create([
            'application_no' => 'APP-2026-002',
            'student_id' => $student2->id,
            'college_id' => $college2->id,
            'course_id' => $course3->id,
            'status' => 'UNDER_REVIEW',
            'remarks' => 'Review in progress by IIT Bombay admission committee',
            'submitted_at' => now()->subDay(),
        ]);
    }
}
