<?php
/**
 * TSILIZY CORE - Authentication Controller
 */

class AuthController
{
    /**
     * Show login form
     */
    public function login(): void
    {
        // Redirect if already logged in
        if (Auth::check()) {
            redirect('dashboard');
        }

        // Handle POST request
        if (isPost()) {
            $this->handleLogin();
            return;
        }

        // Show login form
        view('auth/login', [], 'auth');
    }

    /**
     * Handle login form submission
     */
    private function handleLogin(): void
    {
        // Validate CSRF
        if (!Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            Session::flash('error', 'Invalid request. Please try again.');
            redirect('login');
        }

        // Validate input
        $validator = Validator::make()
            ->required('email', 'Email')
            ->email('email')
            ->required('password', 'Password');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('login');
        }

        $email = input('email');
        $password = input('password');
        $remember = input('remember') === 'on';

        // Check for lockout
        if ($this->isLockedOut($email)) {
            Session::flash('error', 'Too many login attempts. Please try again in 15 minutes.');
            redirect('login');
        }

        // Attempt login
        if (Auth::attempt($email, $password, $remember)) {
            clearOldInput();
            Session::flash('success', 'Welcome back!');
            redirect('dashboard');
        }

        storeOldInput();
        Session::flash('error', 'Invalid email or password.');
        redirect('login');
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        Auth::logout();
        Session::flash('success', 'You have been logged out.');
        redirect('login');
    }

    /**
     * Check if account is locked out
     */
    private function isLockedOut(string $email): bool
    {
        $attempts = Database::fetch(
            "SELECT COUNT(*) as count FROM login_attempts 
             WHERE email = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)",
            [$email, LOGIN_LOCKOUT_TIME]
        );

        return ($attempts['count'] ?? 0) >= MAX_LOGIN_ATTEMPTS;
    }
}
