# Android Attendance System

A PHP web application for managing student attendance, designed to work alongside an Android app for teachers to mark attendance.

## Setup

1. **Start XAMPP** – ensure Apache and MySQL are running.
2. **Import the database** – run `database.sql` via phpMyAdmin or CLI:
   ```
   mysql -u root < database.sql
   ```
3. **Open in browser** – navigate to `http://localhost/php-android-attendance-system/`.

### Default Accounts

| Username   | Password     | Role    |
|------------|-------------|---------|
| admin      | admin123    | Admin   |
| parent1    | parent123   | Parent  |
| teacher1   | teacher123  | Teacher |

## Architecture

Lightweight MVC pattern preserving the existing URL structure.

```
app/
  config/      – database credentials
  core/        – Auth, Database, View helpers
  models/      – ClassModel, Teacher, Student, Attendance, Feedback, User
  controllers/ – AdminController, LoginController, ParentController
  views/
    layouts/   – shared HTML layouts (admin, parent)
    admin/     – admin page templates
    parent/    – parent page templates
    auth/      – login template
```

Route entry files (`index.php`, `login/`, `admin/*.php`, `parent/*.php`) are thin endpoints that call controllers.

## Features

### Admin Panel (`/admin/`)
- **Add Teacher** – create teacher accounts with class assignment
- **Add Class** – manage class/section records
- **Add Student** – register students linked to classes and parents
- **View/Edit** – tabbed interface to edit teachers, classes, students
- **Reports** – attendance reports filtered by class and date range
- **Feedback** – read feedback submitted by parents

### Parent Panel (`/parent/`)
- **Dashboard** – view linked children
- **Add Feedback** – submit feedback to school
- **Student Report** – attendance statistics with date filtering

### JSON API (`/api/`)
- `POST /api/login.php` – web login; add `api=1` or `Accept: application/json` for JSON response
- `GET/POST /api/attendance.php` – get or mark attendance (teacher/admin auth)
- `GET /api/students.php` – list classes or students by class (authenticated)

## Flow

1. Browser/app hits an entry route.
2. Route loads `app/bootstrap.php` and calls the correct controller.
3. Controller uses models and core services.
4. Controller renders a view (with layout) or returns JSON.
