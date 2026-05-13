@extends('includes.layouts.yuva_sangh')

@section('title', 'Mobile App Slider Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Mobile App Slider</h2>
                <p class="text-muted small mb-0">Manage the home screen slider for the Yuva Sangh mobile application.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-phone text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 1.25rem; top: 100px;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0" id="formTitle">Upload Slider</h5>
                </div>
                <div class="card-body p-4">
                    <form id="sliderForm" enctype="multipart/form-data">
                        <input type="hidden" id="slider_id">
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Select Image</label>
                            <div class="upload-area border-dashed rounded-4 p-4 text-center mb-2 position-relative transition-all" id="dropZone">
                                <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3 d-block"></i>
                                <p class="small text-muted mb-0">Click or drag & drop</p>
                                <input type="file" name="image" id="imageInput" class="position-absolute inset-0 opacity-0 cursor-pointer w-100 h-100" accept="image/*" required>
                            </div>
                            <div id="previewContainer" class="mt-3 d-none text-center">
                                <!-- Preview injected via JS -->
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <i class="bi bi-info-circle text-info"></i>
                                <small class="text-muted">Max 200KB • Total limit: 5 images</small>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i> Save Slider
                            </button>
                            <button type="button" class="btn btn-light py-2 rounded-3 fw-bold border d-none" id="cancelBtn">
                                Cancel Edit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold outfit-font mb-0">Current Mobile Sliders</h5>
                        <span id="sliderCount" class="badge bg-light text-dark border rounded-pill px-3 py-2">0/5 Images</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 border-0">Preview</th>
                                    <th class="py-3 border-0">System Path</th>
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
const apiUrl = "/api/mobile-slider";
let sliderCount = 0; 

function showAlert(message, type="success") {
    Swal.fire({
        icon: type,
        title: type.charAt(0).toUpperCase() + type.slice(1),
        text: message,
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: "top-end",
        background: '#fff',
        color: '#1e293b'
    });
}

// Preview Logic
document.getElementById("imageInput").addEventListener("change", function() {
    const container = document.getElementById("previewContainer");
    const dropZone = document.getElementById("dropZone");
    container.innerHTML = "";
    const file = this.files[0];
    if (!file) return;

    if (file.size > 200 * 1024) {
        showAlert("Image must be less than 200KB!", "error");
        this.value = "";
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        const img = document.createElement("img");
        img.src = e.target.result;
        img.className = "img-fluid rounded-4 shadow-sm border";
        img.style.maxHeight = "150px";
        container.appendChild(img);
        container.classList.remove('d-none');
        dropZone.classList.add('bg-light');
    };
    reader.readAsDataURL(file);
});

async function fetchSliders() {
    try {
        let res = await fetch(apiUrl);
        let data = await res.json();
        sliderCount = data.length;
        document.getElementById("sliderCount").textContent = `${sliderCount}/5 Images`;
        
        let rows = "";
        if (data.length === 0) {
            rows = '<tr><td colspan="3" class="text-center py-5 text-muted">No mobile sliders found.</td></tr>';
        } else {
            data.forEach(slider => {
                rows += `
                    <tr class="transition-all">
                        <td class="ps-4 py-3">
                            <img src="${slider.image}" class="rounded-3 shadow-sm border" style="width:140px; height:60px; object-fit:cover;">
                        </td>
                        <td class="py-3 text-muted small text-truncate" style="max-width:200px;">${slider.image}</td>
                        <td class="pe-4 py-3 text-end">
                            <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                <button class="btn btn-sm btn-white border px-3" onclick="editSlider(${slider.id}, '${slider.image}')" title="Edit">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </button>
                                <button class="btn btn-sm btn-white border px-3" onclick="deleteSlider(${slider.id})" title="Delete">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            });
        }
        document.getElementById("sliderTable").innerHTML = rows;
    } catch (err) {
        showAlert("Failed to load sliders", "error");
    }
}

document.getElementById("sliderForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    let id = document.getElementById("slider_id").value;

    if (!id && sliderCount >= 5) {
        Swal.fire({ icon: "warning", title: "Limit Reached", text: "You can upload a maximum of 5 images only!", borderRadius: '1rem' });
        return;
    }

    let file = document.getElementById("imageInput").files[0];
    if (!file && !id) {
        showAlert("Please select an image!", "error");
        return;
    }

    const btn = document.getElementById("submitBtn");
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

    let formData = new FormData();
    if (file) formData.append("image", file);

    let url = id ? `${apiUrl}/${id}` : apiUrl;
    if (id) formData.append("_method", "PUT");

    try {
        let res = await fetch(url, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        let result = await res.json();
        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if (res.ok) {
            showAlert(result.message);
            fetchSliders();
            resetForm();
        } else {
            showAlert(result.message || "Error!", "error");
        }
    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showAlert("Connection error!", "error");
    }
});

function editSlider(id, imageUrl) { 
    document.getElementById("slider_id").value = id;
    document.getElementById("previewContainer").innerHTML = `
        <img src="${imageUrl}" class="img-fluid rounded-4 shadow-sm border mb-2" style="max-height:150px;">
        <p class="small text-muted mb-0">Replacing current image</p>
    `;
    document.getElementById("previewContainer").classList.remove('d-none');
    document.getElementById("submitBtn").innerHTML = '<i class="bi bi-arrow-repeat me-2"></i> Update Slider';
    document.getElementById("cancelBtn").classList.remove("d-none");
    document.getElementById("formTitle").textContent = "Edit Slider Image";
    document.getElementById("imageInput").required = false;

    window.scrollTo({ top: 0, behavior: "smooth" });
}

document.getElementById("cancelBtn").addEventListener("click", resetForm);

function resetForm() {
    document.getElementById("sliderForm").reset();
    document.getElementById("slider_id").value = "";
    document.getElementById("previewContainer").innerHTML = "";
    document.getElementById("previewContainer").classList.add('d-none');
    document.getElementById("dropZone").classList.remove('bg-light');
    document.getElementById("submitBtn").innerHTML = '<i class="bi bi-check-circle me-2"></i> Save Slider';
    document.getElementById("cancelBtn").classList.add("d-none");
    document.getElementById("formTitle").textContent = "Upload Slider";
    document.getElementById("imageInput").required = true;
}

async function deleteSlider(id) {
    Swal.fire({
        title: "Delete this slider?",
        text: "This image will be removed from the mobile app.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Yes, delete it",
        borderRadius: '1rem'
    }).then(async (result) => {
        if (result.isConfirmed) {
            let res = await fetch(`${apiUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let resultData = await res.json();
            if (res.ok) {
                showAlert(resultData.message);
                fetchSliders();
            } else {
                showAlert("Delete failed!", "error");
            }
        }
    });
}

fetchSliders();
</script>

<style>
    .upload-area { border: 2px dashed #e2e8f0; cursor: pointer; }
    .upload-area:hover { border-color: #6366f1; background-color: #f8fafc; }
    .border-dashed { border-style: dashed !important; }
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .btn-white { background-color: #fff; border-color: #e2e8f0; }
    .btn-white:hover { background-color: #f1f5f9; }
</style>
@endsection
