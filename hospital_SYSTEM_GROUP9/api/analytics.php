<?php
// api/analytics.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(405, false, 'Method not allowed.');
}

$type        = $_GET['type'] ?? null;
$doctor      = new Doctor();
$appointment = new Appointment();

switch ($type) {

    // total appointments per doctor (all statuses)
    case 'appointments_per_doctor':
        $result = $doctor->appointmentsPerDoctor();
        sendResponse(200, true, 'Appointments per doctor.', $result);
        break;

    // only pending/confirmed — shows current workload
    case 'doctor_workload':
        $result = $doctor->workload();
        sendResponse(200, true, 'Doctor workload.', $result);
        break;

    // how many appointments fall on each date
    case 'appointments_per_day':
        $result = $appointment->appointmentsPerDay();
        sendResponse(200, true, 'Appointments per day.', $result);
        break;

    // no type given, return all three
    default:
        $data = [
            'appointments_per_doctor' => $doctor->appointmentsPerDoctor(),
            'doctor_workload'         => $doctor->workload(),
            'appointments_per_day'    => $appointment->appointmentsPerDay(),
        ];
        sendResponse(200, true, 'All analytics.', $data);
        break;
}
