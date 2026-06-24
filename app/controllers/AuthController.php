<?php

declare(strict_types=1);

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth.login', [], 'Sign in');
    }

    public function login(): void
    {
        $email    = $this->input('email');
        $password = $this->input('password');

        if ($email === '' || $password === '') {
            flash('error', 'Please enter both email and password.');
            flash_old(['email' => $email]);
            redirect('/login');
        }

        $user = Auth::attempt($email, $password);
        if (!$user) {
            flash('error', 'Invalid credentials. Please try again.');
            flash_old(['email' => $email]);
            redirect('/login');
        }

        clear_old();
        flash('success', 'Welcome back, ' . $user['name'] . '!');
        redirect('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/login');
    }
}
