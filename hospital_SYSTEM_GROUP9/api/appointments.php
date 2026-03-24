<?php
// api/appointments.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../helpers/response.php';

$appointment = new Appointment();
$method      = $_SERVER['REQUEST_METHOD'];
$id          = isset($_GET['id'])         ? (int)$_GET['id']         : null;
$patientId   = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : null;
$doctorId    = isset($_GET['doctor_id'])  ? (int)$_GET['doctor_id']  : null;

switch ($method) {

    case 'GET':
        if ($id) {
            $result = $appointment->getById($id);
            if ($result) {
                sendResponse(200, true, 'Appointment retrieved.', $result);
            } else {
                sendResponse(404, false, 'Appointment not found.');
            }
        } elseif ($patientId) {
            // filter by patient
            $result = $appointment->getByPatient($patientId);
            sendResponse(200, true, "Appointments for patient #{$patientId}.", $result);
        } elseif ($doctorId) {
            // filter by doctor
            $result = $appointment->getByDoctor($doctorId);
            sendResponse(200, true, "Appointments for doctor #{$doctorId}.", $result);
        } else {
            $result = $appointment->getAll();
            sendResponse(200, true, 'Appointments retrieved.', $result);
        }
        break;

    case 'POST':
        $data = getRequestBody();
        if (empty($data['patient_id']) || empty($data['doctor_id']) ||
            empty($data['appointment_date']) || empty($data['appointment_time'])) {
            sendResponse(400, false, 'patient_id, doctor_id, appointment_date, and appointment_time are required.');
        }
        $result = $appointment->create($data);
        sendResponse(201, true, 'Appointment created.', $result);
        break;

    case 'PUT':
        if (!$id) sendResponse(400, false, 'Appointment ID is required.');
        $data = getRequestBody();
        if (empty($data['patient_id']) || empty($data['doctor_id']) ||
            empty($data['appointment_date']) || empty($data['appointment_time'])) {
            sendResponse(400, false, 'patient_id, doctor_id, appointment_date, and appointment_time are required.');
        }
        $result = $appointment->update($id, $data);
        if ($result) {
            sendResponse(200, true, 'Appointment updated.', $result);
        } else {
            sendResponse(404, false, 'Appointment not found.');
        }
        break;

    case 'DELETE':
        if (!$id) sendResponse(400, false, 'Appointment ID is required.');
        $deleted = $appointment->delete($id);
        if ($deleted) {
            sendResponse(200, true, 'Appointment deleted.');
        } else {
            sendResponse(404, false, 'Appointment not found.');
        }
        break;

    default:
        sendResponse(405, false, 'Method not allowed.');
}
