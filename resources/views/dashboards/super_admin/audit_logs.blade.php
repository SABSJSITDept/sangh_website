@extends('includes.layouts.super_admin')

@section('title', 'Super Admin Dashboard | Audit Logs')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold mb-1">Audit Logs</h2>
            <p class="text-muted mb-0">Track all creations, updates, and deletions across the platform.</p>
        </div>
        <div class="d-none d-md-block">
            <div class="glass-card px-4 py-2 d-flex align-items-center">
                <i class="bi bi-shield-lock me-2 text-primary"></i>
                <span class="fw-medium text-dark">System Security Logs</span>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="glass-card p-4 mb-4">
        <form action="{{ route('dashboard.audit_logs') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label small fw-bold text-muted text-uppercase">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control bg-light border-0" placeholder="User name, ID, Model, IP...">
                </div>
            </div>
            <div class="col-md-3">
                <label for="action" class="form-label small fw-bold text-muted text-uppercase">Action Type</label>
                <select name="action" id="action" class="form-select bg-light border-0">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="model_type" class="form-label small fw-bold text-muted text-uppercase">Model Type</label>
                <select name="model_type" id="model_type" class="form-select bg-light border-0">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $type)
                        @php
                            $shortName = class_basename($type);
                        @endphp
                        <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>{{ $shortName }} ({{ $type }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-premium w-100 py-2">Filter</button>
                <a href="{{ route('dashboard.audit_logs') }}" class="btn btn-light w-100 py-2 border-0"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="glass-card p-0 overflow-hidden mb-4 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase fs-7 text-muted fw-bold">User</th>
                        <th class="py-3 text-uppercase fs-7 text-muted fw-bold">Action</th>
                        <th class="py-3 text-uppercase fs-7 text-muted fw-bold">Target Model</th>
                        <th class="py-3 text-uppercase fs-7 text-muted fw-bold">IP & Device</th>
                        <th class="py-3 text-uppercase fs-7 text-muted fw-bold">Timestamp</th>
                        <th class="pe-4 py-3 text-center text-uppercase fs-7 text-muted fw-bold">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle-sm bg-amber bg-opacity-10 text-amber fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                        {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-semibold text-dark">{{ $log->user_name }}</span>
                                        <span class="d-block small text-muted">{{ $log->user ? ucwords(str_replace('_', ' ', $log->user->role)) : 'System' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($log->action === 'created')
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 small">Created</span>
                                @elseif($log->action === 'updated')
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 small">Updated</span>
                                @else
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 small">Deleted</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <span class="fw-semibold text-dark d-block">{{ class_basename($log->model_type) }}</span>
                                    <span class="text-muted small d-block">ID: <code class="bg-light px-1 py-0.5 rounded">{{ $log->model_id ?? 'N/A' }}</code></span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="d-block fw-medium text-dark"><i class="bi bi-laptop me-1"></i> {{ $log->ip_address ?? 'CLI / Local' }}</span>
                                    @if($log->user_agent)
                                        <span class="d-block text-muted text-truncate" style="max-width: 200px;" title="{{ $log->user_agent }}">{{ $log->user_agent }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="d-block fw-semibold text-dark">{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : 'N/A' }}</span>
                                    <span class="d-block text-muted">{{ $log->created_at ? $log->created_at->diffForHumans() : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="pe-4 text-center">
                                <button class="btn btn-sm btn-outline-primary border-2 px-3 rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $log->id }}" aria-expanded="false" aria-controls="details-{{ $log->id }}">
                                    <i class="bi bi-chevron-down"></i> Diff
                                </button>
                            </td>
                        </tr>
                        <!-- Collapsible Row for Details/Diff -->
                        <tr class="collapse-row-tr">
                            <td colspan="6" class="p-0 border-0">
                                <div class="collapse" id="details-{{ $log->id }}">
                                    <div class="px-4 py-3 bg-light bg-opacity-50 border-top border-bottom">
                                        <div class="row">
                                            @if($log->action === 'created')
                                                <div class="col-12">
                                                    <h6 class="fw-bold mb-3 text-success"><i class="bi bi-plus-circle-fill me-1"></i> Fields Set on Creation:</h6>
                                                    <div class="glass-card p-3 bg-white">
                                                        <div class="row g-2">
                                                            @if(is_array($log->new_values))
                                                                @foreach($log->new_values as $field => $val)
                                                                    <div class="col-md-4 col-sm-6 border-bottom pb-2">
                                                                        <span class="text-muted d-block small fw-medium">{{ $field }}</span>
                                                                        <span class="text-dark fw-bold small text-break">{{ is_array($val) ? json_encode($val) : ($val ?? 'NULL') }}</span>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted italic small">No field data logged.</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($log->action === 'deleted')
                                                <div class="col-12">
                                                    <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-trash-fill me-1"></i> Deleted Record Attributes:</h6>
                                                    <div class="glass-card p-3 bg-white">
                                                        <div class="row g-2">
                                                            @if(is_array($log->old_values))
                                                                @foreach($log->old_values as $field => $val)
                                                                    <div class="col-md-4 col-sm-6 border-bottom pb-2">
                                                                        <span class="text-muted d-block small fw-medium">{{ $field }}</span>
                                                                        <span class="text-dark fw-bold small text-break">{{ is_array($val) ? json_encode($val) : ($val ?? 'NULL') }}</span>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted italic small">No field data logged.</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($log->action === 'updated')
                                                <div class="col-12">
                                                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-pencil-fill me-1"></i> Field Changes:</h6>
                                                    <div class="table-responsive glass-card bg-white p-2">
                                                        <table class="table table-bordered table-sm align-middle mb-0 small">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 20%;">Field Name</th>
                                                                    <th style="width: 40%;">Original / Old Value</th>
                                                                    <th style="width: 40%;">Updated / New Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(is_array($log->old_values))
                                                                    @foreach($log->old_values as $field => $oldVal)
                                                                        @php
                                                                            $newVal = $log->new_values[$field] ?? null;
                                                                        @endphp
                                                                        <tr>
                                                                            <td class="fw-bold text-muted">{{ $field }}</td>
                                                                            <td class="bg-danger bg-opacity-10 text-danger text-decoration-line-through text-break">{{ is_array($oldVal) ? json_encode($oldVal) : ($oldVal ?? 'NULL') }}</td>
                                                                            <td class="bg-success bg-opacity-10 text-success fw-bold text-break">{{ is_array($newVal) ? json_encode($newVal) : ($newVal ?? 'NULL') }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="3" class="text-muted italic text-center">No field changes captured.</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No audit logs found matching the filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($logs->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light bg-opacity-25">
                <div class="small text-muted">
                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} entries
                </div>
                <div>
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .fs-7 {
        font-size: 0.75rem;
    }
    .collapse-row-tr {
        background-color: transparent !important;
    }
    .collapse-row-tr:hover {
        background-color: transparent !important;
    }
    /* Simple hover animations and active transitions */
    .btn-outline-primary {
        transition: all 0.2s ease-in-out;
    }
    .btn-outline-primary:hover {
        transform: scale(1.05);
    }
</style>
@endsection
