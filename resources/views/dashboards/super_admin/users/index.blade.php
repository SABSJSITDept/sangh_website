@extends('includes.layouts.super_admin')

@section('title', 'Super Admin Dashboard | User Management')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold mb-1">User Management</h2>
            <p class="text-muted mb-0">Create, monitor, and manage system users and dashboard access roles.</p>
        </div>
        <div>
            <a href="{{ route('dashboard.users.create') }}" class="btn btn-premium d-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill fs-5"></i>
                <span>Add New User</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm glass-card bg-success bg-opacity-10 text-success p-3 rounded-4 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm glass-card bg-danger bg-opacity-10 text-danger p-3 rounded-4 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="glass-card p-4 mb-4">
        <form action="{{ route('dashboard.users.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-9">
                <label for="search" class="form-label small fw-bold text-muted text-uppercase">Search Users</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control bg-light border-0" placeholder="Search by name, email, or role...">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-premium w-100 py-2">Filter</button>
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-light w-100 py-2 border-0"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="glass-card p-0 overflow-hidden mb-4 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase fs-7 text-muted fw-bold">User Information</th>
                        <th class="py-3 text-uppercase fs-7 text-muted fw-bold">Email</th>
                        <th class="py-3 text-uppercase fs-7 text-muted fw-bold">Assigned Role</th>
                        <th class="py-3 text-uppercase fs-7 text-muted fw-bold">Registered Date</th>
                        <th class="pe-4 py-3 text-center text-uppercase fs-7 text-muted fw-bold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle-sm bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px; font-size: 0.95rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $user->name }}</span>
                                        <span class="d-block small text-muted">ID: <code>#{{ $user->id }}</code></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $user->email }}</span>
                            </td>
                            <td>
                                @php
                                    $roleBadgeClass = 'bg-secondary text-secondary';
                                    if ($user->role === 'super_admin') {
                                        $roleBadgeClass = 'bg-danger text-danger border-danger';
                                    } elseif ($user->role === 'app_user') {
                                        $roleBadgeClass = 'bg-info text-info border-info';
                                    } elseif (in_array($user->role, ['shree_sangh', 'yuva_sangh', 'mahila_samiti'])) {
                                        $roleBadgeClass = 'bg-primary text-primary border-primary';
                                    } elseif (in_array($user->role, ['sahitya', 'sahitya_publication'])) {
                                        $roleBadgeClass = 'bg-warning text-warning border-warning';
                                    }
                                @endphp
                                <span class="badge rounded-pill bg-opacity-10 border {{ $roleBadgeClass }} px-3 py-2 small">
                                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="d-block fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                    <span class="d-block text-muted">{{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="pe-4 text-center">
                                <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary border-2 px-3 rounded-pill me-1">
                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('dashboard.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? All their audit log entries will remain but their account will be permanently deactivated.');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-2 px-3 rounded-pill">
                                            <i class="bi bi-trash-fill me-1"></i> Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border border-light px-3 py-2 small">
                                        <i class="bi bi-shield-check"></i> Current User
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-2 d-block mb-2"></i>
                                No users found in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light bg-opacity-25">
                <div class="small text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .fs-7 {
        font-size: 0.75rem;
    }
    .btn-outline-danger {
        transition: all 0.2s ease-in-out;
    }
    .btn-outline-danger:hover {
        transform: scale(1.05);
    }
</style>
@endsection
