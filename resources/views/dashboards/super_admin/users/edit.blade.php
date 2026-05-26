@extends('includes.layouts.super_admin')

@section('title', 'Super Admin Dashboard | Edit User')

@section('content')
<div class="container-fluid px-0" style="max-width: 800px; margin: 0 auto;">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1">Edit User Account</h2>
            <p class="text-muted mb-0">Update account credentials, roles, and password options for {{ $user->name }}.</p>
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
        <form action="{{ route('dashboard.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold text-dark">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control bg-light border-0 @error('name') is-invalid @enderror" placeholder="Full Name" required>
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
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control bg-light border-0 @error('email') is-invalid @enderror" placeholder="Email Address" required>
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
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Encryption Status (Database representation) -->
                <div class="col-md-12 mt-4">
                    <label class="form-label fw-semibold text-dark">Current Password Hash (Database representation)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-shield-lock-fill text-danger"></i></span>
                        <input type="text" class="form-control bg-light border-0 text-muted" value="{{ $user->password }}" readonly style="font-family: monospace; font-size: 0.85rem;">
                    </div>
                    <div class="form-text text-danger mt-1 small">
                        <i class="bi bi-info-circle-fill"></i> Passwords are encrypted using one-way bcrypt hashing. For security reasons, the plain-text password is never stored or visible. If the user forgot their password, please enter a new one in the fields below to overwrite it.
                    </div>
                </div>

                <div class="col-md-12 my-3">
                    <hr class="text-muted">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-key-fill me-1"></i> Reset / Change Password</h5>
                    <p class="text-muted small mb-0">Fill in the fields below only if you want to assign a new password for this user. Leave empty to keep current password.</p>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold text-dark">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-key"></i></span>
                        <input type="password" name="password" id="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" placeholder="Min. 8 characters">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-key-fill"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-0" placeholder="Re-enter password">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-md-12 mt-5">
                    <button type="submit" class="btn btn-premium w-100 py-3 rounded-3 fw-bold fs-6">
                        <i class="bi bi-check-circle me-1"></i> Save Changes & Update Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
