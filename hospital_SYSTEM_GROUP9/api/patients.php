<?php
// api/patients.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../helpers/response.php';

$patient = new Patient();
$method  = $_SERVER['REQUEST_METHOD'];
$id      = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {

    case 'GET':
        if ($id) {
            $result = $patient->getById($id);
            if ($result) {
                sendResponse(200, true, 'Patient retrieved.', $result);
            } else {
                sendResponse(404, false, 'Patient not found.');
            }
        } else {
            $result = $patient->getAll();
            sendResponse(200, true, 'Patients retrieved.', $result);
        }
        break;

    case 'POST':
        $data = getRequestBody();
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['date_of_birth'])) {
            sendResponse(400, false, 'first_name, last_name, and date_of_birth are required.');
        }
        $result = $patient->create($data);
        sendResponse(201, true, 'Patient created.', $result);
        break;

    case 'PUT':
        if (!$id) sendResponse(400, false, 'Patient ID is required.');
        $data = getRequestBody();
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['date_of_birth'])) {
            sendResponse(400, false, 'first_name, last_name, and date_of_birth are required.');
        }
        $result = $patient->update($id, $data);
        if ($result) {
            sendResponse(200, true, 'Patient updated.', $result);
        } else {
            sendResponse(404, false, 'Patient not found.');
        }
        break;

    case 'DELETE':
        if (!$id) sendResponse(400, false, 'Patient ID is required.');
        $deleted = $patient->delete($id);
        if ($deleted) {
            sendResponse(200, true, 'Patient deleted.');
        } else {
            sendResponse(404, false, 'Patient not found.');
        }
        break;

    default:
        sendResponse(405, false, 'Method not allowed.');
}
