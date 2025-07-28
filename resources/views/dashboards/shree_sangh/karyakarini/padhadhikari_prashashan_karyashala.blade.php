@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">पदाधिकारी प्रशासन कार्यशाला</h2>

    <!-- Toast Alert -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="toastAlert" class="toast align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMsg"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Form -->
    <form id="uploadForm" enctype="multipart/form-data" class="mb-4">
        <input type="hidden" id="recordId" />
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="name" id="name" class="form-control" placeholder="नाम" required>
            </div>
            <div class="col-md-6">
                <input type="file" name="pdf" id="pdf" accept="application/pdf" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3" id="submitBtn">सेव करें</button>
        <button type="button" class="btn btn-secondary mt-3 ms-2 d-none" id="cancelEdit">Cancel Edit</button>
    </form>

    <div class="row" id="pdfList"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', fetchData);

function fetchData() {
    fetch('/api/padhadhikari-prashashan-karyashala')
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach(item => {
                html += `
                    <div class="col-md-4 mb-3">
                        <div class="card shadow">
                            <div class="card-body">
                                <h5>${item.name}</h5>
                                <a href="/storage/${item.pdf}" target="_blank" class="btn btn-sm btn-outline-success">View PDF</a>
                                <button onclick="editRecord(${item.id})" class="btn btn-sm btn-warning float-end ms-2">Edit</button>
                                <button onclick="deletePdf(${item.id})" class="btn btn-sm btn-danger float-end">Delete</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            document.getElementById('pdfList').innerHTML = html;
        });
}

// Toast
function showToast(message) {
    document.getElementById('toastMsg').textContent = message;
    new bootstrap.Toast(document.getElementById('toastAlert')).show();
}

// Handle Submit (Add or Edit)
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const file = document.getElementById('pdf').files[0];
    const id = document.getElementById('recordId').value;
    const isEdit = id !== '';

    if (file && file.size > 2 * 1024 * 1024) {
        showToast("PDF size must be 2MB or less.");
        return;
    }

    const url = isEdit ? `/api/padhadhikari-prashashan-karyashala/${id}` : '/api/padhadhikari-prashashan-karyashala';
    const method = isEdit ? 'POST' : 'POST';

    if (isEdit) {
        formData.append('_method', 'PUT'); // Laravel expects _method for PUT via POST
    }

    fetch(url, {
        method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then((data) => {
        if (data.status === false && data.errors) {
            showToast("Validation failed.");
        } else {
            this.reset();
            document.getElementById('recordId').value = '';
            document.getElementById('submitBtn').textContent = 'सेव करें';
            document.getElementById('cancelEdit').classList.add('d-none');
            fetchData();
        }
    });
});

// Load data into form for editing
function editRecord(id) {
    fetch(`/api/padhadhikari-prashashan-karyashala/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('recordId').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('submitBtn').textContent = 'Update';
            document.getElementById('cancelEdit').classList.remove('d-none');
        });
}

// Cancel Edit
document.getElementById('cancelEdit').addEventListener('click', function () {
    document.getElementById('uploadForm').reset();
    document.getElementById('recordId').value = '';
    document.getElementById('submitBtn').textContent = 'सेव करें';
    this.classList.add('d-none');
});

// Delete Record
function deletePdf(id) {
    if (!confirm('Are you sure to delete?')) return;

    fetch(`/api/padhadhikari-prashashan-karyashala/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(() => fetchData());
}
</script>
@endsection
