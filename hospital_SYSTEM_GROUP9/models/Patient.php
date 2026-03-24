<?php
// models/Patient.php

require_once __DIR__ . '/../config/Database.php';

class Patient {
    private PDO $db;

    private int    $patient_id;
    private string $first_name;
    private string $last_name;
    private string $date_of_birth;
    private string $gender;
    private string $contact_no;
    private string $email;
    private string $address;

    public function __construct() {
        $database  = new Database();
        $this->db  = $database->connect();
    }

    // getters
    public function getId():        int    { return $this->patient_id; }
    public function getFirstName(): string { return $this->first_name; }
    public function getLastName():  string { return $this->last_name; }
    public function getEmail():     string { return $this->email; }

    // setters
    public function setFirstName(string $v):    void { $this->first_name    = $v; }
    public function setLastName(string $v):     void { $this->last_name     = $v; }
    public function setDateOfBirth(string $v):  void { $this->date_of_birth = $v; }
    public function setGender(string $v):       void { $this->gender        = $v; }
    public function setContactNo(string $v):    void { $this->contact_no    = $v; }
    public function setEmail(string $v):        void { $this->email         = $v; }
    public function setAddress(string $v):      void { $this->address       = $v; }

    // insert new patient, returns the inserted row
    public function create(array $data): array {
        $sql = "INSERT INTO patients (first_name, last_name, date_of_birth, gender, contact_no, email, address)
                VALUES (:first_name, :last_name, :date_of_birth, :gender, :contact_no, :email, :address)
                RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':first_name'    => $data['first_name'],
            ':last_name'     => $data['last_name'],
            ':date_of_birth' => $data['date_of_birth'],
            ':gender'        => $data['gender']     ?? null,
            ':contact_no'    => $data['contact_no'] ?? null,
            ':email'         => $data['email']       ?? null,
            ':address'       => $data['address']     ?? null,
        ]);
        return $stmt->fetch();
    }

    // get all patients
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM patients ORDER BY patient_id ASC");
        return $stmt->fetchAll();
    }

    // get one patient by id
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE patient_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // update patient info
    public function update(int $id, array $data): array|false {
        $sql = "UPDATE patients
                SET first_name    = :first_name,
                    last_name     = :last_name,
                    date_of_birth = :date_of_birth,
                    gender        = :gender,
                    contact_no    = :contact_no,
                    email         = :email,
                    address       = :address
                WHERE patient_id = :id
                RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':first_name'    => $data['first_name'],
            ':last_name'     => $data['last_name'],
            ':date_of_birth' => $data['date_of_birth'],
            ':gender'        => $data['gender']     ?? null,
            ':contact_no'    => $data['contact_no'] ?? null,
            ':email'         => $data['email']       ?? null,
            ':address'       => $data['address']     ?? null,
            ':id'            => $id,
        ]);
        return $stmt->fetch();
    }

    // delete a patient record
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM patients WHERE patient_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
