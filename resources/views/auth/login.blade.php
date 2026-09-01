<!DOCTYPE html>
<html lang="en">
<head>
    <title>Proof of Content - Login</title>
    @include('layouts.partials.head')
</head>
<body class="login-bg">
    <div class="container d-flex justify-content-center">
        <div class="card login-card">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="badge bg-primary text-wrap mb-3 fs-5 p-2 rounded-3 shadow-sm">PoC</div>
                    <h4 class="fw-bold text-dark-custom">Proof of Content</h4>
                    <p class="text-muted">Sign in to your account</p>
                </div>
                
                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark-custom">Email address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label fw-semibold text-dark-custom">Password</label>
                            <a href="{{ url('/password/reset') }}" class="text-decoration-none small text-primary fw-medium">Forgot password?</a>
                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary toggle-password bg-light" type="button">Show</button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted" for="remember">Remember me</label>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2 fw-semibold">Sign In</button>
                    </div>
                    <div class="text-center text-muted small">
                        Don't have an account? <a href="#" class="text-primary text-decoration-none fw-medium">Contact admin</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.partials.scripts')
</body>
</html>
