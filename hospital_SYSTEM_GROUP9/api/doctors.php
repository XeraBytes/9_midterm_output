<?php
// api/doctors.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../helpers/response.php';

$doctor = new Doctor();
$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {

    case 'GET':
        if ($id) {
            $result = $doctor->getById($id);
            if ($result) {
                sendResponse(200, true, 'Doctor retrieved.', $result);
            } else {
                sendResponse(404, false, 'Doctor not found.');
            }
        } else {
            $result = $doctor->getAll();
            sendResponse(200, true, 'Doctors retrieved.', $result);
        }
        break;

    case 'POST':
        $data = getRequestBody();
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['specialization'])) {
            sendResponse(400, false, 'first_name, last_name, and specialization are required.');
        }
        $result = $doctor->create($data);
        sendResponse(201, true, 'Doctor created.', $result);
        break;

    case 'PUT':
        if (!$id) sendResponse(400, false, 'Doctor ID is required.');
        $data = getRequestBody();
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['specialization'])) {
            sendResponse(400, false, 'first_name, last_name, and specialization are required.');
        }
        $result = $doctor->update($id, $data);
        if ($result) {
            sendResponse(200, true, 'Doctor updated.', $result);
        } else {
            sendResponse(404, false, 'Doctor not found.');
        }
        break;

    case 'DELETE':
        if (!$id) sendResponse(400, false, 'Doctor ID is required.');
        $deleted = $doctor->delete($id);
        if ($deleted) {
            sendResponse(200, true, 'Doctor deleted.');
        } else {
            sendResponse(404, false, 'Doctor not found.');
        }
        break;

    default:
        sendResponse(405, false, 'Method not allowed.');
}
