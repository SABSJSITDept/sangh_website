@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4">
    <h3 class="mb-3">Yuva Slider Management</h3>

    <!-- Upload Form -->
    <form id="uploadForm" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            <small class="text-muted">Max size: 200 KB</small>
        </div>
        <button type="submit" id="uploadBtn" class="btn btn-primary">Upload Image</button>
    </form>

    <!-- Image List -->
    <div id="sliderList" class="row g-3"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetchSliders();
});

function showError(msg) {
    Swal.fire("Error", msg, "error");
}

function showSuccess(msg) {
    Swal.fire("Success", msg, "success");
}

function setUploading(state) {
    const btn = document.getElementById('uploadBtn');
    if (state) {
        btn.disabled = true;
        btn.textContent = 'Uploading...';
    } else {
        btn.disabled = false;
        btn.textContent = 'Upload Image';
    }
}

function fetchSliders() {
    fetch("/api/yuva-slider", {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (!res.ok) throw new Error("Failed to fetch sliders");
        return res.json();
    })
    .then(data => {
        let container = document.getElementById("sliderList");
        container.innerHTML = "";

        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = `<div class="col-12"><p class="text-muted">No sliders found.</p></div>`;
            return;
        }

        data.forEach(item => {
            // Ensure the image URL is safe to use; backend should provide correct path
            container.innerHTML += `
                <div class="col-md-3 text-center">
                    <div class="card">
                        <img src="${item.image}" class="card-img-top" style="height:150px;object-fit:cover;">
                        <div class="card-body p-2">
                            <button class="btn btn-danger btn-sm w-100" onclick="deleteSlider(${item.id})">Delete</button>
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

    // Size Restriction (200 KB = 204800 bytes)
    const MAX_SIZE = 204800;
    if (file.size > MAX_SIZE) {
        showError("Image size must be less than 200 KB.");
        return;
    }

    // Optional: check file type (image)
    if (!file.type.startsWith("image/")) {
        showError("Selected file is not an image.");
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
            // NOTE: Do NOT set Content-Type here; browser will set multipart/form-data with boundary.
        },
        body: formData
    })
    .then(async res => {
        setUploading(false);
        // expecting JSON response
        let json;
        try {
            json = await res.json();
        } catch (err) {
            throw new Error("Invalid server response.");
        }

        if (!res.ok) {
            // server might send validation errors
            if (json && json.error) throw new Error(json.error);
            if (json && json.errors) {
                // collect first validation message
                const first = Object.values(json.errors)[0];
                throw new Error(Array.isArray(first) ? first[0] : first);
            }
            throw new Error("Upload failed.");
        }

        if (json.error) {
            showError(json.error);
        } else {
            showSuccess(json.message || "Image uploaded successfully");
            this.reset();
            fetchSliders();
        }
    })
    .catch(err => {
        console.error(err);
        setUploading(false);
        showError(err.message || "Something went wrong");
    });
});

function deleteSlider(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This image will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/yuva-slider/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async res => {
                let json;
                try { json = await res.json(); } catch(e) { json = {}; }
                if (!res.ok) {
                    throw new Error(json.message || "Delete failed");
                }
                Swal.fire("Deleted!", json.message || "Image deleted", "success");
                fetchSliders();
            })
            .catch(err => {
                console.error(err);
                showError(err.message || "Something went wrong");
            });
        }
    });
}
</script>
@endsection
