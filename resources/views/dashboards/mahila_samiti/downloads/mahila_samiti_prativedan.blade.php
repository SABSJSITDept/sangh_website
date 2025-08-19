@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .toast-container {
        z-index: 2000;
    }
    .toast {
        opacity: 0.95;
        transition: all 0.3s ease-in-out;
    }
    .table th {
        background: #f8f9fa;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4 text-center fw-bold text-primary">
        <i class="bi bi-journal-text"></i> महिला समिति प्रतिवेदन
    </h2>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3"></div>

    <!-- Form Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="bi bi-pencil-square"></i> Add / Update प्रतिवेदन
        </div>
        <div class="card-body">
            <form id="prativedanForm" class="row g-3">
                <input type="hidden" id="id">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">नाम</label>
                    <input type="text" class="form-control" id="name" placeholder="नाम दर्ज करें" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Google Drive लिंक</label>
                    <input type="url" class="form-control" id="google_drive_link" placeholder="लिंक पेस्ट करें" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save2"></i> Save
                    </button>
                    <button type="reset" class="btn btn-secondary px-4" onclick="resetForm()">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold">
            <i class="bi bi-table"></i> प्रतिवेदन सूची
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle" id="prativedanTable">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">नाम</th>
                        <th style="width: 40%;">Google Drive लिंक</th>
                        <th style="width: 20%;">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    fetchData();

    const form = document.getElementById("prativedanForm");

    form.addEventListener("submit", function(e){
        e.preventDefault();
        let id = document.getElementById("id").value;
        let name = document.getElementById("name").value;
        let link = document.getElementById("google_drive_link").value;

        let url = "/api/mahila_prativedan" + (id ? `/${id}` : "");
        let method = id ? "PUT" : "POST";

        fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({name: name, google_drive_link: link})
        })
        .then(res => res.json())
        .then(data => {
            if(data.errors){
                showToast(Object.values(data.errors).join("<br>"), "danger");
            } else {
                showToast(id ? "Updated Successfully ✅" : "Added Successfully ✅", "success");
                resetForm();
                fetchData();
            }
        })
        .catch(() => showToast("Something went wrong!", "danger"));
    });
});

function fetchData(){
    fetch("/api/mahila_prativedan")
    .then(res => res.json())
    .then(data => {
        let tbody = document.querySelector("#prativedanTable tbody");
        tbody.innerHTML = "";
        data.forEach((row, index) => {
            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${row.name}</td>
                    <td><a href="${row.google_drive_link}" class="text-decoration-none" target="_blank"><i class="bi bi-link-45deg"></i> View Link</a></td>
                    <td>
                        <button class="btn btn-sm btn-warning me-2" onclick="editData(${row.id}, '${row.name}', '${row.google_drive_link}')">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(${row.id})">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        });
    });
}

function editData(id, name, link){
    document.getElementById("id").value = id;
    document.getElementById("name").value = name;
    document.getElementById("google_drive_link").value = link;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function deleteData(id){
    if(confirm("Are you sure you want to delete this record?")){
        fetch(`/api/mahila_prativedan/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.json())
        .then(() => {
            showToast("Deleted Successfully 🗑️", "success");
            fetchData();
        });
    }
}

function resetForm(){
    document.getElementById("prativedanForm").reset();
    document.getElementById("id").value = "";
}

function showToast(message, type){
    let container = document.querySelector(".toast-container");
    let toast = document.createElement("div");
    toast.className = `toast align-items-center text-bg-${type} border-0 fade show mb-2 shadow`;
    toast.innerHTML = `<div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
@endsection
