@extends('includes.layouts.super_admin')

@section('title', 'Super Admin Dashboard | Create User')

@section('content')
<div class="container-fluid px-0" style="max-width: 800px; margin: 0 auto;">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1">Create User Account</h2>
            <p class="text-muted mb-0">Register a new administrator or manager to access the backend dashboards.</p>
        </div>
        <div>
            <a href="{{ route('dashboard.users.index') }}" class="btn btn-light d-flex align-items-center gap-2 border-0 shadow-sm px-3 py-2 rounded-3">
                <i class="bi bi-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm glass-card bg-danger bg-opacity-10 text-danger p-3 rounded-4 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Section -->
    <div class="glass-card p-5 bg-white border-0 shadow-lg">
        <form action="{{ route('dashboard.users.store') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <!-- Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold text-dark">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control bg-light border-0 @error('name') is-invalid @enderror" placeholder="e.g. Aditya Acharya" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold text-dark">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control bg-light border-0 @error('email') is-invalid @enderror" placeholder="e.g. admin@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Role -->
                <div class="col-md-12">
                    <label for="role" class="form-label fw-semibold text-dark">Access Role</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-shield-lock"></i></span>
                        <select name="role" id="role" class="form-select bg-light border-0 @error('role') is-invalid @enderror" required>
                            <option value="" disabled selected>Select Access Role...</option>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text text-muted small mt-1">This role controls which dashboard section the user can view and edit.</div>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold text-dark">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-key"></i></span>
                        <input type="password" name="password" id="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" placeholder="Min. 8 characters" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-key-fill"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-0" placeholder="Re-enter password" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-md-12 mt-5">
                    <button type="submit" class="btn btn-premium w-100 py-3 rounded-3 fw-bold fs-6">
                        <i class="bi bi-check-circle me-1"></i> Register User Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
