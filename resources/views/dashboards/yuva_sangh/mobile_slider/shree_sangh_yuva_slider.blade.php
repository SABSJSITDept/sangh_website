@extends('includes.layouts.yuva_sangh')

@section('title', 'Home Slider Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Home Slider Management</h2>
                <p class="text-muted small mb-0">Manage premium slider images for the Shree Sangh section.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-house-door text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Upload Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 1.25rem; top: 100px;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0">Add Slider Image</h5>
                </div>
                <div class="card-body p-4">
                    <form id="sliderForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Select Image</label>
                            <div class="upload-drop-zone border-dashed rounded-4 p-4 text-center mb-2 position-relative transition-all" id="dropZone">
                                <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3 d-block"></i>
                                <p class="small text-muted mb-0">Click or drag & drop</p>
                                <input type="file" name="photo" id="photoInput" class="position-absolute inset-0 opacity-0 cursor-pointer w-100 h-100" accept="image/*" required>
                            </div>
                            <div id="imagePreview" class="d-none mt-3">
                                <img src="" alt="Preview" class="img-fluid rounded-4 shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <i class="bi bi-info-circle text-info"></i>
                                <small class="text-muted">1280×520px (Max 300KB)</small>
                            </div>
                        </div>
                        <button type="submit" id="uploadBtn" class="btn btn-primary w-100 py-2 rounded-3 fw-bold">
                            <i class="bi bi-plus-lg me-2"></i> Add Photo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Slider List Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold outfit-font mb-0">Current Sliders</h5>
                        <span id="sliderCount" class="badge bg-light text-dark border rounded-pill px-3 py-2">0 Images</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 border-0">Preview</th>
                                    <th class="py-3 border-0">Path</th>
                                    <th class="pe-4 py-3 border-0 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sliderTable">
                                <!-- Loaded via AJAX -->
                                <tr>
                                    <td colspan="3" class="text-center py-5">
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
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest'
};

document.addEventListener("DOMContentLoaded", function() {
    fetchSliders();

    const photoInput = document.getElementById('photoInput');
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    const dropZone = document.getElementById('dropZone');

    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('d-none');
                dropZone.classList.add('bg-light');
            }
            reader.readAsDataURL(file);
        }
    });
});

function fetchSliders() {
    fetch('/api/home_slider')
        .then(res => res.json())
        .then(data => {
            let container = document.getElementById('sliderTable');
            let countBadge = document.getElementById('sliderCount');
            countBadge.textContent = `${data.length} Images`;
            
            let rows = '';
            if (data.length === 0) {
                rows = '<tr><td colspan="3" class="text-center py-5 text-muted">No slider images found.</td></tr>';
            } else {
                data.forEach(slider => {
                    rows += `
                        <tr class="transition-all">
                            <td class="ps-4 py-3">
                                <img src="/${slider.photo}" class="rounded-3 shadow-sm border" style="width:160px; height:70px; object-fit:cover;">
                            </td>
                            <td class="py-3 text-muted small">${slider.photo}</td>
                            <td class="pe-4 py-3 text-end">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    <button class="btn btn-sm btn-white border px-3" onclick="updatePhoto(${slider.id})" title="Replace Image">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </button>
                                    <button class="btn btn-sm btn-white border px-3" onclick="deletePhoto(${slider.id})" title="Delete">
                                        <i class="bi bi-trash3 text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
            container.innerHTML = rows;
        });
}

document.getElementById('sliderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    const btn = document.getElementById('uploadBtn');
    const originalHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Adding...';

    fetch('/api/home_slider', {
        method: 'POST',
        headers,
        body: formData
    })
    .then(async res => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        const data = await res.json();

        if (res.status === 422) {
            let errorMsg = data.message;
            if (data.errors) errorMsg = Object.values(data.errors).join('\n');
            Swal.fire({ icon: 'error', title: 'Upload Failed', text: errorMsg, borderRadius: '1rem' });
            return;
        }

        if (res.ok) {
            Swal.fire({ icon: 'success', title: 'Added', text: data.message, timer: 1500, showConfirmButton: false });
            fetchSliders();
            this.reset();
            document.getElementById('imagePreview').classList.add('d-none');
            document.getElementById('dropZone').classList.remove('bg-light');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!' });
    });
});

function deletePhoto(id) {
    Swal.fire({
        title: 'Delete Image?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        borderRadius: '1rem'
    }).then(res => {
        if (res.isConfirmed) {
            fetch(`/api/home_slider/${id}`, { method: 'DELETE', headers })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({ icon: 'success', title: 'Deleted', text: data.message, timer: 1500, showConfirmButton: false });
                    fetchSliders();
                });
        }
    });
}

function updatePhoto(id) {
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function() {
        let file = this.files[0];
        if (file.size > 300 * 1024) {
            Swal.fire({ icon: 'error', title: 'Too Large', text: 'Image must be less than 300KB' });
            return;
        }

        let formData = new FormData();
        formData.append('photo', file);

        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        fetch(`/api/home_slider/${id}`, { 
            method: 'POST', 
            headers: { ...headers, 'X-HTTP-Method-Override': 'PUT' }, 
            body: formData 
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire({ icon: 'success', title: 'Updated', text: data.message, timer: 1500, showConfirmButton: false });
            fetchSliders();
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Update failed' }));
    };
    input.click();
}
</script>

<style>
    .upload-drop-zone { border: 2px dashed #e2e8f0; cursor: pointer; }
    .upload-drop-zone:hover { border-color: #6366f1; background-color: #f8fafc; }
    .border-dashed { border-style: dashed !important; }
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .btn-white { background-color: #fff; border-color: #e2e8f0; }
    .btn-white:hover { background-color: #f1f5f9; }
</style>
@endsection
