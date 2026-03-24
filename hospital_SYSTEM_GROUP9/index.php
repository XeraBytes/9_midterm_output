<?php
// index.php

header('Content-Type: application/json');

echo json_encode([
    'message' => 'Hospital Appointment System API v1.0',
    'endpoints' => [
        'GET    /api/patients.php',
        'GET    /api/patients.php?id={id}',
        'POST   /api/patients.php',
        'PUT    /api/patients.php?id={id}',
        'DELETE /api/patients.php?id={id}',

        'GET    /api/doctors.php',
        'GET    /api/doctors.php?id={id}',
        'POST   /api/doctors.php',
        'PUT    /api/doctors.php?id={id}',
        'DELETE /api/doctors.php?id={id}',

        'GET    /api/appointments.php',
        'GET    /api/appointments.php?id={id}',
        'GET    /api/appointments.php?patient_id={id}',
        'GET    /api/appointments.php?doctor_id={id}',
        'POST   /api/appointments.php',
        'PUT    /api/appointments.php?id={id}',
        'DELETE /api/appointments.php?id={id}',

        'GET    /api/analytics.php',
        'GET    /api/analytics.php?type=appointments_per_doctor',
        'GET    /api/analytics.php?type=doctor_workload',
        'GET    /api/analytics.php?type=appointments_per_day',
    ]
]);
