-- ============================================================
-- Hospital Appointment System - Database Schema
-- Group 9 | City College of Calamba
-- Department of Computing and Informatics
-- ============================================================

-- Drop tables if they exist (for re-running the script)
DROP TABLE IF EXISTS appointments CASCADE;
DROP TABLE IF EXISTS doctors CASCADE;
DROP TABLE IF EXISTS patients CASCADE;

-- ============================================================
-- TABLE: patients
-- ============================================================
CREATE TABLE patients (
    patient_id   SERIAL PRIMARY KEY,
    first_name   VARCHAR(100) NOT NULL,
    last_name    VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender       VARCHAR(10) CHECK (gender IN ('Male', 'Female', 'Other')),
    contact_no   VARCHAR(20),
    email        VARCHAR(150) UNIQUE,
    address      TEXT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: doctors
-- ============================================================
CREATE TABLE doctors (
    doctor_id    SERIAL PRIMARY KEY,
    first_name   VARCHAR(100) NOT NULL,
    last_name    VARCHAR(100) NOT NULL,
    specialization VARCHAR(150) NOT NULL,
    contact_no   VARCHAR(20),
    email        VARCHAR(150) UNIQUE,
    available_days VARCHAR(100),  -- e.g., 'Mon,Wed,Fri'
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: appointments
-- ============================================================
CREATE TABLE appointments (
    appointment_id   SERIAL PRIMARY KEY,
    patient_id       INT NOT NULL,
    doctor_id        INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status           VARCHAR(20) DEFAULT 'Pending' CHECK (status IN ('Pending', 'Confirmed', 'Completed', 'Cancelled')),
    reason           TEXT,
    notes            TEXT,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_patient FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    CONSTRAINT fk_doctor  FOREIGN KEY (doctor_id)  REFERENCES doctors(doctor_id)  ON DELETE CASCADE
);

-- ============================================================
-- SAMPLE DATA: patients
-- ============================================================
INSERT INTO patients (first_name, last_name, date_of_birth, gender, contact_no, email, address) VALUES
('Juan',    'Dela Cruz',  '1990-05-12', 'Male',   '09171234567', 'juan.delacruz@email.com',  'Calamba, Laguna'),
('Maria',   'Santos',     '1985-08-22', 'Female', '09281234567', 'maria.santos@email.com',   'Los Banos, Laguna'),
('Pedro',   'Reyes',      '2000-03-17', 'Male',   '09391234567', 'pedro.reyes@email.com',    'Cabuyao, Laguna'),
('Ana',     'Garcia',     '1995-11-30', 'Female', '09451234567', 'ana.garcia@email.com',     'Binan, Laguna'),
('Jose',    'Mendoza',    '1978-07-04', 'Male',   '09561234567', 'jose.mendoza@email.com',   'Santa Rosa, Laguna');

-- ============================================================
-- SAMPLE DATA: doctors
-- ============================================================
INSERT INTO doctors (first_name, last_name, specialization, contact_no, email, available_days) VALUES
('Dr. Ramon',  'Aquino',   'Cardiologist',      '09171110001', 'r.aquino@hospital.com',   'Mon,Wed,Fri'),
('Dr. Clara',  'Bautista', 'Dermatologist',     '09172220002', 'c.bautista@hospital.com', 'Tue,Thu'),
('Dr. Miguel', 'Cruz',     'General Physician', '09173330003', 'm.cruz@hospital.com',     'Mon,Tue,Wed,Thu,Fri'),
('Dr. Lisa',   'Delos',    'Pediatrician',      '09174440004', 'l.delos@hospital.com',    'Mon,Thu'),
('Dr. Hanna',   'Burgos', 'Orthopedist',       '09175550005', 'hlburgos@ccc.edu.ph', 'Wed,Fri');

-- ============================================================
-- SAMPLE DATA: appointments
-- ============================================================
INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status, reason) VALUES
(1, 1, '2025-04-01', '09:00', 'Confirmed',  'Chest pain consultation'),
(2, 3, '2025-04-01', '10:00', 'Completed',  'Routine check-up'),
(3, 2, '2025-04-02', '14:00', 'Pending',    'Skin rash evaluation'),
(4, 4, '2025-04-02', '11:00', 'Confirmed',  'Child vaccination'),
(5, 5, '2025-04-03', '15:00', 'Cancelled',  'Knee pain'),
(1, 3, '2025-04-03', '08:00', 'Completed',  'Follow-up check-up'),
(2, 1, '2025-04-04', '09:30', 'Pending',    'Blood pressure check'),
(3, 5, '2025-04-04', '13:00', 'Confirmed',  'Back pain evaluation'),
(4, 2, '2025-04-05', '10:30', 'Completed',  'Acne treatment'),
(5, 3, '2025-04-05', '08:30', 'Confirmed',  'Annual physical exam');

-- ============================================================
-- ANALYTICS VIEWS (optional but useful)
-- ============================================================

-- Appointments per doctor
CREATE OR REPLACE VIEW view_appointments_per_doctor AS
SELECT
    d.doctor_id,
    CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
    d.specialization,
    COUNT(a.appointment_id) AS total_appointments
FROM doctors d
LEFT JOIN appointments a ON d.doctor_id = a.doctor_id
GROUP BY d.doctor_id, d.first_name, d.last_name, d.specialization
ORDER BY total_appointments DESC;

-- Doctor workload (only confirmed/pending)
CREATE OR REPLACE VIEW view_doctor_workload AS
SELECT
    d.doctor_id,
    CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
    d.specialization,
    COUNT(a.appointment_id) AS active_appointments
FROM doctors d
LEFT JOIN appointments a ON d.doctor_id = a.doctor_id
    AND a.status IN ('Pending', 'Confirmed')
GROUP BY d.doctor_id, d.first_name, d.last_name, d.specialization
ORDER BY active_appointments DESC;

-- Appointments per day
CREATE OR REPLACE VIEW view_appointments_per_day AS
SELECT
    appointment_date,
    COUNT(*) AS total_appointments
FROM appointments
GROUP BY appointment_date
ORDER BY appointment_date ASC;
