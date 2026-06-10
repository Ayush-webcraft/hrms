-- =====================================================================
--  HRMS — SQLite schema (zero-config default)
--  Loaded automatically by:  php database/migrate.php
-- =====================================================================

CREATE TABLE IF NOT EXISTS users (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name        TEXT NOT NULL,
    email       TEXT NOT NULL UNIQUE,
    password    TEXT NOT NULL,
    role        TEXT NOT NULL DEFAULT 'employee' CHECK (role IN ('admin','employee')),
    employee_id INTEGER NULL,
    created_at  TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_code TEXT NOT NULL UNIQUE,
    name          TEXT NOT NULL,
    email         TEXT NOT NULL UNIQUE,
    phone         TEXT,
    department    TEXT,
    designation   TEXT,
    joining_date  TEXT,
    basic_salary  REAL NOT NULL DEFAULT 0,
    status        TEXT NOT NULL DEFAULT 'active' CHECK (status IN ('active','inactive')),
    created_at    TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS attendance (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id     INTEGER NOT NULL,
    attendance_date TEXT NOT NULL,
    check_in        TEXT NULL,
    check_out       TEXT NULL,
    work_hours      REAL DEFAULT 0,
    overtime_hours  REAL DEFAULT 0,
    status          TEXT NOT NULL DEFAULT 'Present' CHECK (status IN ('Present','Absent','Leave','Half-Day')),
    created_at      TEXT DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (employee_id, attendance_date),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS leaves (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id INTEGER NOT NULL,
    leave_type  TEXT NOT NULL,
    start_date  TEXT NOT NULL,
    end_date    TEXT NOT NULL,
    days        INTEGER NOT NULL DEFAULT 1,
    reason      TEXT,
    status      TEXT NOT NULL DEFAULT 'Pending' CHECK (status IN ('Pending','Approved','Rejected')),
    reviewed_by INTEGER NULL,
    applied_at  TEXT DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TEXT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS salaries (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id     INTEGER NOT NULL,
    month           TEXT NOT NULL,
    working_days    INTEGER NOT NULL,
    present_days    REAL NOT NULL DEFAULT 0,
    absent_days     REAL NOT NULL DEFAULT 0,
    leave_days      REAL NOT NULL DEFAULT 0,
    half_days       INTEGER NOT NULL DEFAULT 0,
    per_day_salary  REAL NOT NULL DEFAULT 0,
    basic_salary    REAL NOT NULL DEFAULT 0,
    overtime_hours  REAL NOT NULL DEFAULT 0,
    overtime_amount REAL NOT NULL DEFAULT 0,
    deductions      REAL NOT NULL DEFAULT 0,
    net_salary      REAL NOT NULL DEFAULT 0,
    generated_at    TEXT DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (employee_id, month),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);
