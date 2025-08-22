@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card:hover {
        transform: translateY(-5px);
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .news-img {
        height: 200px;
        object-fit: cover;
    }
</style>

<div class="container py-4">
    <h3 class="mb-4 text-center">📰 Yuva News & Events</h3>

    <!-- Add Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Add News</strong>
        </div>
        <div class="card-body">
            <form id="newsForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Photo (max 200KB)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-success w-100">➕ Add News</button>
            </form>
        </div>
    </div>

    <!-- News List -->
    <div id="newsList" class="row g-3"></div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="editNewsForm" enctype="multipart/form-data">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">✏️ Edit News</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editNewsId" name="id">

                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="editTitle" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Photo (max 200KB)</label>
                    <input type="file" name="photo" id="editPhoto" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty if you don't want to change.</small>
                </div>
                <div id="editPreview" class="mb-2"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">💾 Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetchNews();

    // ✅ Add Form Submit
    document.getElementById("newsForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        let title = formData.get("title").trim();
        let description = formData.get("description").trim();
        let photo = formData.get("photo");

        if (!title) {
            Swal.fire("Error", "Title is required", "error");
            return;
        }
        if (!description && !photo.size) {
            Swal.fire("Error", "Either Description or Photo is required", "error");
            return;
        }
        if (photo.size && photo.size > 200 * 1024) {
            Swal.fire("Error", "Image must be less than 200KB", "error");
            return;
        }

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
            if(response.error){
                Swal.fire("Error", response.error, "error");
            } else {
                Swal.fire("Success", response.message, "success");
                this.reset();
                fetchNews();
            }
        });
    });

    // ✅ Edit Form Submit
    document.getElementById("editNewsForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let id = document.getElementById("editNewsId").value;
        let formData = new FormData(this);

        // Method spoofing for Laravel
        formData.append("_method", "PUT");

        let title = formData.get("title").trim();
        let description = formData.get("description").trim();
        let photo = formData.get("photo");

        if (!title) {
            Swal.fire("Error", "Title is required", "error");
            return;
        }
        if (!description && !photo.size) {
            Swal.fire("Error", "Either Description or Photo is required", "error");
            return;
        }
        if (photo.size && photo.size > 200 * 1024) {
            Swal.fire("Error", "Image must be less than 200KB", "error");
            return;
        }

        fetch(`/api/yuva-news/${id}`, {
            method: "POST",   // ✅ POST + _method=PUT
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            if(response.error){
                Swal.fire("Error", response.error, "error");
            } else {
                Swal.fire("Updated", response.message, "success");
                document.getElementById("editNewsForm").reset();
                bootstrap.Modal.getInstance(document.getElementById("editNewsModal")).hide();
                fetchNews();
            }
        })
        .catch(() => Swal.fire("Error", "Something went wrong!", "error"));
    });
});

// ✅ Fetch All News
function fetchNews() {
    fetch("/api/yuva-news")
        .then(res => res.json())
        .then(data => {
            let html = `
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 20%">Title</th>
                            <th style="width: 45%">Description</th>
                            <th style="width: 20%">Photo</th>
                            <th style="width: 10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            if (data.length === 0) {
                html += `<tr><td colspan="5" class="text-center text-muted">No news added yet.</td></tr>`;
            } else {
                data.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.title ?? ""}</td>
                            <td>${item.description ?? ""}</td>
                            <td>
                                ${item.photo 
                                    ? `<img src="${item.photo}" class="img-thumbnail" style="height:80px; width:auto;">`
                                    : `<span class="text-muted">No Image</span>`
                                }
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm me-1" onclick="openEdit(${item.id}, '${item.title ?? ''}', \`${item.description ?? ''}\`, '${item.photo ?? ''}')">✏️</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteNews(${item.id})">🗑</button>
                            </td>
                        </tr>
                    `;
                });
            }

            html += `
                    </tbody>
                </table>
            </div>
            `;

            document.getElementById("newsList").innerHTML = html;
        });
}


// ✅ Delete News
function deleteNews(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This will delete the news permanently!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel"
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
                Swal.fire("Deleted", response.message, "success");
                fetchNews();
            });
        }
    });
}

// ✅ Open Edit Modal
function openEdit(id, title, description, photo) {
    document.getElementById("editNewsId").value = id;
    document.getElementById("editTitle").value = title;
    document.getElementById("editDescription").value = description;
    document.getElementById("editPreview").innerHTML = photo ? `<img src="${photo}" class="img-fluid rounded border">` : "";

    let modal = new bootstrap.Modal(document.getElementById("editNewsModal"));
    modal.show();
}
</script>
@endsection
