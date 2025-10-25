@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow-sm border-0" style="width: 380px;">
        {{-- Header --}}
        <div class="card-header text-center bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-building me-2"></i>KantorApp Login
            </h5>
        </div>

        {{-- Body --}}
        <div class="card-body p-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Remember Me</label>
                </div>

                {{-- Submit Button --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-semibold">
                        <i class="fas fa-right-to-bracket me-2"></i>Login
                    </button>
                </div>

                {{-- Forgot Password --}}
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-none small">
                        Forgot Your Password?
                    </a>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="card-footer text-center text-muted small bg-white py-2">
            © KantorApp {{ date('Y') }}
        </div>
    </div>
</div>
@endsection
