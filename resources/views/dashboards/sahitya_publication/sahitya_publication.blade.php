@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .homepage-card img {
        width: 100%;
        height: 200px;
        object-fit: contain;
        background-color: #f8f9fa;
    }
</style>

<div class="container my-4">
    <div class="row">
        <!-- Left: Form -->
        <div class="col-md-8">
            <h4 class="mb-3 text-primary">📚 Add / Edit Sahitya</h4>
            <form id="sahityaForm" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="edit_id" id="edit_id" />
                <div class="col-md-4">
                    <label>Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="naneshvani">नानेशवाणी साहित्य</option>
                        <option value="ram_uvach">राम उवाच साहित्य</option>
                        <option value="shri_ram_dhwani">श्री राम ध्वनि</option>
                        <option value="ram_darshan">राम दर्शन</option>
                        <option value="samta_katha_mala">समता कथा माला</option>
                        <option value="any">अन्य प्रकाशित साहित्य</option>
                        <option value="agam">आगम, अहिंसा-समता एवं प्राकृत संस्थान</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Book Name</label>
                    <input type="text" name="name" class="form-control" required />
                </div>
                <div class="col-md-4">
                    <label>Cover Photo (max 200KB)</label>
                    <input type="file" name="cover_photo" accept="image/*" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label>PDF (max 2MB, optional)</label>
                    <input type="file" name="pdf" accept="application/pdf" class="form-control" />
                </div>
                <div class="col-md-3">
                    <label>Preference</label>
                    <input type="number" name="preference" class="form-control" required />
                </div>
                <div class="col-md-3">
                    <label>Show on Homepage</label>
                    <select name="show_on_homepage" class="form-select">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">💾 Save</button>
                </div>
            </form>
        </div>

        <!-- Right: Homepage Book -->
        <div class="col-md-4">
            <h5 class="text-success">🏠 Homepage Book</h5>
            <div id="homepageBooks" class="homepage-card"></div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Category Filter -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Select Category</label>
            <select id="categorySelector" class="form-select" onchange="fetchByCategory(this.value)">
                <option value="">-- Select Category --</option>
                <option value="naneshvani">नानेशवाणी साहित्य</option>
                <option value="ram_uvach">राम उवाच साहित्य</option>
                <option value="shri_ram_dhwani">श्री राम ध्वनि</option>
                <option value="ram_darshan">राम दर्शन</option>
                <option value="samta_katha_mala">समता कथा माला</option>
                <option value="any">अन्य प्रकाशित साहित्य</option>
                <option value="agam">आगम, अहिंसा-समता एवं प्राकृत संस्थान</option>
            </select>
        </div>
    </div>

    <!-- Books Table -->
    <div id="allBooks"></div>
</div>

<script>
const categoryMap = {
    'naneshvani': 'नानेशवाणी साहित्य',
    'ram_uvach': 'राम उवाच साहित्य',
    'shri_ram_dhwani': 'श्री राम ध्वनि',
    'ram_darshan': 'राम दर्शन',
    'samta_katha_mala': 'समता कथा माला',
    'any': 'अन्य प्रकाशित साहित्य',
    'agam': 'आगम, अहिंसा-समता एवं प्राकृत संस्थान',
};

function renderHomepage(books) {
    const container = document.getElementById('homepageBooks');
    container.innerHTML = '';
    books.forEach(book => {
        container.innerHTML += `
            <div class="card">
                <img src="${book.cover_photo}" alt="Cover" />
                <div class="card-body">
                    <h6>${book.name}</h6>
                    <p class="text-muted">${categoryMap[book.category]}</p>
                </div>
            </div>
        `;
    });
}
function renderBooksTable(books) {
    const container = document.getElementById('allBooks');
    if (books.length === 0) return container.innerHTML = '<p class="text-muted">No books available.</p>';
    let table = `<table class="table table-bordered"><thead><tr><th>Image</th><th>Name & Category</th><th>PDF</th><th>Actions</th></tr></thead><tbody>`;
    books.forEach(book => {
        table += `<tr>
            <td><img src="${book.cover_photo}" width="80"></td>
            <td>
                <strong>${book.name}</strong><br>
                <small>${categoryMap[book.category]}</small><br>
                <small class="text-secondary">Preference: ${book.preference}</small>
            </td>
            <td>${book.pdf ? `<a href="${book.pdf}" target="_blank">View PDF</a>` : 'N/A'}</td>
            <td>
                <button class="btn btn-sm btn-info" onclick='editBook(${JSON.stringify(book)})'>✏️ Edit</button>
                <button class="btn btn-sm btn-success" onclick="setHomepageBook(${book.id})">🏠</button>
                <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">❌</button>
            </td>
        </tr>`;
    });
    table += '</tbody></table>';
    container.innerHTML = table;
}


async function fetchHomepageBook() {
    const res = await fetch('/api/sahitya/homepage-books');
    const books = await res.json();
    renderHomepage(books);
}

async function fetchByCategory(category) {
    if (!category) return document.getElementById('allBooks').innerHTML = '';
    const res = await fetch(`/api/sahitya/category/${category}`);
    const books = await res.json();
    renderBooksTable(books);
}

function editBook(book) {
    document.querySelector('[name="category"]').value = book.category;
    document.querySelector('[name="name"]').value = book.name;
    document.querySelector('[name="preference"]').value = book.preference;
    document.querySelector('[name="show_on_homepage"]').value = book.show_on_homepage;
    document.getElementById('edit_id').value = book.id;
    document.getElementById('sahityaForm').scrollIntoView({ behavior: 'smooth' });
    setTimeout(() => document.querySelector('[name="name"]').focus(), 500);
}

async function deleteBook(id) {
    if (!confirm('Delete this book?')) return;
    const res = await fetch(`/api/sahitya/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    if (res.ok) {
        fetchHomepageBook();
        fetchByCategory(document.getElementById('categorySelector').value);
    }
}

async function setHomepageBook(id) {
    await fetch(`/api/sahitya/set-homepage/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    fetchHomepageBook();
    fetchByCategory(document.getElementById('categorySelector').value);
}

// Handle Form Submit

const form = document.getElementById('sahityaForm');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const editId = document.getElementById('edit_id').value;
    const method = editId ? 'POST' : 'POST';
    const url = editId ? `/api/sahitya/${editId}?_method=PUT` : '/api/sahitya';

    const res = await fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    if (res.ok) {
        Swal.fire('✅ Success', editId ? 'Updated successfully' : 'Book added', 'success');
        form.reset();
        document.getElementById('edit_id').value = '';
        fetchHomepageBook();
        fetchByCategory(document.getElementById('categorySelector').value);
    } else {
        Swal.fire('❌ Error', 'Something went wrong', 'error');
    }
});

fetchHomepageBook();
</script>
@endsection
