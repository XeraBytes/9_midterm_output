<?php
// models/Appointment.php

require_once __DIR__ . '/../config/Database.php';

class Appointment {
    private PDO $db;

    public function __construct() {
        $database  = new Database();
        $this->db  = $database->connect();
    }

    // book a new appointment
    public function create(array $data): array {
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status, reason, notes)
                VALUES (:patient_id, :doctor_id, :appointment_date, :appointment_time, :status, :reason, :notes)
                RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':patient_id'        => $data['patient_id'],
            ':doctor_id'         => $data['doctor_id'],
            ':appointment_date'  => $data['appointment_date'],
            ':appointment_time'  => $data['appointment_time'],
            ':status'            => $data['status'] ?? 'Pending',
            ':reason'            => $data['reason'] ?? null,
            ':notes'             => $data['notes']  ?? null,
        ]);
        return $stmt->fetch();
    }

    // get all appointments, joined with patient and doctor names
    public function getAll(): array {
        $sql = "SELECT
                    a.*,
                    CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
                    CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
                    d.specialization
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                JOIN doctors  d ON a.doctor_id  = d.doctor_id
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // get a single appointment by id
    public function getById(int $id): array|false {
        $sql = "SELECT
                    a.*,
                    CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
                    CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
                    d.specialization
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                JOIN doctors  d ON a.doctor_id  = d.doctor_id
                WHERE a.appointment_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // update an existing appointment
    public function update(int $id, array $data): array|false {
        $sql = "UPDATE appointments
                SET patient_id       = :patient_id,
                    doctor_id        = :doctor_id,
                    appointment_date = :appointment_date,
                    appointment_time = :appointment_time,
                    status           = :status,
                    reason           = :reason,
                    notes            = :notes
                WHERE appointment_id = :id
                RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':patient_id'        => $data['patient_id'],
            ':doctor_id'         => $data['doctor_id'],
            ':appointment_date'  => $data['appointment_date'],
            ':appointment_time'  => $data['appointment_time'],
            ':status'            => $data['status'] ?? 'Pending',
            ':reason'            => $data['reason'] ?? null,
            ':notes'             => $data['notes']  ?? null,
            ':id'                => $id,
        ]);
        return $stmt->fetch();
    }

    // delete an appointment
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM appointments WHERE appointment_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // all appointments for a specific patient
    public function getByPatient(int $patientId): array {
        $sql = "SELECT
                    a.*,
                    CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
                    d.specialization
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.doctor_id
                WHERE a.patient_id = :patient_id
                ORDER BY a.appointment_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':patient_id' => $patientId]);
        return $stmt->fetchAll();
    }

    // all appointments assigned to a specific doctor
    public function getByDoctor(int $doctorId): array {
        $sql = "SELECT
                    a.*,
                    CONCAT(p.first_name, ' ', p.last_name) AS patient_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                WHERE a.doctor_id = :doctor_id
                ORDER BY a.appointment_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':doctor_id' => $doctorId]);
        return $stmt->fetchAll();
    }

    // count of appointments per day
    public function appointmentsPerDay(): array {
        $stmt = $this->db->query("SELECT * FROM view_appointments_per_day");
        return $stmt->fetchAll();
    }
}
