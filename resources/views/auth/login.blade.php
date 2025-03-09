
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Login</h1>

    <!-- Login Form -->
    <form id="login-form">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            <div id="email-error" class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye" id="eye-icon"></i>
                </button>
                <div id="password-error" class="invalid-feedback"></div>
            </div>
        </div>
        <div id="login-error" class="alert alert-danger d-none"></div>
        <button type="submit" class="btn btn-primary w-100" id="loginButton">Login</button>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<script>
    document.getElementById('login-form').addEventListener('submit', async function(event) {
        event.preventDefault();

        // Reset previous errors
        const errorDiv = document.getElementById('login-error');
        errorDiv.classList.add('d-none');
        errorDiv.textContent = '';

        // Disable button and show loading
        const loginButton = document.getElementById('loginButton');
        loginButton.disabled = true;
        loginButton.textContent = 'Logging in...';

        // Get form data
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Validate email
        if (!validateEmail(email)) {
            document.getElementById('email-error').textContent = 'Please enter a valid email address.';
            document.getElementById('email').classList.add('is-invalid');
            loginButton.disabled = false;
            loginButton.textContent = 'Login';
            return;
        } else {
            document.getElementById('email').classList.remove('is-invalid');
            document.getElementById('email-error').textContent = '';
        }

        // Login request
        try {
            const response = await fetch('/api/v1/users/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                // Save token in local storage
                localStorage.setItem('jwt_token', data.token);

                // Redirect to treatments page
                window.location.href = data.redirect_url;
            } else {
                // Display error message
                errorDiv.textContent = data.error || 'Login failed.';
                errorDiv.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Login error:', error);
            errorDiv.textContent = 'An error occurred while logging in.';
            errorDiv.classList.remove('d-none');
        } finally {
            // Re-enable button after request
            loginButton.disabled = false;
            loginButton.textContent = 'Login';
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });
</script>
@endsection
