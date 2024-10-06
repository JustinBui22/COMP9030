SET @@AUTOCOMMIT = 1;

DROP DATABASE IF EXISTS COMP9030;
CREATE DATABASE COMP9030;

USE COMP9030;


CREATE user IF NOT EXISTS dbadmin@localhost;
GRANT all privileges ON COMP9030.* TO dbadmin@localhost;

CREATE TABLE account (
    username VARCHAR(100) PRIMARY KEY,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    is_logged_in BOOLEAN NOT NULL DEFAULT FALSE,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE therapists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL, -- Gender selection
    age INT NOT NULL,
    height DECIMAL(5, 2), -- Height in cm
    weight DECIMAL(5, 2), -- Weight in kg
    status VARCHAR(50) DEFAULT 'Active', -- Patient status (Active, Inactive, etc.)
    patient_group VARCHAR(50) DEFAULT 'None', -- Group or therapy session the patient belongs to
    therapist_id INT, -- Foreign key linking to therapist
    avatar_url VARCHAR(255) DEFAULT '../../common/assets/images/patient_icon.png',
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (therapist_id) REFERENCES therapists(id) ON DELETE CASCADE
);
INSERT INTO therapists (id, name, email, phone)
VALUES (1, 'Dr. Alice Smith', 'alice.smith@example.com', '123-456-7890');

INSERT INTO patients (name, gender, age, height, weight, status, patient_group, therapist_id, avatar_url)
VALUES 
('John Doe', 'Male', 32, 180, 75, 'Active', 'Group A', 1, '../../common/assets/images/patient_icon.png'),
('Jane Smith', 'Female', 28, 165, 60, 'Inactive', 'Group B', 1, '../../common/assets/images/patient_icon.png'),
('Bob Brown', 'Male', 45, 175, 85, 'Active', 'Group A', 1, '../../common/assets/images/patient_icon.png');