Create Database clinic_system;

use clinic_system;

CREATE TABLE patient_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    registration_date DATE NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    height DECIMAL(5,2) NULL,
    weight DECIMAL(5,2) NULL,
    temperature DECIMAL(4,1) NULL,
    blood_pressure VARCHAR(20) NULL,
    blood_pressure_category VARCHAR(20) NULL,
    check_up_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);