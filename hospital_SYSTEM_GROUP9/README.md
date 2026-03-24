# Group 9 — Hospital Appointment System
**City College of Calamba | Department of Computing and Informatics**
**Midterm Output | Web Systems and Technology**

---

## System Description

A RESTful API that manages patients, doctors, and appointments for a hospital. Built with **PHP (OOP)** and **PostgreSQL**.

---

## Project Structure

```
hospital_system/
├── config/
│   └── Database.php          # PostgreSQL connection
├── models/
│   ├── Patient.php           # Patient model (CRUD)
│   ├── Doctor.php            # Doctor model (CRUD + Analytics)
│   └── Appointment.php       # Appointment model (CRUD + Relationships + Analytics)
├── api/
│   ├── patients.php          # Patients API endpoint
│   ├── doctors.php           # Doctors API endpoint
│   ├── appointments.php      # Appointments API endpoint
│   └── analytics.php         # Analytics API endpoint
├── helpers/
│   └── response.php          # JSON response helper
├── database.sql              # Database schema + sample data
└── README.md
```

---

## Setup Instructions

### 1. Requirements
- PHP 8.1+
- PostgreSQL 13+
- A local server (XAMPP, Laragon, or PHP built-in server)

### 2. Database Setup
Open your PostgreSQL client (pgAdmin or psql) and run:
```sql
CREATE DATABASE hospital_db;
```
Then import the schema:
```bash
psql -U postgres -d hospital_db -f database.sql
```

### 3. Configure Database Credentials
Open `config/Database.php` and update:
```php
private string $host     = 'localhost';
private string $port     = '5432';
private string $dbname   = 'hospital_db';
private string $user     = 'postgres';
private string $password = 'admin123'; // <-- change this
```

### 4. Run the Server
From the project root:
```bash
php -S localhost:8000
```

---

## API Endpoints

Base URL: `http://localhost:8000/api`

---

### Patients

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/patients.php` | Get all patients |
| GET | `/patients.php?id=1` | Get patient by ID |
| POST | `/patients.php` | Create a new patient |
| PUT | `/patients.php?id=1` | Update a patient |
| DELETE | `/patients.php?id=1` | Delete a patient |

**POST / PUT Body (JSON):**
```json
{
  "first_name": "Juan",
  "last_name": "Dela Cruz",
  "date_of_birth": "1990-05-12",
  "gender": "Male",
  "contact_no": "09171234567",
  "email": "juan@email.com",
  "address": "Calamba, Laguna"
}
```

---

### Doctors

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/doctors.php` | Get all doctors |
| GET | `/doctors.php?id=1` | Get doctor by ID |
| POST | `/doctors.php` | Create a new doctor |
| PUT | `/doctors.php?id=1` | Update a doctor |
| DELETE | `/doctors.php?id=1` | Delete a doctor |

**POST / PUT Body (JSON):**
```json
{
  "first_name": "Dr. Ramon",
  "last_name": "Aquino",
  "specialization": "Cardiologist",
  "contact_no": "09171110001",
  "email": "r.aquino@hospital.com",
  "available_days": "Mon,Wed,Fri"
}
```

---

### Appointments

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/appointments.php` | Get all appointments |
| GET | `/appointments.php?id=1` | Get appointment by ID |
| GET | `/appointments.php?patient_id=1` | Get appointments by patient |
| GET | `/appointments.php?doctor_id=1` | Get appointments by doctor |
| POST | `/appointments.php` | Create a new appointment |
| PUT | `/appointments.php?id=1` | Update an appointment |
| DELETE | `/appointments.php?id=1` | Delete an appointment |

**POST / PUT Body (JSON):**
```json
{
  "patient_id": 1,
  "doctor_id": 2,
  "appointment_date": "2025-04-10",
  "appointment_time": "10:00",
  "status": "Pending",
  "reason": "Routine check-up",
  "notes": "First visit"
}
```

**Status values:** `Pending`, `Confirmed`, `Completed`, `Cancelled`

---

### Analytics

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/analytics.php` | Get all analytics |
| GET | `/analytics.php?type=appointments_per_doctor` | Total appointments per doctor |
| GET | `/analytics.php?type=doctor_workload` | Active appointments per doctor |
| GET | `/analytics.php?type=appointments_per_day` | Appointments per day |

---

## Sample Response Format

```json
{
  "success": true,
  "message": "Patients retrieved.",
  "data": [
    {
      "patient_id": 1,
      "first_name": "Juan",
      "last_name": "Dela Cruz",
      "date_of_birth": "1990-05-12",
      "gender": "Male",
      "contact_no": "09171234567",
      "email": "juan.delacruz@email.com",
      "address": "Calamba, Laguna",
      "created_at": "2025-04-01 08:00:00"
    }
  ]
}
```

---

## Member Roles and Responsibilities

| Member | Role |
|--------|------|
| Member 1 | Database Designer |
| Member 2 | Model Developer (PHP OOP) |
| Member 3 | CRUD API Developer |
| Member 4 | Relationship API Developer |
| Member 5 | Data Analytics API Developer |
| Member 6 | Documentation and Testing |

> Names and commit history per member are reflected in the repository.

---

## Repository

Repository name: `9_midterm_output`

Submitted via remote repository as required by the instructor.
