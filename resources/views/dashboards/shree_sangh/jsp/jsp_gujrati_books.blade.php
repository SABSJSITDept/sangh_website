@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container py-4">
    <h2 class="mb-4 text-center">📘 JSP Gujarati Books</h2>

    <!-- Toast Alert (top-right) -->
<div id="toast" class="position-fixed end-0 z-3" style="top: 70px; min-width: 300px; right: 1rem;"></div>    <form id="bookForm" class="mb-4" enctype="multipart/form-data">
        <input type="hidden" id="bookId">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" id="name" placeholder="Book Name" required>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" id="preference" placeholder="Preference (Unique)" required>
            </div>
            <div class="col-md-4">
                <input type="file" class="form-control" id="pdf" accept="application/pdf">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Save</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>SR</th>
                    <th>Book Name</th>
                    <th>Preference</th>
                    <th>PDF</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody"></tbody>
        </table>
    </div>
</div>

<script>
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest'
};

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.innerHTML = `
        <div class="toast align-items-center text-bg-${type} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    setTimeout(() => {
        toast.innerHTML = '';
    }, 3000);
}

function fetchBooks() {
    fetch('/api/jsp-gujrati-books')
        .then(res => res.json())
        .then(data => {
            const tableBody = document.getElementById('bookTableBody');
            tableBody.innerHTML = '';
            data.forEach((book, index) => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${book.name}</td>
                        <td>${book.preference}</td>
                        <td><a href="/storage/${book.pdf}" target="_blank">View PDF</a></td>
                        <td>
                            <button onclick="editBook(${book.id})" class="btn btn-warning btn-sm">Edit</button>
                            <button onclick="deleteBook(${book.id})" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
}

document.getElementById('bookForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('bookId').value;
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('preference', document.getElementById('preference').value);
    const pdfFile = document.getElementById('pdf').files[0];
    if (pdfFile) formData.append('pdf', pdfFile);

    const url = id ? `/api/jsp-gujrati-books/${id}` : '/api/jsp-gujrati-books';
    const method = id ? 'POST' : 'POST';
    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method,
        headers,
        body: formData
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(data => {
        showToast(data.message || 'Success', 'success');
        document.getElementById('bookForm').reset();
        document.getElementById('bookId').value = '';
        fetchBooks();
    })
    .catch(err => {
        const errorMsg = err?.errors ? Object.values(err.errors).flat().join(', ') : 'Something went wrong!';
        showToast(errorMsg, 'danger');
    });
});

function editBook(id) {
    fetch(`/api/jsp-gujrati-books`)
        .then(res => res.json())
        .then(data => {
            const book = data.find(b => b.id === id);
            document.getElementById('name').value = book.name;
            document.getElementById('preference').value = book.preference;
            document.getElementById('bookId').value = book.id;
        });
}

function deleteBook(id) {
    if (!confirm('Are you sure to delete?')) return;
    fetch(`/api/jsp-gujrati-books/${id}`, {
        method: 'DELETE',
        headers
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, 'success');
        fetchBooks();
    })
    .catch(() => showToast('Delete failed!', 'danger'));
}

fetchBooks();
</script>
@endsection
