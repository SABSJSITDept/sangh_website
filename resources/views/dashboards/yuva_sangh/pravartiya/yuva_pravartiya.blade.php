@extends('includes.layouts.yuva_sangh')

@section('title', 'Yuva Sangh Pravartiya')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Yuva Sangh Pravartiya</h2>
                <p class="text-muted small mb-0">Manage and update various activities and initiatives.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-broadcast text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center gap-3" style="border-radius: 1rem;">
                <div class="bg-primary text-white p-2 rounded-circle">
                    <i class="bi bi-info-lg"></i>
                </div>
                <div>
                    <span class="fw-medium">Logo Note:</span> Uploading a logo is optional. Supported formats: JPG, PNG, WEBP (Max 200KB).
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0" id="formTitle">Create New Entry</h5>
                </div>
                <div class="card-body p-4">
                    <form id="pravartiyaForm" enctype="multipart/form-data">
                        <input type="hidden" id="edit_id" value="">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-600 small text-uppercase text-muted">Heading <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" id="heading" required maxlength="255" placeholder="Enter activity title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600 small text-uppercase text-muted">Logo (Optional)</label>
                                <div class="input-group">
                                    <input type="file" class="form-control rounded-3" id="photo" accept="image/*">
                                </div>
                                <div class="form-text small">Leave empty to keep current image when updating.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-600 small text-uppercase text-muted">Content <span class="text-danger">*</span></label>
                                <textarea class="form-control rounded-3" id="content" rows="4" required placeholder="Describe the activity in detail..."></textarea>
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" id="submitBtn" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                                <i class="bi bi-check-circle me-2"></i> Save Entry
                            </button>
                            <button type="button" id="resetBtn" class="btn btn-light px-4 py-2 rounded-pill fw-bold border">
                                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Section -->
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold outfit-font mb-0">All Entries</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="dataTable">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 border-0 py-3" style="width:70px">#</th>
                                    <th class="border-0 py-3">Heading</th>
                                    <th class="border-0 py-3">Content Preview</th>
                                    <th class="border-0 py-3 text-center">Logo</th>
                                    <th class="pe-4 border-0 py-3 text-end" style="width:180px">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <!-- Loaded via JS -->
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const API_BASE = "/api/yuva-pravartiya";

function toast(icon, title) {
    Swal.fire({ 
        icon, 
        title, 
        timer: 2000, 
        showConfirmButton: false, 
        position: 'top-end', 
        toast: true,
        background: '#fff',
        color: '#1e293b'
    });
}

function confirmBox(title="Are you sure?") {
    return Swal.fire({
        title, 
        icon: 'warning', 
        showCancelButton: true,
        confirmButtonText: 'Yes, Proceed', 
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#64748b',
        borderRadius: '1rem'
    });
}

function clearForm() {
    document.getElementById('edit_id').value = "";
    document.getElementById('heading').value = "";
    document.getElementById('content').value = "";
    document.getElementById('photo').value = "";
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-check-circle me-2"></i> Save Entry';
    document.getElementById('formTitle').textContent = "Create New Entry";
}

let allData = [];
function fetchAll() {
    fetch(API_BASE, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        allData = data;
        renderTable(data);
    })
    .catch(() => toast('error','Failed to load data'));
}

function renderTable(data) {
    const tbody = document.getElementById('tbody');
    tbody.innerHTML = "";
    if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted">No entries found.</td></tr>`;
        return;
    }
    data.forEach((item, idx) => {
        const imgHtml = item.photo ? `<img src="${item.photo}" alt="photo" class="rounded-3 border shadow-sm" style="width:48px;height:48px;object-fit:cover;">` : `<span class="badge bg-light text-muted border py-2">No Image</span>`;
        const row = `
            <tr class="transition-all">
                <td class="ps-4 fw-bold text-muted">${idx + 1}</td>
                <td class="fw-bold text-dark outfit-font">${escapeHtml(item.heading)}</td>
                <td class="text-muted small text-truncate" style="max-width:300px;">${escapeHtml(item.content)}</td>
                <td class="text-center">${imgHtml}</td>
                <td class="pe-4 text-end">
                    <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                        <button class="btn btn-sm btn-white border px-3" onclick="editItem(${idx})" title="Edit">
                            <i class="bi bi-pencil-square text-primary"></i>
                        </button>
                        <button class="btn btn-sm btn-white border px-3" onclick="delItem(${item.id})" title="Delete">
                            <i class="bi bi-trash3 text-danger"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

function escapeHtml(str) {
    return (str ?? '').toString().replace(/[&<>"'`=\/]/g, s => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    }[s]));
}

function editItem(index) {
    const item = allData[index];
    if(!item) return;
    document.getElementById('edit_id').value = item.id;
    document.getElementById('heading').value = item.heading ?? '';
    document.getElementById('content').value = item.content ?? '';
    document.getElementById('photo').value = '';
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-arrow-repeat me-2"></i> Update Entry';
    document.getElementById('formTitle').textContent = "Edit Entry Details";
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function delItem(id) {
    confirmBox("Delete this entry?")
    .then(res => {
        if (!res.isConfirmed) return;
        return fetch(`${API_BASE}/${id}`, {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    })
    .then(r => r ? r.json() : null)
    .then(resp => {
        if (!resp) return;
        toast('success', resp.message || 'Deleted');
        fetchAll();
    })
    .catch(() => toast('error', 'Delete failed'));
}

document.getElementById('pravartiyaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const heading = document.getElementById('heading').value.trim();
    const content = document.getElementById('content').value.trim();
    const photoInput = document.getElementById('photo');

    const id = document.getElementById('edit_id').value;
    const isUpdate = Boolean(id);

    const form = new FormData();
    form.append('heading', heading);
    form.append('content', content);
    if (photoInput.files[0]) form.append('photo', photoInput.files[0]);

    const btn = document.getElementById('submitBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

    fetch(isUpdate ? `${API_BASE}/${id}` : API_BASE, {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: (function(){
            if (isUpdate) form.append('_method', 'PUT');
            return form;
        })()
    })
    .then(async r => {
        const data = await r.json();
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if (!r.ok) throw data;
        toast('success', data.message || (isUpdate ? 'Updated' : 'Created'));
        clearForm();
        fetchAll();
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if (err && err.errors) {
            const firstKey = Object.keys(err.errors)[0];
            toast('error', err.errors[firstKey][0]);
        } else {
            toast('error', 'Save failed');
        }
    });
});

document.getElementById('resetBtn').addEventListener('click', clearForm);
fetchAll();
</script>

<style>
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .btn-white { background-color: #fff; border-color: #e2e8f0; }
    .btn-white:hover { background-color: #f1f5f9; }
</style>
@endsection
