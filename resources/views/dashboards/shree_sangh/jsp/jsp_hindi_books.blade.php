@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📚 JSP Hindi Books</h2>
    </div>

    <!-- 📤 Add/Edit Form -->
    <form id="bookForm" enctype="multipart/form-data" class="card shadow p-4 mb-4">
        <input type="hidden" id="bookId">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="name" class="form-label">📘 Book Name</label>
                <input type="text" id="name" class="form-control" placeholder="Enter book title..." required>
            </div>
            <div class="col-md-5">
                <label for="pdf" class="form-label">📄 Upload PDF (max 2MB)</label>
                <input type="file" id="pdf" class="form-control" accept=".pdf">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-upload"></i> <span id="formButtonText">Submit</span>
                </button>
            </div>
        </div>
    </form>

    <!-- 📄 Book Table -->
    <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-warning text-dark">
                <tr>
                    <th>ID</th>
                    <th>Book Name</th>
                    <th>PDF</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookList">
                <tr><td colspan="4">⏳ Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Toast Alert -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
    <div id="toast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMsg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
};

const toast = new bootstrap.Toast(document.getElementById('toast'));
const showToast = (msg, success = true) => {
    const toastEl = document.getElementById('toast');
    toastEl.classList.remove('bg-success', 'bg-danger');
    toastEl.classList.add(success ? 'bg-success' : 'bg-danger');
    document.getElementById('toastMsg').innerText = msg;
    toast.show();
};

function loadBooks() {
    fetch('/api/jsp-hindi-books')
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('bookList');
            if (data.length === 0) {
                list.innerHTML = `<tr><td colspan="4">📭 कोई बुक नहीं मिली।</td></tr>`;
                return;
            }
            list.innerHTML = '';
            data.forEach((book, index) => {
                list.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${book.name}</td>
                        <td>
                            <a href="/storage/${book.pdf}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-pdf"></i> View
                            </a>
                        </td>
                        <td>
                            <button onclick="editBook(${book.id})" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button onclick="deleteBook(${book.id})" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        });
}

function editBook(id) {
    fetch(`/api/jsp-hindi-books/${id}`)
        .then(res => res.json())
        .then(book => {
            document.getElementById('bookId').value = book.id;
            document.getElementById('name').value = book.name;
            document.getElementById('formButtonText').textContent = 'Update';
        });
}

document.getElementById('bookForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('bookId').value;
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);

    const fileInput = document.getElementById('pdf');
    if (fileInput.files.length > 0) {
        formData.append('pdf', fileInput.files[0]);
    }

    const method = id ? 'POST' : 'POST';
    const url = id ? `/api/jsp-hindi-books/${id}?_method=PUT` : '/api/jsp-hindi-books';

    fetch(url, {
        method: method,
        headers,
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message || 'Success');
        document.getElementById('bookForm').reset();
        document.getElementById('bookId').value = '';
        document.getElementById('formButtonText').textContent = 'Submit';
        loadBooks();
    })
    .catch(err => showToast('Error occurred', false));
});

function deleteBook(id) {
    if (!confirm('क्या आप वाकई इस बुक को हटाना चाहते हैं?')) return;

    fetch(`/api/jsp-hindi-books/${id}`, {
        method: 'DELETE',
        headers
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message || 'Deleted');
        loadBooks();
    })
    .catch(() => showToast('Error deleting book', false));
}

loadBooks();
</script>
@endsection
