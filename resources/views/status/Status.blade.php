@extends('includes.layouts.super_admin')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Status Management</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body { background: #f6f8fb; font-family: Inter, Arial, sans-serif; }
        .page-header { background: linear-gradient(90deg,#0d6efd 0%, #6610f2 100%); color: white; padding: 28px; border-radius: 12px; }
        .card-rounded { border-radius: 12px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
        .form-label { font-weight: 600; font-size: .92rem; }
        #toastContainer { position: fixed; top: 20px; right: 20px; z-index: 1080; }
    </style>
</head>
<body>
<div class="container py-5">

    <!-- Header -->
    <div class="page-header mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="h3 mb-1">Status Management</h1>
            <div class="small opacity-75">Add, edit, and delete statuses.</div>
        </div>
        <img src="/images/logo.png" alt="Logo" class="rounded-circle bg-white p-1" style="width:70px;height:70px;object-fit:contain;">
    </div>

    <!-- Form Card -->
    <div class="card card-rounded p-4 mb-4">
        <h5 class="mb-3" id="formTitle">Add Status</h5>
        <form id="statusForm" class="row g-3">
            <input type="hidden" id="editId">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" id="name" class="form-control" placeholder="Enter name" required
                       oninput="this.value = this.value.toUpperCase()" style="text-transform:uppercase">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select id="status" class="form-select" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" id="submitBtn">Save</button>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card card-rounded p-4">
        <h5 class="mb-3">All Statuses</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="statusTable">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="statusTableBody">
                    <tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer"></div>

<script>
    const API_BASE = '/api/status';
    let editMode = false;

    function showToast(msg, type = 'success') {
        const id = 'toast_' + Date.now();
        const bg = type === 'success' ? 'bg-success' : 'bg-danger';
        document.getElementById('toastContainer').insertAdjacentHTML('beforeend', `
            <div id="${id}" class="toast align-items-center text-white ${bg} border-0 show mb-2" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${msg}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="document.getElementById('${id}').remove()"></button>
                </div>
            </div>`);
        setTimeout(() => { const el = document.getElementById(id); if (el) el.remove(); }, 3000);
    }

    async function loadStatuses() {
        const res = await fetch(API_BASE);
        const data = await res.json();
        const tbody = document.getElementById('statusTableBody');
        if (!data.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>';
            return;
        }
        tbody.innerHTML = data.map(row => `
            <tr>
                <td>${row.id}</td>
                <td>${row.name}</td>
                <td><span class="badge ${row.status == 1 ? 'bg-success' : 'bg-secondary'}">${row.status == 1 ? 'Active' : 'Inactive'}</span></td>
                <td>
                    <button class="btn btn-sm btn-warning me-1" onclick="editStatus(${row.id}, '${row.name.replace(/'/g,"\\'")}', ${row.status})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteStatus(${row.id})">Delete</button>
                </td>
            </tr>`).join('');
    }

    document.getElementById('statusForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const id     = document.getElementById('editId').value;
        const name   = document.getElementById('name').value.trim();
        const status = document.getElementById('status').value;

        const method  = editMode ? 'PUT' : 'POST';
        const url     = editMode ? `${API_BASE}/${id}` : API_BASE;

        const res = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ name, status })
        });

        if (res.ok) {
            showToast(editMode ? 'Updated successfully!' : 'Added successfully!');
            resetForm();
            loadStatuses();
        } else {
            const err = await res.json();
            showToast(err.message || 'Something went wrong.', 'danger');
        }
    });

    function editStatus(id, name, status) {
        editMode = true;
        document.getElementById('editId').value = id;
        document.getElementById('name').value   = name;
        document.getElementById('status').value = status;
        document.getElementById('formTitle').textContent  = 'Edit Status';
        document.getElementById('submitBtn').textContent  = 'Update';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function deleteStatus(id) {
        if (!confirm('Are you sure you want to delete this record?')) return;
        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        if (res.ok) { showToast('Deleted successfully!'); loadStatuses(); }
        else showToast('Delete failed.', 'danger');
    }

    function resetForm() {
        editMode = false;
        document.getElementById('statusForm').reset();
        document.getElementById('editId').value = '';
        document.getElementById('formTitle').textContent = 'Add Status';
        document.getElementById('submitBtn').textContent = 'Save';
    }

    loadStatuses();
</script>
</body>
</html>
@endsection
