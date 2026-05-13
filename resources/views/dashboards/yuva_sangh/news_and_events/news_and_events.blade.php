@extends('includes.layouts.yuva_sangh')

@section('title', 'Yuva News & Events')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Yuva News & Events</h2>
                <p class="text-muted small mb-0">Publish community updates and manage upcoming events.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-newspaper text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add News Form Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 1.25rem; top: 100px;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0">Create New Update</h5>
                </div>
                <div class="card-body p-4">
                    <form id="newsForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-uppercase text-muted">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3" placeholder="Enter headline..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-uppercase text-muted">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="4" placeholder="Share details about this news..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Feature Image</label>
                            <div class="upload-area border rounded-3 p-3 text-center bg-light cursor-pointer" onclick="document.getElementById('newsPhoto').click()">
                                <i class="bi bi-camera-fill fs-3 text-muted d-block mb-2"></i>
                                <span class="small text-muted" id="fileName">Select image (Max 200KB)</span>
                                <input type="file" name="photo" id="newsPhoto" class="d-none" accept="image/*">
                            </div>
                            <div id="addPreview" class="d-none mt-2">
                                <img src="" class="img-fluid rounded-3 border shadow-sm" style="max-height: 150px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold">
                            <i class="bi bi-plus-circle me-2"></i> Publish News
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- News List Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold outfit-font mb-0">News Feed</h5>
                        <span id="newsCount" class="badge bg-light text-dark border rounded-pill px-3 py-2">0 Updates</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="newsList">
                        <!-- Loaded via AJAX -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Edit Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <form id="editNewsForm" enctype="multipart/form-data">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0">Edit News Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="editNewsId" name="id">
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-uppercase text-muted">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editTitle" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-uppercase text-muted">Description</label>
                        <textarea name="description" id="editDescription" class="form-control rounded-3" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-uppercase text-muted">Replace Image</label>
                        <input type="file" name="photo" id="editPhoto" class="form-control rounded-3" accept="image/*">
                        <div class="form-text small">Leave empty if you don't want to change.</div>
                    </div>
                    <div id="editPreview" class="mt-2 text-center"></div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update News</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    fetchNews();

    // Image Preview Logic for Add Form
    const newsPhoto = document.getElementById('newsPhoto');
    const addPreview = document.getElementById('addPreview');
    const addPreviewImg = addPreview.querySelector('img');
    const fileNameText = document.getElementById('fileName');

    newsPhoto.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            fileNameText.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                addPreviewImg.src = e.target.result;
                addPreview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    // Add News Form Submit
    document.getElementById("newsForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let photo = formData.get("photo");

        if (photo.size && photo.size > 200 * 1024) {
            Swal.fire("Error", "Image must be less than 200KB", "error");
            return;
        }

        const btn = this.querySelector('button[type="submit"]');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Publishing...';

        fetch("/api/yuva-news", {
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
            if(response.error){
                Swal.fire("Error", response.error, "error");
            } else {
                Swal.fire({ icon: "success", title: "Published", text: response.message, timer: 1500, showConfirmButton: false });
                this.reset();
                addPreview.classList.add('d-none');
                fileNameText.textContent = "Select image (Max 200KB)";
                fetchNews();
            }
        });
    });

    // Edit News Form Submit
    document.getElementById("editNewsForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let id = document.getElementById("editNewsId").value;
        let formData = new FormData(this);
        formData.append("_method", "PUT");

        const btn = this.querySelector('button[type="submit"]');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';

        fetch(`/api/yuva-news/${id}`, {
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
            if(response.error){
                Swal.fire("Error", response.error, "error");
            } else {
                Swal.fire({ icon: "success", title: "Updated", text: response.message, timer: 1500, showConfirmButton: false });
                bootstrap.Modal.getInstance(document.getElementById("editNewsModal")).hide();
                fetchNews();
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            Swal.fire("Error", "Something went wrong!", "error");
        });
    });
});

let allNews = [];
function fetchNews() {
    fetch("/api/yuva-news")
        .then(res => res.json())
        .then(data => {
            allNews = data;
            let container = document.getElementById("newsList");
            document.getElementById("newsCount").textContent = `${data.length} Updates`;
            
            let html = '<div class="table-responsive"><table class="table table-hover align-middle mb-0">';
            html += '<thead class="bg-light text-muted small text-uppercase"><tr>';
            html += '<th class="ps-4 py-3 border-0">Update</th>';
            html += '<th class="py-3 border-0">Content</th>';
            html += '<th class="py-3 border-0 text-center">Actions</th></tr></thead><tbody>';

            if (data.length === 0) {
                html += '<tr><td colspan="3" class="text-center py-5 text-muted">No news added yet.</td></tr>';
            } else {
                data.forEach((item, index) => {
                    html += `
                        <tr class="transition-all">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        ${item.photo 
                                            ? `<img src="${item.photo}" class="rounded-3 shadow-sm" style="width:50px;height:50px;object-fit:cover;">`
                                            : `<div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width:50px;height:50px;"><i class="bi bi-image"></i></div>`
                                        }
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold outfit-font text-dark">${item.title ?? ""}</h6>
                                        <small class="text-muted">ID: #${item.id}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <p class="mb-0 small text-muted text-truncate" style="max-width:300px;">${item.description ?? "No description provided."}</p>
                            </td>
                            <td class="py-3 text-center pe-4">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    <button class="btn btn-sm btn-white border px-3" onclick="openEdit(${index})">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </button>
                                    <button class="btn btn-sm btn-white border px-3" onclick="deleteNews(${item.id})">
                                        <i class="bi bi-trash3 text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
            html += '</tbody></table></div>';
            container.innerHTML = html;
        });
}

function deleteNews(id) {
    Swal.fire({
        title: "Delete this news?",
        text: "This action is permanent and cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        borderRadius: '1rem'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/yuva-news/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(response => {
                Swal.fire({ icon: "success", title: "Deleted", text: response.message, timer: 1500, showConfirmButton: false });
                fetchNews();
            });
        }
    });
}

function openEdit(index) {
    const item = allNews[index];
    if(!item) return;
    document.getElementById("editNewsId").value = item.id;
    document.getElementById("editTitle").value = item.title ?? '';
    document.getElementById("editDescription").value = item.description ?? '';
    document.getElementById("editPreview").innerHTML = item.photo ? `<img src="${item.photo}" class="img-fluid rounded-3 border shadow-sm mt-2" style="max-height:150px;">` : "";
    let modal = new bootstrap.Modal(document.getElementById("editNewsModal"));
    modal.show();
}
</script>

<style>
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .cursor-pointer { cursor: pointer; }
    .btn-white { background-color: #fff; border-color: #e2e8f0; }
    .btn-white:hover { background-color: #f1f5f9; }
    .upload-area:hover { border-color: #6366f1 !important; background-color: #f1f5f9 !important; }
</style>
@endsection
