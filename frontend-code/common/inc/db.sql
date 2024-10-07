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
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    age INT NOT NULL,
    height DECIMAL(4,1), -- Adjust height if necessary
    weight DECIMAL(5,2), -- Weight in kg
    status ENUM('Active', 'Inactive', 'Discharged') DEFAULT 'Active',
    patient_group VARCHAR(50) DEFAULT 'None', -- Consider linking to a groups table
    therapist_id INT,
    avatar_url VARCHAR(255) DEFAULT '../../common/assets/images/patient_icon.png',
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (therapist_id) -- Adding index for performance
);
-- Inserting sample data into therapists table
INSERT INTO therapists (name, email, phone)
VALUES ('Dr. Alice Smith', 'alice.smith@example.com', '123-456-7890');

-- Inserting sample data into patients table
INSERT INTO patients (name, gender, age, height, weight, status, therapist_id, avatar_url)
VALUES 
('John Doe', 'Male', 32, 180, 75, 'Active', 1, '../../common/assets/images/patient_icon.png'),
('Jane Smith', 'Female', 28, 165, 60, 'Inactive', 1, '../../common/assets/images/patient_icon.png'),
('Bob Brown', 'Male', 45, 175, 85, 'Active', 1, '../../common/assets/images/patient_icon.png');

CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,                  
    status ENUM('Active', 'Inactive'),
    therapist_id INT,
    schedule_id INT, -- Link to schedule table
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
);


CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,                      -- Group the member belongs to
    patient_id INT,                    -- Patient ID
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserting sample groups
INSERT INTO groups (name, description, status, upcoming_schedule, member_count, therapist_id)
VALUES 
('Group A', 'This group focuses on rehabilitation and therapy.', 'Active', 'Meeting on 12th Aug, Therapy session on 15th Aug.', 15, 1),
('Group B', 'This group focuses on mental health improvement.', 'Active', 'Mindfulness session on 10th Aug, Checkup on 17th Aug.', 12, 1);

-- Inserting sample members into group_members
INSERT INTO group_members (group_id, patient_id)
VALUES
(1, 101), -- John Doe in Group A
(1, 102), -- Jane Smith in Group A
(2, 103); -- Bob Brown in Group B


CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    schedule_date DATE NOT NULL,
    description TEXT,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE journal_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_date DATE NOT NULL,
    journal_text TEXT,
    mood VARCHAR(50),
    mood_notes TEXT
);
