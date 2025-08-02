@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">📄 JSP Big Exam</h2>

    <!-- 🔔 Toast for Validation/Error Messages -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="toastMessage" class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <form id="bigexamForm" class="mb-4" enctype="multipart/form-data">
        <input type="hidden" id="updateId">
        <div class="row g-2 mb-2">
            <div class="col-md-4">
                <input type="text" name="name" id="name" class="form-control" placeholder="Exam Name" required>
            </div>
            <div class="col-md-4">
                <input type="file" name="pdf" id="pdf" class="form-control" accept="application/pdf">
            </div>
            <div class="col-md-4">
                <input type="number" name="priority" id="priority" class="form-control" placeholder="Priority (e.g. 1)" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <table class="table table-bordered" id="bigexamTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>PDF</th>
                <th>Priority</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchBigexams();

    const form = document.getElementById('bigexamForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const id = document.getElementById('updateId').value;
        const method = 'POST';
        const url = id ? `/api/jsp-bigexam/${id}` : '/api/jsp-bigexam';
        if (id) formData.append('_method', 'PUT');

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => {
            if (res.ok) {
                return res.json();
            } else {
                return res.json().then(err => { throw err; });
            }
        })
        .then(() => {
            form.reset();
            document.getElementById('updateId').value = '';
            fetchBigexams();
        })
        .catch(err => {
            let message = 'Something went wrong!';
            if (err.errors) {
                message = Object.values(err.errors)[0][0];
            }
            showToast(message);
        });
    });
});

function fetchBigexams() {
    fetch('/api/jsp-bigexam')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#bigexamTable tbody');
            tbody.innerHTML = '';
            data.forEach(entry => {
                tbody.innerHTML += `
                    <tr>
                        <td>${entry.name}</td>
                        <td><a href="/storage/${entry.pdf}" target="_blank">📄 View PDF</a></td>
                        <td>${entry.priority}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editBigexam(${entry.id}, '${entry.name}', ${entry.priority})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBigexam(${entry.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function editBigexam(id, name, priority) {
    document.getElementById('updateId').value = id;
    document.getElementById('name').value = name;
    document.getElementById('priority').value = priority;
}

function deleteBigexam(id) {
    if (confirm('Are you sure?')) {
        fetch(`/api/jsp-bigexam/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(() => fetchBigexams());
    }
}

function showToast(message) {
    const toastBody = document.getElementById('toastBody');
    toastBody.textContent = message;

    const toastElement = new bootstrap.Toast(document.getElementById('toastMessage'));
    toastElement.show();
}
</script>
@endsection
