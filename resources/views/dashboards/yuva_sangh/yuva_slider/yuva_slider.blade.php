@extends('includes.layouts.yuva_sangh')

@section('title', 'Yuva Slider Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Yuva Slider Management</h2>
                <p class="text-muted small mb-0">Upload and manage images for the Yuva Sangh home slider.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-images text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Upload Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 1.25rem; top: 100px;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0">Upload New Slider</h5>
                </div>
                <div class="card-body p-4">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="image" class="form-label fw-600 small text-uppercase tracking-wider">Select Image</label>
                            <div class="upload-drop-zone border-dashed rounded-4 p-4 text-center mb-2 position-relative transition-all" id="dropZone">
                                <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3 d-block"></i>
                                <p class="small text-muted mb-0">Click or drag & drop image here</p>
                                <input type="file" name="image" id="image" class="position-absolute inset-0 opacity-0 cursor-pointer w-100 h-100" accept="image/*" required>
                            </div>
                            <div id="imagePreview" class="d-none mt-3">
                                <img src="" alt="Preview" class="img-fluid rounded-4 shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <i class="bi bi-info-circle text-info"></i>
                                <small class="text-muted">Recommended: 1920x1080px (Max 200 KB)</small>
                            </div>
                        </div>
                        <button type="submit" id="uploadBtn" class="btn btn-primary w-100 py-2 rounded-3 fw-bold">
                            <i class="bi bi-upload me-2"></i> Upload Image
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Slider List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold outfit-font mb-0">Current Sliders</h5>
                        <span id="sliderCount" class="badge bg-light text-dark rounded-pill px-3 py-2 border">0 Images</span>
                    </div>
                </div>
                <div class="card-body p-4 pt-0">
                    <div id="sliderList" class="row g-4">
                        <!-- Content loaded via AJAX -->
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    fetchSliders();

    // Image Preview Logic
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    const dropZone = document.getElementById('dropZone');

    fileInput.addEventListener('change', function() {
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

function showError(msg) {
    Swal.fire({
        icon: "error",
        title: "Error",
        text: msg,
        confirmButtonColor: "#6366f1"
    });
}

function showSuccess(msg) {
    Swal.fire({
        icon: "success",
        title: "Success",
        text: msg,
        timer: 2000,
        showConfirmButton: false
    });
}

function setUploading(state) {
    const btn = document.getElementById('uploadBtn');
    if (state) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Uploading...';
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-upload me-2"></i> Upload Image';
    }
}

function fetchSliders() {
    fetch("/api/yuva-slider", {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        let container = document.getElementById("sliderList");
        let countBadge = document.getElementById("sliderCount");
        container.innerHTML = "";

        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-image-fill display-1 text-light mb-3 d-block"></i>
                    <p class="text-muted">No slider images found. Start by uploading one!</p>
                </div>`;
            countBadge.textContent = "0 Images";
            return;
        }

        countBadge.textContent = `${data.length} Images`;

        data.forEach(item => {
            container.innerHTML += `
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden hover-scale" style="border-radius: 1rem;">
                        <div class="position-relative">
                            <img src="${item.image}" class="card-img-top" style="height:180px;object-fit:cover;">
                            <div class="position-absolute top-0 end-0 p-2">
                                <button class="btn btn-danger btn-sm rounded-3 shadow" onclick="deleteSlider(${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-3 text-center">
                            <span class="badge bg-light text-dark border fw-normal">Slider #${item.id}</span>
                        </div>
                    </div>
                </div>
            `;
        });
    })
    .catch(err => {
        console.error(err);
        showError("Unable to load sliders.");
    });
}

document.getElementById("uploadForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let fileInput = document.getElementById("image");
    let file = fileInput.files[0];

    if (!file) {
        showError("Please select an image.");
        return;
    }

    const MAX_SIZE = 204800; // 200 KB
    if (file.size > MAX_SIZE) {
        showError("Image size must be less than 200 KB.");
        return;
    }

    let formData = new FormData();
    formData.append('image', file);

    setUploading(true);

    fetch("/api/yuva-slider", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async res => {
        setUploading(false);
        let json;
        try { json = await res.json(); } catch (err) { throw new Error("Invalid server response."); }

        if (!res.ok) {
            if (json && json.errors) {
                const first = Object.values(json.errors)[0];
                throw new Error(Array.isArray(first) ? first[0] : first);
            }
            throw new Error(json.error || "Upload failed.");
        }

        showSuccess(json.message || "Image uploaded successfully");
        this.reset();
        document.getElementById('imagePreview').classList.add('d-none');
        document.getElementById('dropZone').classList.remove('bg-light');
        fetchSliders();
    })
    .catch(err => {
        setUploading(false);
        showError(err.message || "Something went wrong");
    });
});

function deleteSlider(id) {
    Swal.fire({
        title: "Delete Image?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Yes, delete",
        cancelButtonText: "Cancel",
        borderRadius: '1rem'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(\`/api/yuva-slider/\${id}\`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async res => {
                let json;
                try { json = await res.json(); } catch(e) { json = {}; }
                if (!res.ok) throw new Error(json.message || "Delete failed");
                showSuccess(json.message || "Image deleted");
                fetchSliders();
            })
            .catch(err => showError(err.message));
        }
    });
}
</script>

<style>
    .upload-drop-zone {
        border: 2px dashed #e2e8f0;
        cursor: pointer;
    }
    .upload-drop-zone:hover {
        border-color: #6366f1;
        background-color: #f8fafc;
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .tracking-wider {
        letter-spacing: 0.05em;
    }
    .fw-600 {
        font-weight: 600;
    }
</style>
@endsection
