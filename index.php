<?php
/**
 * Front controller — every web request enters here.
 */

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$router = new Router();

// --- Public ----------------------------------------------------------------
$router->get('/',        'AuthController@showLogin', ['guest']);
$router->get('/login',   'AuthController@showLogin', ['guest']);
$router->post('/login',  'AuthController@login',     ['guest']);
$router->get('/logout',  'AuthController@logout');

// --- Dashboard --------------------------------------------------------------
$router->get('/dashboard', 'DashboardController@index', ['auth']);

// --- Employees (admin only) -------------------------------------------------
$router->get('/employees',            'EmployeeController@index',   ['admin']);
$router->get('/employees/create',     'EmployeeController@create',  ['admin']);
$router->post('/employees',           'EmployeeController@store',   ['admin']);
$router->get('/employees/:id',        'EmployeeController@show',    ['admin']);
$router->get('/employees/:id/edit',   'EmployeeController@edit',    ['admin']);
$router->post('/employees/:id',       'EmployeeController@update',  ['admin']);
$router->post('/employees/:id/delete','EmployeeController@destroy', ['admin']);

// --- Attendance -------------------------------------------------------------
$router->get('/attendance',          'AttendanceController@index',    ['auth']);
$router->get('/attendance/report',   'AttendanceController@report',   ['auth']);
$router->post('/attendance/checkin', 'AttendanceController@checkIn',  ['employee']);
$router->post('/attendance/checkout','AttendanceController@checkOut', ['employee']);
$router->post('/attendance/mark',    'AttendanceController@mark',     ['admin']);

// --- Leaves -----------------------------------------------------------------
$router->get('/leaves',             'LeaveController@index',  ['auth']);
$router->get('/leaves/create',      'LeaveController@create', ['employee']);
$router->post('/leaves',            'LeaveController@store',  ['employee']);
$router->post('/leaves/:id/approve','LeaveController@approve',['admin']);
$router->post('/leaves/:id/reject', 'LeaveController@reject', ['admin']);

// --- Payroll ----------------------------------------------------------------
$router->get('/payroll',            'PayrollController@index',    ['admin']);
$router->post('/payroll/generate',  'PayrollController@generate', ['admin']);
$router->get('/payslips/:id',       'PayrollController@payslip',  ['auth']);

$router->dispatch();
