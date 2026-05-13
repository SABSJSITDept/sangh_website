@extends('includes.layouts.yuva_sangh')

@section('title', 'Yuva Ex-President Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">पूर्व अध्यक्ष (Ex-President)</h2>
                <p class="text-muted small mb-0">Manage and honor the previous leadership of Yuva Sangh.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-person-check text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 1.25rem; top: 100px;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0" id="formTitle">Add Ex-President</h5>
                </div>
                <div class="card-body p-4">
                    <form id="exPresidentForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="editId">
                        
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-uppercase text-muted">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3" name="name" id="name" required placeholder="Enter name">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-uppercase text-muted">Karyakal (Tenure)</label>
                            <input type="text" class="form-control rounded-3" name="karyakal" id="karyakal" placeholder="e.g. 2021-2023">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-uppercase text-muted">City</label>
                            <input type="text" class="form-control rounded-3" name="city" id="city" placeholder="Enter city">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Photo <span class="text-danger">*</span></label>
                            <div class="upload-area border rounded-3 p-3 text-center bg-light cursor-pointer" onclick="document.getElementById('photo').click()">
                                <i class="bi bi-person-bounding-box fs-3 text-muted d-block mb-2"></i>
                                <span class="small text-muted" id="fileName">Select Photo (Max 200KB)</span>
                                <input type="file" class="d-none" name="photo" id="photo" accept="image/*">
                            </div>
                            <div id="photoPreview" class="mt-3 d-none">
                                <img src="" alt="Preview" class="img-fluid rounded-4 shadow-sm border" style="max-height: 150px; width: 100%; object-fit: cover;">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold" id="submitBtn">
                                <i class="bi bi-plus-lg me-2"></i> Add Record
                            </button>
                            <button type="button" class="btn btn-light py-2 rounded-3 fw-bold border d-none" id="cancelEdit">
                                Cancel Edit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold outfit-font mb-0">Past Leadership Roll</h5>
                        <span id="recordCount" class="badge bg-light text-dark border rounded-pill px-3 py-2">0 Records</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 border-0">Profile</th>
                                    <th class="py-3 border-0">Name</th>
                                    <th class="py-3 border-0">Tenure & City</th>
                                    <th class="pe-4 py-3 border-0 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="exPresidentTable">
                                <!-- Loaded via JS -->
                                <tr>
                                    <td colspan="4" class="text-center py-5">
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
function showToast(icon, message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 2000,
        background: '#fff',
        color: '#1e293b'
    });
}

// Photo Preview Logic
document.getElementById('photo').addEventListener('change', function() {
    const file = this.files[0];
    const preview = document.getElementById('photoPreview');
    const previewImg = preview.querySelector('img');
    const fileName = document.getElementById('fileName');

    if (file) {
        if (file.size > 200 * 1024) {
            showToast("error", "Image must be under 200KB");
            this.value = "";
            return;
        }
        fileName.textContent = file.name;
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

let allData = [];
function fetchList() {
    fetch("/api/yuva-ex-president")
        .then(res => res.json())
        .then(data => {
            allData = data;
            let container = document.getElementById("exPresidentTable");
            document.getElementById("recordCount").textContent = `${data.length} Records`;
            
            let html = "";
            if (data.length === 0) {
                html = '<tr><td colspan="4" class="text-center py-5 text-muted">No records found.</td></tr>';
            } else {
                data.forEach(item => {
                    html += `
                    <tr class="transition-all">
                        <td class="ps-4 py-3">
                            <img src="${item.photo}" class="rounded-circle border shadow-sm" style="width:50px; height:50px; object-fit:cover;">
                        </td>
                        <td class="py-3">
                            <h6 class="mb-0 fw-bold outfit-font text-dark">${item.name}</h6>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-primary-subtle text-primary border rounded-pill px-2 mb-1">${item.karyakal ?? 'N/A'}</span>
                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>${item.city ?? 'Not set'}</div>
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                <button class="btn btn-sm btn-white border px-3" onclick="editItem(${item.id})" title="Edit">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </button>
                                <button class="btn btn-sm btn-white border px-3" onclick="deleteItem(${item.id})" title="Delete">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
            }
            container.innerHTML = html;
        });
}

document.getElementById("exPresidentForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let id = document.getElementById("editId").value;
    let url = id ? `/api/yuva-ex-president/${id}?_method=PUT` : "/api/yuva-ex-president";

    const btn = document.getElementById("submitBtn");
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

    fetch(url, {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if(response.errors){
            Object.values(response.errors).forEach(err => showToast("error", err[0]));
        } else {
            showToast("success", response.message);
            resetForm();
            fetchList();
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showToast("error", "Failed to save record.");
    });
});

function editItem(id){
    const item = allData.find(x => x.id == id);
    if(!item) return;
    document.getElementById("editId").value = item.id;
    document.getElementById("name").value = item.name;
    document.getElementById("karyakal").value = item.karyakal ?? '';
    document.getElementById("city").value = item.city ?? '';

    document.getElementById("formTitle").innerText = "Edit Ex-President";
    document.getElementById("submitBtn").innerHTML = '<i class="bi bi-arrow-repeat me-2"></i> Update Record';
    document.getElementById("cancelEdit").classList.remove("d-none");
    
    const preview = document.getElementById('photoPreview');
    const previewImg = preview.querySelector('img');
    previewImg.src = item.photo;
    preview.classList.remove('d-none');
    document.getElementById("photo").required = false;

    window.scrollTo({ top: 0, behavior: "smooth" });
}

document.getElementById("cancelEdit").addEventListener("click", resetForm);

function resetForm(){
    document.getElementById("exPresidentForm").reset();
    document.getElementById("editId").value = "";
    document.getElementById("formTitle").innerText = "Add Ex-President";
    document.getElementById("submitBtn").innerHTML = '<i class="bi bi-plus-lg me-2"></i> Add Record';
    document.getElementById("cancelEdit").classList.add("d-none");
    document.getElementById("photoPreview").classList.add('d-none');
    document.getElementById("fileName").textContent = "Select Photo (Max 200KB)";
    document.getElementById("photo").required = true;
}

function deleteItem(id){
    Swal.fire({
        title: "Delete this record?",
        text: "This action is permanent.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Yes, Delete",
        borderRadius: '1rem'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/yuva-ex-president/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(response => {
                showToast("success", response.message);
                fetchList();
            });
        }
    });
}

fetchList();
</script>

<style>
    .upload-area { border: 2px dashed #e2e8f0; cursor: pointer; }
    .upload-area:hover { border-color: #6366f1; background-color: #f8fafc; }
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .btn-white { background-color: #fff; border-color: #e2e8f0; }
    .btn-white:hover { background-color: #f1f5f9; }
</style>
@endsection
