@extends('includes.layouts.spf')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .spf-card {
        border-radius: 18px;
        box-shadow: 0 4px 32px 0 rgba(0,0,0,0.10);
        border: none;
        background: #fff;
    }
    .spf-card-header {
        border-radius: 18px 18px 0 0;
        background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%);
        color: #fff;
        font-size: 1.3rem;
        font-weight: 600;
        padding: 1.2rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px 0 rgba(0,0,0,0.04);
    }
    .spf-btn-add {
        background: linear-gradient(90deg, #28a745 60%, #5be584 100%);
        color: #fff;
        font-weight: 500;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1.2rem;
        transition: background 0.2s;
    }
    .spf-btn-add:hover {
        background: linear-gradient(90deg, #218838 60%, #43d97b 100%);
        color: #fff;
    }
    .spf-form-floating .form-control:focus ~ label,
    .spf-form-floating .form-control:not(:placeholder-shown) ~ label {
        top: -0.8rem;
        left: 0.75rem;
        font-size: 0.9rem;
        color: #007bff;
        background: #fff;
        padding: 0 0.25rem;
    }
    .spf-form-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .spf-form-floating label {
        position: absolute;
        top: 0.7rem;
        left: 1rem;
        color: #888;
        pointer-events: none;
        transition: all 0.2s;
        background: transparent;
        padding: 0 0.25rem;
    }
    .spf-form-floating .form-control {
        padding-top: 1.5rem;
        border-radius: 8px;
    }
    .spf-table {
        border-radius: 12px;
        overflow: hidden;
        background: #f8f9fa;
        margin-top: 1.5rem;
    }
    .spf-table th, .spf-table td {
        vertical-align: middle;
    }
    .spf-table-striped tbody tr:nth-of-type(odd) {
        background-color: #f1f7ff;
    }
    .spf-table thead {
        background: #e3f0ff;
    }
    .spf-action-btn {
        border-radius: 6px;
        margin-right: 0.4rem;
        transition: background 0.2s, color 0.2s;
    }
    .spf-action-btn.edit {
        background: #e3f0ff;
        color: #007bff;
    }
    .spf-action-btn.edit:hover {
        background: #007bff;
        color: #fff;
    }
    .spf-action-btn.delete {
        background: #ffe3e3;
        color: #dc3545;
    }
    .spf-action-btn.delete:hover {
        background: #dc3545;
        color: #fff;
    }
    .spf-toast {
        min-width: 220px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 1rem;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.10);
        opacity: 0.97;
    }
    @media (max-width: 600px) {
        .spf-card { border-radius: 10px; }
        .spf-card-header { font-size: 1.05rem; padding: 1rem; }
        .spf-table { font-size: 0.95rem; }
    }
</style>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card spf-card">
                <div class="spf-card-header">
                    <span><i class="bi bi-people-fill me-2"></i>SPF Committee Management</span>
                    <button class="spf-btn-add" onclick="openForm()"><i class="bi bi-plus-circle me-1"></i> Add Member</button>
                </div>
                <div class="card-body p-4">
                    <div id="toast" class="toast spf-toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="display:none;z-index:9999;">
                        <div class="d-flex">
                            <div class="toast-body" id="toast-message"></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="hideToast()"></button>
                        </div>
                    </div>
                    <form id="spfForm" class="mb-4" style="display:none;max-width:520px;margin:auto;" onsubmit="return submitForm(event)">
                        <input type="hidden" id="member_id">
                        <div class="spf-form-floating">
                            <input type="text" class="form-control" id="name" placeholder=" " required>
                            <label for="name">Name</label>
                            <div class="invalid-feedback">Name is required.</div>
                        </div>
                        <div class="spf-form-floating">
                            <select class="form-control" id="post" placeholder=" " required>
                                <option value="">Select Post</option>
                                <option value="Advisory Board">Advisory Board</option>
                                <option value="Core Committee">Core Committee</option>
                                <option value="Anchal Coordinators">Anchal Coordinators</option>
                            </select>
                            <label for="post">Post</label>
                            <div class="invalid-feedback">Post is required.</div>
                        </div>
                        <div class="spf-form-floating">
                            <select class="form-control" id="anchal" placeholder=" ">
                                <option value="">Select Anchal</option>
                            </select>
                            <label for="anchal">Anchal</label>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Save</button>
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="closeForm()"><i class="bi bi-x-circle me-1"></i>Cancel</button>
                        </div>
                    </form>
                    <ul class="nav nav-tabs mt-3" id="committeeTabs" role="tablist">
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
                            <table class="table spf-table spf-table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="advisoryTable">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="core" role="tabpanel" aria-labelledby="core-tab">
                            <table class="table spf-table spf-table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="coreTable">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="anchal" role="tabpanel" aria-labelledby="anchal-tab">
                            <select class="form-control mb-3" id="anchalFilter">
                                <option value="">Select Anchal</option>
                            </select>
                            <table class="table spf-table spf-table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="anchalTable">
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
        // Determine active tab and load correct committee data
        const activeTab = document.querySelector('#committeeTabs .nav-link.active');
        if (activeTab) {
            const tabId = activeTab.id;
            if (tabId === 'advisory-tab') {
                loadCommittee('/api/spf-committee/advisory-board');
            } else if (tabId === 'core-tab') {
                loadCommittee('/api/spf-committee/core-committee');
            } else if (tabId === 'anchal-tab') {
                const anchalFilter = document.getElementById('anchalFilter');
                let url = '/api/spf-committee/anchal-coordinators';
                if (anchalFilter && anchalFilter.value) url += `/${anchalFilter.value}`;
                loadCommittee(url);
            }
        } else {
            // fallback: load advisory board
            loadCommittee('/api/spf-committee/advisory-board');
        }
    } catch (err) {
        showToast(err.message, false);
    }
    return false;
}
async function loadCommittee(apiUrl) {
    // Determine which tab is active and select the correct table body
    let tableId = 'advisoryTable';
    const advisoryTab = document.getElementById('advisory-tab');
    const coreTab = document.getElementById('core-tab');
    const anchalTab = document.getElementById('anchal-tab');
    if (coreTab && coreTab.classList.contains('active')) {
        tableId = 'coreTable';
    } else if (anchalTab && anchalTab.classList.contains('active')) {
        tableId = 'anchalTable';
    }
    const table = document.getElementById(tableId);
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
                    <button class='spf-action-btn edit btn btn-sm' title='Edit' onclick='openForm(${member.id}, "${member.name}", "${member.post}", ${member.anchal_id || "''"})'><i class="bi bi-pencil-square"></i></button>
                    <button class='spf-action-btn delete btn btn-sm' title='Delete' onclick='deleteMember(${member.id})'><i class="bi bi-trash"></i></button>
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
                // Always load all Anchal Coordinators if no filter is selected
                let url = '/api/spf-committee/anchal-coordinators';
                if (anchalFilter && anchalFilter.value) url += `/${anchalFilter.value}`;
                loadCommittee(url);
            }
        });
    });

    if (anchalFilter) {
        anchalFilter.addEventListener('change', function() {
            let url = '/api/spf-committee/anchal-coordinators';
            if (anchalFilter.value) url += `/${anchalFilter.value}`;
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
