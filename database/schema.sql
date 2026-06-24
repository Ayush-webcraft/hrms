-- =====================================================================
--  HRMS — MySQL schema
--  Used when DB_DRIVER=mysql. Run via:  php database/migrate.php
--  (migrate.php creates the database, loads this file, then seeds data.)
-- =====================================================================

CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('admin','employee') NOT NULL DEFAULT 'employee',
    employee_id INT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS employees (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(20)  NOT NULL UNIQUE,
    name          VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    phone         VARCHAR(20),
    department    VARCHAR(50),
    designation   VARCHAR(50),
    joining_date  DATE,
    basic_salary  DECIMAL(10,2) NOT NULL DEFAULT 0,
    status        ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attendance (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    employee_id     INT NOT NULL,
    attendance_date DATE NOT NULL,
    check_in        TIME NULL,
    check_out       TIME NULL,
    work_hours      DECIMAL(5,2) DEFAULT 0,
    overtime_hours  DECIMAL(5,2) DEFAULT 0,
    status          ENUM('Present','Absent','Leave','Half-Day') NOT NULL DEFAULT 'Present',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_emp_date (employee_id, attendance_date),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS leaves (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    leave_type  VARCHAR(30) NOT NULL,
    start_date  DATE NOT NULL,
    end_date    DATE NOT NULL,
    days        INT NOT NULL DEFAULT 1,
    reason      VARCHAR(255),
    status      ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    reviewed_by INT NULL,
    applied_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS salaries (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    employee_id     INT NOT NULL,
    month           VARCHAR(7) NOT NULL,           -- YYYY-MM
    working_days    INT NOT NULL,
    present_days    DECIMAL(5,1) NOT NULL DEFAULT 0,
    absent_days     DECIMAL(5,1) NOT NULL DEFAULT 0,
    leave_days      DECIMAL(5,1) NOT NULL DEFAULT 0,
    half_days       INT NOT NULL DEFAULT 0,
    per_day_salary  DECIMAL(10,2) NOT NULL DEFAULT 0,
    basic_salary    DECIMAL(10,2) NOT NULL DEFAULT 0,
    overtime_hours  DECIMAL(6,2) NOT NULL DEFAULT 0,
    overtime_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    deductions      DECIMAL(10,2) NOT NULL DEFAULT 0,
    net_salary      DECIMAL(10,2) NOT NULL DEFAULT 0,
    generated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_emp_month (employee_id, month),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
