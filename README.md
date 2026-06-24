# 🧑‍💼 HRMS — HR Attendance & Payroll Management System

A complete **pure-PHP** HR system: employee management, attendance (check-in/out,
leave, half-day), leave approval with balance tracking, attendance-based payroll
with overtime, printable payslips, a role-aware dashboard, and authentication
with **role-based access control** (HR Admin vs Employee).

No framework, no Composer, no build step — just PHP + PDO. Runs on **PHP's
built-in server** (zero setup with SQLite) or **XAMPP/Apache + MySQL**.

```
hrms/
├── index.php              # front controller (routes live here)
├── server.php             # router for `php -S` (built-in dev server)
├── .htaccess              # Apache rewrite + protects the DB/source
├── config/config.php      # DB + payroll/leave settings (env-overridable)
├── database/
│   ├── schema.sql         # MySQL schema (per the spec)
│   ├── schema.sqlite.sql  # SQLite schema (zero-config default)
│   └── migrate.php        # creates tables + seeds demo data
├── assets/                # CSS + a little JS
└── app/
    ├── bootstrap.php      # config, autoloader, session
    ├── core/              # Database, Router, Controller, Auth
    ├── helpers/           # e(), url(), money(), csrf, flash, ...
    ├── models/            # User, Employee, Attendance, LeaveRequest, Salary
    ├── controllers/       # Auth, Dashboard, Employee, Attendance, Leave, Payroll
    └── views/             # server-rendered PHP templates
```

## Features

| Module | What it does |
| ------ | ------------ |
| **Employees** | Add / edit / delete / search, auto employee codes, optional login account |
| **Attendance** | Self check-in/out (auto work + overtime hours), admin daily board, manual mark (Present/Absent/Leave/Half-Day), monthly report |
| **Leave** | Apply, admin approve/reject, annual quota + balance tracking; approved leave is stamped onto attendance |
| **Payroll** | One-click monthly generation from attendance, overtime pay, absence deductions, printable payslip (Save-as-PDF) |
| **Dashboard** | Admin: headcount, present/absent/on-leave today, pending leaves, monthly payroll. Employee: own status, leave balance, payslips |
| **Auth** | Session login, password hashing (`password_hash`), CSRF protection, role-based access control |

## Run it locally (fastest — SQLite, no DB server)

You need **PHP 8.0+** (`php -v`). From the `hrms/` folder:

```bash
# 1. create tables + demo data (SQLite file under database/)
php database/migrate.php

# 2. start the dev server
php -S localhost:8000 server.php
```

Open **http://localhost:8000** and log in:

| Role | Email | Password |
| ---- | ----- | -------- |
| HR Admin | `admin@hrms.test` | `password` |
| Employee | `aarav@hrms.test` | `password` |

To wipe and reseed: `php database/migrate.php --fresh`.

## Run with MySQL (matches the original spec)

1. Set the driver + credentials (environment variables override `config/config.php`):

   ```bash
   # PowerShell
   $env:DB_DRIVER="mysql"; $env:DB_USERNAME="root"; $env:DB_PASSWORD=""
   # bash
   export DB_DRIVER=mysql DB_USERNAME=root DB_PASSWORD=
   ```

2. Migrate (creates the `hrms_db` database for you) and run:

   ```bash
   php database/migrate.php
   php -S localhost:8000 server.php
   ```

## Run on XAMPP / Apache

1. Copy this `hrms/` folder into `htdocs/`.
2. Run `php database/migrate.php` once (set `DB_DRIVER=mysql` to use XAMPP's MySQL).
3. Visit **http://localhost/hrms/**. The included `.htaccess` handles routing and
   blocks direct access to the database/source files.

## Salary calculation

For each employee and month:

```
working_days = days in the month
per_day      = basic_salary / working_days
deductions   = (absent_days + 0.5 × half_days) × per_day      # approved leave is PAID
overtime_pay = overtime_hours × (per_day / 8) × 1.5
net_salary   = basic_salary − deductions + overtime_pay
```

Example (basic ₹30,000, 30-day month, 3 absences, no overtime):
`per_day = 1000`, `deduction = 3 × 1000 = 3000`, **net = ₹27,000**.

Rates are configurable in [`config/config.php`](config/config.php)
(`standard_work_hours`, `overtime_multiplier`, `annual_leave_quota`, `currency`).

## Security notes

- Passwords are hashed with `password_hash()` / verified with `password_verify()`.
- All SQL uses **parameterized PDO statements** (no string concatenation).
- Every state-changing form carries a **CSRF token**, verified on POST.
- All output is escaped via `e()` (htmlspecialchars) to prevent XSS.
- Routes are guarded by role middleware (`admin` / `employee` / `auth`).

### About JWT

The original spec listed **JWT** auth. JWT is designed for *stateless APIs*
consumed by a separate JavaScript SPA. This app is **server-rendered PHP**, where
**session-based auth + CSRF** is the idiomatic and safer choice (no token sitting
in JS, immune to token-theft via XSS). The RBAC requirement is fully met via the
role middleware. If you later split off a JS/mobile client, add a token-issuing
API layer alongside the session layer.
