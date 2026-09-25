# 🎓 Student Admission & College Management System (Laravel 12 & Web UI)

A production-grade, secure, and clean **Laravel 12 RESTful API & Interactive Web Dashboard** for managing student college admissions. Designed with **Server-Side RBAC**, **Atomic Concurrent-Safe Seat Allocation**, **AI-Assisted Application Summarization**, **Interactive Web UI**, and automated PHPUnit test coverage.

---

## ⚡ Quick Start Guide

### Prerequisites
- **PHP** >= 8.2 with `pdo_sqlite` / `pdo_mysql` extension
- **Composer** >= 2.0

### Installation & Run

```bash
# 1. Clone repository & navigate to project directory
cd student-admission-api

# 2. Install dependencies
composer install

# 3. Setup Environment File
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate

# 5. Link Storage Disk (For Logos & Profile Photos)
php artisan storage:link

# 6. Execute Database Migrations & Seeders
php artisan migrate:fresh --seed

# 7. Run PHPUnit Test Suite (Verifies 100% Pass)
php artisan test

# 8. Start Development Server (runs on http://127.0.0.1:8000)
php artisan serve
```

### 🖥️ Interactive Web UI Dashboard
Open **`http://127.0.0.1:8000`** in any web browser to access the complete Single-Page Application (SPA) testing dashboard. Use the **Quick Role Switcher Toolbar** at the top to instantly test permissions as System Admin, College Admin, or Student!

---

## 🔐 Test Credentials

Seed data comes out-of-the-box with pre-created accounts for evaluation:

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **System Admin** | `admin@system.com` | `password` | Full system control across all colleges |
| **College Admin (DU)** | `collegeadmin@delhiuniv.ac.in` | `password` | College-scoped access (Delhi University only) |
| **Student 1** | `student1@gmail.com` | `password` | Student portal (Rahul Sharma) |
| **Student 2** | `student2@gmail.com` | `password` | Student portal (Priya Patel) |

---

## 📡 Key REST API Endpoints

| Method | Endpoint | Auth | Description |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Public | Register new Student account |
| `POST` | `/api/login` | Public | Authenticate & obtain Sanctum Bearer Token |
| `GET` | `/api/colleges` | Public | List active colleges with course count |
| `GET` | `/api/colleges/{id}` | Public | View college details & courses |
| `GET` | `/api/applications` | Bearer Token | List applications (Scoped per Role & filters) |
| `POST` | `/api/applications` | Bearer Token | Submit new application (`DRAFT` ➔ `SUBMITTED`) |
| `GET` | `/api/applications/{id}` | Bearer Token | View application details (Policy guarded) |
| `POST` | `/api/applications/{id}/approve` | Bearer Token | Approve application (**Atomic Seat Lock**) |
| `POST` | `/api/applications/{id}/reject` | Bearer Token | Reject application (Releases seat if needed) |
| `POST` | `/api/applications/{id}/ai-summary` | Bearer Token | **AI Assistance**: Generate summary |

---

## 🔒 Security & Concurrency Architecture

### 1. Server-Side Authorization (Laravel Sanctum & Policies)
- Authorization is strictly enforced on the server-side via `ApplicationPolicy`:
  - **Students** can only access their own submitted applications (`403 Forbidden` if attempting to view another student's application).
  - **College Admins** can only view and approve/reject applications targeting their assigned `college_id`.
  - **System Admins** have global access.

### 2. Concurrent Seat Allocation Engine (`lockForUpdate()`)
- To prevent race conditions where two simultaneous applicants consume the last available seat in a course:
  ```php
  DB::transaction(function () use ($application) {
      // Pessimistic DB locking on course row
      $course = Course::where('id', $application->course_id)->lockForUpdate()->firstOrFail();

      if ($course->available_seats <= 0) {
          throw new DomainException('No available seats remaining.');
      }

      $course->decrement('available_seats');
      $application->update(['status' => 'APPROVED']);
  });
  ```
- This guarantees zero over-allocation under concurrent HTTP requests.

---

## 🤖 AI Service Architecture

Designed around an Interface-driven architecture (`AIServiceInterface`):
- `MockAIService`: Offline, deterministic driver returning structured summary metrics without requiring external network calls or secrets.
- `OpenAIService`: Integrates with OpenAI Chat Completions API (`OPENAI_API_KEY`). If the key is omitted or the API times out/fails, it gracefully falls back to `MockAIService` without breaking the application flow.

---

## 🧪 Automated Testing

Execute the automated test suite covering all 6 feature requirements and business unit logic:

```bash
php artisan test
```

Output:
```text
PASS Tests\Unit\SeatManagementServiceTest
✓ seat management service decrements seats atomically on approval
✓ seat management service throws exception when no seats available

PASS Tests\Feature\StudentAdmissionSystemTest
✓ student can register and login
✓ unauthorized student cannot access another students application
✓ application cannot be submitted if course has no seats
✓ duplicate application is rejected
✓ authorized admin can approve application
✓ unauthorized user cannot approve application
✓ ai application summary endpoint
```

---

## 📁 Postman Collection
Import `postman_collection.json` into Postman to test all endpoints out-of-the-box against `http://127.0.0.1:8000`.
