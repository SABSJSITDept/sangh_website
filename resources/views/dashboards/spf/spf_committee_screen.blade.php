@extends('includes.layouts.spf')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>SPF Committee Management</span>
                    <button class="btn btn-success btn-sm" onclick="openForm()">Add Member</button>
                </div>
                <div class="card-body">
                    <div id="toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="display:none;z-index:9999;">
                        <div class="d-flex">
                            <div class="toast-body" id="toast-message"></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="hideToast()"></button>
                        </div>
                    </div>
                    <form id="spfForm" class="mb-4" style="display:none;" onsubmit="return submitForm(event)">
                        <input type="hidden" id="member_id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="invalid-feedback">Name is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="post" class="form-label">Post</label>
                            <select class="form-control" id="post" required>
                                <option value="">Select Post</option>
                                <option value="Advisory Board">Advisory Board</option>
                                <option value="Core Committee">Core Committee</option>
                                <option value="Anchal Coordinators">Anchal Coordinators</option>
                            </select>
                            <div class="invalid-feedback">Post is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="anchal" class="form-label">Anchal</label>
                            <select class="form-control" id="anchal">
                                <option value="">Select Anchal</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" onclick="closeForm()">Cancel</button>
                    </form>
                    <ul class="nav nav-tabs" id="committeeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="advisory-tab" data-bs-toggle="tab" data-bs-target="#advisory" type="button" role="tab" aria-controls="advisory" aria-selected="true">Advisory Board</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="core-tab" data-bs-toggle="tab" data-bs-target="#core" type="button" role="tab" aria-controls="core" aria-selected="false">Core Committee</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="anchal-tab" data-bs-toggle="tab" data-bs-target="#anchal" type="button" role="tab" aria-controls="anchal" aria-selected="false">Anchal Coordinators</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="committeeTabsContent">
                        <div class="tab-pane fade show active" id="advisory" role="tabpanel" aria-labelledby="advisory-tab">
                            <table class="table table-bordered table-hover mt-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="committeeTable">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="core" role="tabpanel" aria-labelledby="core-tab">
                            <table class="table table-bordered table-hover mt-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="committeeTable">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="anchal" role="tabpanel" aria-labelledby="anchal-tab">
                            <select class="form-control mb-3" id="anchalFilter">
                                <option value="">Select Anchal</option>
                            </select>
                            <table class="table table-bordered table-hover mt-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="committeeTable">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const apiUrl = '/api/spf-committee';

function showToast(message, success = true) {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-message');
    toastMsg.textContent = message;
    toast.classList.remove('bg-danger', 'bg-success');
    toast.classList.add(success ? 'bg-success' : 'bg-danger');
    toast.style.display = 'block';
    setTimeout(hideToast, 3000);
}
function hideToast() {
    document.getElementById('toast').style.display = 'none';
}
function openForm(id = null, name = '', post = '', anchal_id = '') {
    document.getElementById('spfForm').style.display = 'block';
    document.getElementById('member_id').value = id || '';
    document.getElementById('name').value = name;
    document.getElementById('post').value = post;
    document.getElementById('anchal').value = anchal_id || '';
}
function closeForm() {
    document.getElementById('spfForm').reset();
    document.getElementById('spfForm').style.display = 'none';
}
function validateForm() {
    let valid = true;
    const name = document.getElementById('name');
    const post = document.getElementById('post');
    if (!name.value.trim()) {
        name.classList.add('is-invalid');
        valid = false;
    } else {
        name.classList.remove('is-invalid');
    }
    if (!post.value.trim()) {
        post.classList.add('is-invalid');
        valid = false;
    } else {
        post.classList.remove('is-invalid');
    }
    return valid;
}
async function submitForm(event) {
    event.preventDefault();
    if (!validateForm()) return false;
    const id = document.getElementById('member_id').value;
    const name = document.getElementById('name').value.trim();
    const post = document.getElementById('post').value.trim();
    const anchal_id = document.getElementById('anchal').value || null;
    const method = id ? 'PUT' : 'POST';
    const url = id ? `${apiUrl}/${id}` : apiUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        const res = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ name, post, anchal_id })
        });
        if (!res.ok) throw new Error('Failed to save member');
        showToast('Member saved successfully!');
        closeForm();
        loadCommittee();
    } catch (err) {
        showToast(err.message, false);
    }
    return false;
}
async function loadCommittee(apiUrl) {
    const table = document.getElementById('committeeTable');
    table.innerHTML = '<tr><td colspan="4">Loading...</td></tr>';
    try {
        const res = await fetch(apiUrl);
        const data = await res.json();
        table.innerHTML = '';
        data.data.forEach((member, idx) => {
            table.innerHTML += `<tr>
                <td>${idx + 1}</td>
                <td>${member.name}</td>
                <td>${member.post}</td>
                <td>
                    <button class='btn btn-sm btn-info me-2' onclick='openForm(${member.id}, "${member.name}", "${member.post}", ${member.anchal_id || "''"})'>Edit</button>
                    <button class='btn btn-sm btn-danger' onclick='deleteMember(${member.id})'>Delete</button>
                </td>
            </tr>`;
        });
        if (data.data.length === 0) {
            table.innerHTML = '<tr><td colspan="4">No members found.</td></tr>';
        }
    } catch (err) {
        table.innerHTML = `<tr><td colspan="4">${err.message}</td></tr>`;
    }
}
async function deleteMember(id) {
    if (!confirm('Are you sure you want to delete this member?')) return;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        const res = await fetch(`${apiUrl}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        if (!res.ok) throw new Error('Failed to delete member');
        showToast('Member deleted successfully!');
        loadCommittee();
    } catch (err) {
        showToast(err.message, false);
    }
}
async function loadAnchalOptions() {
    const anchalSelect = document.getElementById('anchal');
    try {
        const res = await fetch('/api/aanchal');
        const data = await res.json();
        if (Array.isArray(data)) {
            data.forEach(anchal => {
                const option = document.createElement('option');
                option.value = anchal.id;
                option.textContent = anchal.name;
                anchalSelect.appendChild(option);
            });
        }
    } catch (err) {
        console.error('Failed to load anchal options:', err);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Load data on tab switch
    const tabs = document.querySelectorAll('#committeeTabs button');
    const anchalFilter = document.getElementById('anchalFilter');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', (event) => {
            const tabId = event.target.id;
            if (tabId === 'advisory-tab') {
                loadCommittee('/api/spf-committee/advisory-board');
            } else if (tabId === 'core-tab') {
                loadCommittee('/api/spf-committee/core-committee');
            } else if (tabId === 'anchal-tab') {
                // If anchalFilter exists and has value, pass it
                const anchalId = anchalFilter ? anchalFilter.value : '';
                let url = '/api/spf-committee/anchal-coordinators';
                if (anchalId) url += `/${anchalId}`;
                loadCommittee(url);
            }
        });
    });

    if (anchalFilter) {
        anchalFilter.addEventListener('change', function() {
            const anchalId = anchalFilter.value;
            let url = '/api/spf-committee/anchal-coordinators';
            if (anchalId) url += `/${anchalId}`;
            loadCommittee(url);
        });
    }

    // Initial load
    loadCommittee('/api/spf-committee/advisory-board');
    loadAnchalOptions();
});

// Expose functions to global scope for inline onclick
window.openForm = openForm;
window.deleteMember = deleteMember;
</script>
@endsection
