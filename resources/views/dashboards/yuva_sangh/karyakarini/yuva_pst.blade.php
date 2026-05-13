@extends('includes.layouts.yuva_sangh')

@section('title', 'Yuva Sangh Karyakarini - PST')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">कार्यकारिणी पदाधिकारी (PST)</h2>
                <p class="text-muted small mb-0">Manage the core leadership team members and their roles.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-person-workspace text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-3" style="border-radius: 1rem;">
                <div class="bg-info text-white p-2 rounded-circle mt-1">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Leadership Rules:</h6>
                    <p class="small mb-0 opacity-75">Each position (अध्यक्ष, महामंत्री, कोषाध्यक्ष, सह कोषाध्यक्ष) allows only <b>one entry</b>. Photo must be under 200KB (JPG/PNG).</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add Entry Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 1.25rem; top: 100px;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0">Add Core Member</h5>
                </div>
                <div class="card-body p-4">
                    <form id="pstForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-uppercase text-muted">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-uppercase text-muted">Position <span class="text-danger">*</span></label>
                            <select name="post" id="postSelect" class="form-select rounded-3" required>
                                <option value="">Select Position</option>
                                <option>अध्यक्ष</option>
                                <option>महामंत्री</option>
                                <option>कोषाध्यक्ष</option>
                                <option>सह कोषाध्यक्ष</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Photo <span class="text-danger">*</span></label>
                            <div class="upload-area border rounded-3 p-3 text-center bg-light cursor-pointer" onclick="document.getElementById('photoInput').click()">
                                <i class="bi bi-camera-fill fs-3 text-muted d-block mb-2"></i>
                                <span class="small text-muted" id="fileName">Select Image (Max 200KB)</span>
                                <input type="file" id="photoInput" name="photo" class="d-none" accept="image/*" required>
                            </div>
                            <div id="photoPreviewContainer" class="mt-3 d-none text-center">
                                <img id="photoPreview" class="img-fluid rounded-4 shadow-sm border" style="max-height: 150px; width: 100%; object-fit: cover;" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold" id="submitBtn">
                            <i class="bi bi-save me-2"></i> Save Entry
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold outfit-font mb-0">Current Core Team</h5>
                        <span id="pstCount" class="badge bg-light text-dark border rounded-pill px-3 py-2">0 Members</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4" id="pstList">
                        <!-- Loaded via JS -->
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <form id="editForm" enctype="multipart/form-data">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0">Update Member Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-uppercase text-muted">Name</label>
                        <input type="text" name="name" id="editName" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-uppercase text-muted">Position</label>
                        <select name="post" id="editPost" class="form-select rounded-3" required>
                            <option value="">Select Position</option>
                            <option>अध्यक्ष</option>
                            <option>महामंत्री</option>
                            <option>कोषाध्यक्ष</option>
                            <option>सह कोषाध्यक्ष</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-uppercase text-muted">Photo (Optional)</label>
                        <input type="file" id="editPhoto" name="photo" accept="image/*" class="form-control rounded-3">
                        <div id="editPreviewContainer" class="mt-3 text-center">
                            <img id="editPreview" class="img-fluid rounded-4 shadow-sm border" style="max-height: 120px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-primary rounded-pill px-4" type="submit" id="updateBtn">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const toasty = (title, icon='success') => {
    Swal.fire({ 
        toast: true, 
        position: 'top-end', 
        showConfirmButton: false, 
        timer: 2000, 
        icon, 
        title,
        background: '#fff',
        color: '#1e293b'
    });
};

const csrfHeaders = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest'
};

let allData = [];
let usedPosts = new Set();
const MAX_BYTES = 200 * 1024;

// Photo Preview Logic
document.getElementById('photoInput').addEventListener('change', function(){
    const file = this.files[0];
    if (file) {
        if (file.size > MAX_BYTES) {
            toasty('Photo must be under 200KB', 'error');
            this.value = '';
            return;
        }
        document.getElementById('fileName').textContent = file.name;
        const img = document.getElementById('photoPreview');
        img.src = URL.createObjectURL(file);
        document.getElementById('photoPreviewContainer').classList.remove('d-none');
    }
});

document.getElementById('editPhoto').addEventListener('change', function(){
    const file = this.files[0];
    if (file) {
        if (file.size > MAX_BYTES) {
            toasty('Photo must be under 200KB', 'error');
            this.value = '';
            return;
        }
        const img = document.getElementById('editPreview');
        img.src = URL.createObjectURL(file);
    }
});

function fetchPst(){
    fetch("/api/yuva-pst")
    .then(res => res.json())
    .then(data => {
        allData = data;
        usedPosts = new Set(data.map(x => x.post));
        document.getElementById('pstCount').textContent = `${data.length} Members`;
        
        // Disable taken posts in create select
        const select = document.getElementById('postSelect');
        [...select.options].forEach(opt => {
            if(!opt.value) return;
            opt.disabled = usedPosts.has(opt.value);
        });

        let html = "";
        if(!data.length){
            html = `<div class="col-12 text-center py-5 text-muted"><i class="bi bi-people display-1 text-light d-block mb-3"></i>No members added yet.</div>`;
        } else {
            data.forEach(item => {
                html += `
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden hover-scale" style="border-radius: 1rem;">
                        <div class="position-relative">
                            <img src="${item.photo}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 p-2">
                                <div class="btn-group-vertical shadow-sm rounded-3 overflow-hidden">
                                    <button class="btn btn-sm btn-white border px-2 py-1" onclick="openEdit(${item.id})" title="Edit">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </button>
                                    <button class="btn btn-sm btn-white border px-2 py-1" onclick="deletePst(${item.id})" title="Delete">
                                        <i class="bi bi-trash3 text-danger"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3 text-center">
                            <h6 class="fw-bold outfit-font text-dark mb-1">${item.name}</h6>
                            <span class="badge ${badgeClassFor(item.post)} rounded-pill px-3 py-1 small fw-normal border">${item.post}</span>
                        </div>
                    </div>
                </div>`;
            });
        }
        document.getElementById('pstList').innerHTML = html;
    });
}

function badgeClassFor(post){
    const map = {
        'अध्यक्ष':'bg-danger-subtle text-danger border-danger',
        'महामंत्री':'bg-primary-subtle text-primary border-primary',
        'कोषाध्यक्ष':'bg-success-subtle text-success border-success',
        'सह कोषाध्यक्ष':'bg-warning-subtle text-warning border-warning'
    };
    return map[post] || 'bg-light text-dark';
}

document.getElementById('pstForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    const btn = document.getElementById('submitBtn');
    const originalHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

    fetch("/api/yuva-pst", { method: "POST", body: formData, headers: csrfHeaders })
    .then(res => res.json())
    .then(response => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if(response.errors){
            toasty(Object.values(response.errors)[0][0], 'error');
        } else {
            toasty(response.message || 'Record saved');
            fetchPst();
            this.reset();
            document.getElementById('photoPreviewContainer').classList.add('d-none');
            document.getElementById('fileName').textContent = "Select Image (Max 200KB)";
        }
    }).catch(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        toasty('Failed to save record', 'error');
    });
});

function deletePst(id){
    Swal.fire({
        title: 'Delete this record?',
        text: 'This action is permanent.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Delete',
        borderRadius: '1rem'
    }).then(result => {
        if(!result.isConfirmed) return;
        fetch(`/api/yuva-pst/${id}`, { method: "DELETE", headers: csrfHeaders })
        .then(res => res.json())
        .then(response => {
            toasty(response.message || 'Deleted');
            fetchPst();
        });
    });
}

let editModal;
document.addEventListener("DOMContentLoaded", function() {
    editModal = new bootstrap.Modal(document.getElementById('editModal'));
});

function openEdit(id){
    const item = allData.find(x => x.id == id);
    if(!item) return;
    document.getElementById('editId').value = item.id;
    document.getElementById('editName').value = item.name;
    document.getElementById('editPost').value = item.post;
    document.getElementById('editPreview').src = item.photo;
    
    const editSelect = document.getElementById('editPost');
    [...editSelect.options].forEach(opt => {
        if(!opt.value) return;
        if(opt.value === item.post) { opt.disabled = false; return; }
        opt.disabled = usedPosts.has(opt.value);
    });
    
    editModal.show();
}

document.getElementById('editForm').addEventListener('submit', function(e){
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const formData = new FormData(this);
    formData.append('_method', 'PUT');

    const btn = document.getElementById('updateBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';

    fetch(`/api/yuva-pst/${id}`, { method: "POST", body: formData, headers: csrfHeaders })
    .then(res => res.json())
    .then(response => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if(response.errors){
            toasty(Object.values(response.errors)[0][0], 'error');
        } else {
            toasty(response.message || 'Updated');
            editModal.hide();
            fetchPst();
        }
    }).catch(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        toasty('Update failed', 'error');
    });
});

fetchPst();
</script>

<style>
    .upload-area { border: 2px dashed #e2e8f0; cursor: pointer; }
    .upload-area:hover { border-color: #6366f1; background-color: #f8fafc; }
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease; }
    .btn-white { background-color: #fff; border-color: #e2e8f0; }
    .btn-white:hover { background-color: #f1f5f9; }
</style>
@endsection
