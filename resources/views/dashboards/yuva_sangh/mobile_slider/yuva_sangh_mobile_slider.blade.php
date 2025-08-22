@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h3 class="mb-4">📱 Mobile Slider</h3>

    <!-- Upload Form -->
    <form id="sliderForm" enctype="multipart/form-data" class="mb-4 card p-3 shadow-sm">
        <input type="hidden" id="slider_id">
        <div class="mb-3">
            <label class="form-label fw-bold">Upload Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            <div class="form-text text-muted">Max 200KB, only 1 image at a time (Total limit: 5 images)</div>
        </div>
        <div id="previewContainer" class="mb-3"></div>
        <button type="submit" class="btn btn-primary" id="submitBtn">Save</button>
        <button type="button" class="btn btn-secondary d-none" id="cancelBtn">Cancel</button>
    </form>

    <!-- Table -->
    <table class="table table-bordered table-hover align-middle shadow-sm">
        <thead class="table-light">
            <tr>
                <th style="width:150px;">Preview</th>
                <th style="width:200px;">Action</th>
            </tr>
        </thead>
        <tbody id="sliderTable"></tbody>
    </table>
</div>

<script>
const apiUrl = "/api/mobile-slider";
let sliderCount = 0; 

// SweetAlert Wrapper
function showAlert(message, type="success") {
    Swal.fire({
        icon: type,
        text: message,
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: "top-end"
    });
}

// Preview Selected Image
document.getElementById("image").addEventListener("change", function() {
    const container = document.getElementById("previewContainer");
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
        img.width = 120;
        img.className = "rounded border shadow-sm";
        container.appendChild(img);
    };
    reader.readAsDataURL(file);
});

// Fetch Sliders
async function fetchSliders() {
    let res = await fetch(apiUrl);
    let data = await res.json();
    sliderCount = data.length;
    let rows = "";
    data.forEach(slider => {
        rows += `
            <tr>
                <td><img src="${slider.image}" width="120" class="rounded border shadow-sm"></td>
                <td>
                    <button class="btn btn-sm btn-warning me-2" onclick="editSlider(${slider.id}, '${slider.image}')">✏️ Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteSlider(${slider.id})">🗑️ Delete</button>
                </td>
            </tr>`;
    });
    document.getElementById("sliderTable").innerHTML = rows;
}

// Add / Update Slider
document.getElementById("sliderForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    let id = document.getElementById("slider_id").value;

    // Limit check
    if (!id && sliderCount >= 5) {
        Swal.fire("Limit Reached!", "You can upload a maximum of 5 images only!", "warning");
        return;
    }

    let file = document.getElementById("image").files[0];
    if (!file) {
        showAlert("Please select an image!", "error");
        return;
    }
    if (file.size > 200 * 1024) {
        showAlert("Image must be less than 200KB!", "error");
        return;
    }

    let formData = new FormData();
    formData.append("image", file);

    let url = id ? `${apiUrl}/${id}` : apiUrl;
    let method = "POST";
    if (id) formData.append("_method", "PUT");

    let res = await fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    let result = await res.json();
    if (res.ok) {
        showAlert(result.message);
        fetchSliders();
        resetForm();
    } else {
        showAlert(result.message || "Error!", "error");
    }
});

// Edit
function editSlider(id, imageUrl) { 
    document.getElementById("slider_id").value = id;
    document.getElementById("previewContainer").innerHTML = `
        <img src="${imageUrl}" width="120" class="rounded border me-2 shadow-sm">
        <span class="text-muted">Select a new image to replace</span>
    `;
    document.getElementById("submitBtn").textContent = "Update";
    document.getElementById("cancelBtn").classList.remove("d-none");

    showAlert("You can now update this image", "info");
    document.getElementById("sliderForm").scrollIntoView({ behavior: "smooth", block: "start" });
}

// Cancel Edit
document.getElementById("cancelBtn").addEventListener("click", resetForm);

function resetForm() {
    document.getElementById("sliderForm").reset();
    document.getElementById("slider_id").value = "";
    document.getElementById("previewContainer").innerHTML = "";
    document.getElementById("submitBtn").textContent = "Save";
    document.getElementById("cancelBtn").classList.add("d-none");
}

// Delete
async function deleteSlider(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This image will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
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

// Initial load
fetchSliders();
</script>
@endsection
