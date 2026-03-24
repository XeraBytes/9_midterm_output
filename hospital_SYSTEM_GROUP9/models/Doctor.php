<?php
// models/Doctor.php

require_once __DIR__ . '/../config/Database.php';

class Doctor {
    private PDO $db;

    public function __construct() {
        $database  = new Database();
        $this->db  = $database->connect();
    }

    // add a new doctor
    public function create(array $data): array {
        $sql = "INSERT INTO doctors (first_name, last_name, specialization, contact_no, email, available_days)
                VALUES (:first_name, :last_name, :specialization, :contact_no, :email, :available_days)
                RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':first_name'      => $data['first_name'],
            ':last_name'       => $data['last_name'],
            ':specialization'  => $data['specialization'],
            ':contact_no'      => $data['contact_no']    ?? null,
            ':email'           => $data['email']          ?? null,
            ':available_days'  => $data['available_days'] ?? null,
        ]);
        return $stmt->fetch();
    }

    // get all doctors
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM doctors ORDER BY doctor_id ASC");
        return $stmt->fetchAll();
    }

    // get one doctor by id
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM doctors WHERE doctor_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // update doctor info
    public function update(int $id, array $data): array|false {
        $sql = "UPDATE doctors
                SET first_name     = :first_name,
                    last_name      = :last_name,
                    specialization = :specialization,
                    contact_no     = :contact_no,
                    email          = :email,
                    available_days = :available_days
                WHERE doctor_id = :id
                RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':first_name'      => $data['first_name'],
            ':last_name'       => $data['last_name'],
            ':specialization'  => $data['specialization'],
            ':contact_no'      => $data['contact_no']    ?? null,
            ':email'           => $data['email']          ?? null,
            ':available_days'  => $data['available_days'] ?? null,
            ':id'              => $id,
        ]);
        return $stmt->fetch();
    }

    // delete a doctor
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM doctors WHERE doctor_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // how many total appointments each doctor has
    public function appointmentsPerDoctor(): array {
        $stmt = $this->db->query("SELECT * FROM view_appointments_per_doctor");
        return $stmt->fetchAll();
    }

    // pending + confirmed appointments only (active workload)
    public function workload(): array {
        $stmt = $this->db->query("SELECT * FROM view_doctor_workload");
        return $stmt->fetchAll();
    }
}
