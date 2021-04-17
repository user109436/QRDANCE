DROP DATABASE IF EXISTS qrcode;
CREATE DATABASE IF NOT EXISTS qrcode;
USE  qrcode;
CREATE TABLE IF NOT EXISTS staffs(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fname VARCHAR(255) NOT NULL,
    mname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    tags VARCHAR(255) NOT NULL,
    about VARCHAR(255) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp
);

CREATE TABLE IF NOT EXISTS accountlist(
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    account_id INT(11) NOT NULL,
    account_type INT(2) NOT NULL,
    creator_id INT(11) NOT NULL,
    username VARCHAR(255),
    password VARCHAR(255),
    encrypted_password VARCHAR(255),
    active boolean DEFAULT true,
    email VARCHAR(255),
    date_created timestamp
);

CREATE TABLE IF NOT EXISTS subjects(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name_of_subject VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp
);
CREATE TABLE IF NOT EXISTS year(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    year INT(2) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp
);

CREATE TABLE IF NOT EXISTS sections(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    section VARCHAR(255) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp
);

CREATE TABLE IF NOT EXISTS courses(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    course_acronym VARCHAR(255) NOT NULL,
    course VARCHAR(255) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp
);

CREATE TABLE IF NOT EXISTS students(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fname VARCHAR(255) NOT NULL,
    mname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    year_id INT(11) NOT NULL,
    section_id INT(11) NOT NULL,
    course_id INT(11) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp, 
    FOREIGN KEY (year_id) REFERENCES year(id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)

);

CREATE TABLE IF NOT EXISTS subject_attendance(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    subject_id INT(11) NOT NULL,
    student_id INT(11) NOT NULL,
    year_id INT(11) NOT NULL,
    section_id INT(11) NOT NULL,
    course_id INT(11) NOT NULL,
    remarks INT(2) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (year_id) REFERENCES year(id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE IF NOT EXISTS guard_attendance(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id INT(11) NOT NULL,
    present Boolean NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE IF NOT EXISTS qr_codes(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id INT(11) NOT NULL,
    qrcode_name VARCHAR(255) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp,
    FOREIGN KEY (student_id) REFERENCES students(id)
);
CREATE TABLE IF NOT EXISTS enrolled_subjects(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id INT(11) NOT NULL,
    subject_id INT(11) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);
CREATE TABLE IF NOT EXISTS professors_subject_list(
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    subject_id INT(11) NOT NULL,
    professor_id INT(11) NOT NULL,
    creator_id INT(11) NOT NULL,
    date_created timestamp,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (professor_id) REFERENCES staffs(id)
);

CREATE TABLE IF NOT EXISTS accounts_photos(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
 account_id INT(11) NOT NULL,
file_extension VARCHAR(4) NOT NULL,
creator_id  INT(11) NOT NULL,
date_created TIMESTAMP
);
CREATE TABLE IF NOT EXISTS settings(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
attendance_today boolean NOT NULL,
domain_name VARCHAR(255) NOT NULL,
qrcode_name VARCHAR(255) NOT NULL,
pandemic boolean NOT NULL,
maintenance boolean NOT NULL,
creator_id  INT(11) NOT NULL,
date_created TIMESTAMP
);
CREATE TABLE IF NOT EXISTS notifications(
id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
account_id INT(11) NOT NULL,
account_type INT(11) NOT NULL,
subject VARCHAR(255) NOT NULL,
message VARCHAR(255) NOT NULL,
creator_id INT(11) NOT NULL,
date_created TIMESTAMP
);

CREATE TABLE IF NOT EXISTS schedules(
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id INT(11) NOT NULL,
    subject VARCHAR(225) NOT NULL, 
    purpose VARCHAR(255) NOT NULL,
    approve INT(2) NOT NULL DEFAULT 3,
    staffs_notes VARCHAR(255) NULL,
    schedule DATETIME,
    date_created DATETIME,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

INSERT INTO `staffs` (`id`, `fname`, `mname`, `lname`, `tags`, `about`, `creator_id`, `date_created`)
VALUES 
(NULL, 'Jaime', 'S.', 'Carpenter', 'Master of Arts', 'Lorem impsum Dolor ', '1', current_timestamp())
;

INSERT INTO `accountlist` (`id`, `account_id`, `account_type`, `creator_id`, `username`, `password`, `encrypted_password`, `active`, `email`, `date_created`) 
VALUES
(NULL, '1', '4', '1', 'adminQRDANCE', 'adminQRDANCE','$2y$10$2DXZEjlGrzvdVMIKt.L9eOR4m/oapv0Y0PE0bFWJtZYJuAhTferXu', '1', 'admin@gmail.com', current_timestamp());

INSERT INTO `accounts_photos` (`id`, `account_id`, `file_extension`, `creator_id`, `date_created`)
VALUES
(NULL, '1', 'jpg', '1', current_timestamp())
;

INSERT INTO `settings` (`id`, `attendance_today`, `domain_name`, `qrcode_name`, `pandemic`, `maintenance`, `creator_id`, `date_created`)
VALUES (NULL, '1', '192.168.0.10/qrcode_beta/public/log.php?id=', 'not set', '0', '0', '1', current_timestamp());