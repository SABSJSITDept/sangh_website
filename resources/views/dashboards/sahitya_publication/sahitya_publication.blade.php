@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ✅ Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .homepage-book-img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
</style>

<div class="container my-4">
    <div class="row">
        <!-- 📥 Left: Upload Form -->
        <div class="col-md-8">
            <h4 class="text-primary mb-3">📚 Add Sahitya Publication</h4>
            <form id="sahityaForm" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label>Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="naneshvani">नानेशवाणी साहित्य</option>
                        <option value="ram_uvach">राम उवाच साहित्य</option>
                        <option value="shri_ram_dhwani">श्री राम ध्वनि</option>
                        <option value="ram_darshan">राम दर्शन</option>
                        <option value="samta_katha_mala">समता कथा माला</option>
                        <option value="any">अन्य प्रकाशित साहित्य</option>
                        <option value="agam">आगम, अहिंसा-समता और प्राकृत संस्थान</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Book Name</label>
                    <input type="text" name="name" class="form-control" required />
                </div>
                <div class="col-md-6">
                    <label>Cover Photo (max 200KB)</label>
                    <input type="file" name="cover_photo" accept="image/*" class="form-control" required />
                </div>
                <div class="col-md-6">
                    <label>PDF (max 2MB, optional)</label>
                    <input type="file" name="pdf" accept="application/pdf" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label>Preference</label>
                    <input type="number" name="preference" class="form-control" required />
                </div>
                <div class="col-md-6">
                    <label>Show on Homepage</label>
                    <select name="show_on_homepage" class="form-select">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">➕ Add Book</button>
                </div>
            </form>
        </div>

        <!-- 🏠 Right: Homepage Book -->
        <div class="col-md-4">
            <h5 class="text-success">🏠 Homepage Book</h5>
            <div id="homepageBooks"></div>
        </div>
    </div>

    <hr class="my-4">

    <!-- 📂 Category Filter -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Select Category to View Books</label>
            <select id="categorySelector" class="form-select" onchange="fetchByCategory(this.value)">
                <option value="">-- Select Category --</option>
                <option value="naneshvani">नानेशवाणी साहित्य</option>
                <option value="ram_uvach">राम उवाच साहित्य</option>
                <option value="shri_ram_dhwani">श्री राम ध्वनि</option>
                <option value="ram_darshan">राम दर्शन</option>
                <option value="samta_katha_mala">समता कथा माला</option>
                <option value="any">अन्य प्रकाशित साहित्य</option>
                <option value="agam">आगम, अहिंसा-समता और प्राकृत संस्थान</option>
            </select>
        </div>
    </div>

    <!-- 📖 List View -->
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
    'agam': 'आगम, अहिंसा-समता और प्राकृत संस्थान'
};

function renderHomepage(bookList) {
    const box = document.getElementById('homepageBooks');
    box.innerHTML = '';
    if (bookList.length === 0) {
        box.innerHTML = '<div class="text-muted">No homepage book set.</div>';
        return;
    }
    const book = bookList[0];
    box.innerHTML = `
        <div class="card">
            <img src="${book.cover_photo}" class="homepage-book-img" alt="Cover">
            <div class="card-body">
                <h6 class="fw-bold">${book.name}</h6>
                <small>${categoryMap[book.category] || book.category}</small>
            </div>
        </div>
    `;
}

function renderTable(container, books) {
    container.innerHTML = '';
    if (books.length === 0) {
        container.innerHTML = '<div class="text-muted">No books found.</div>';
        return;
    }
    const table = document.createElement('table');
    table.className = 'table table-bordered';
    table.innerHTML = `
        <thead class="table-light">
            <tr>
                <th>Cover</th>
                <th>Name</th>
                <th>PDF</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            ${books.map(book => `
                <tr>
                    <td><img src="${book.cover_photo}" width="80" height="100" style="object-fit: cover;"></td>
                    <td>${book.name}</td>
                    <td>${book.pdf ? `<a href="${book.pdf}" target="_blank">View PDF</a>` : '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-success me-1" onclick="setHomepageBook(${book.id})">🏠</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">🗑️</button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    container.appendChild(table);
}

document.getElementById('sahityaForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('/api/sahitya', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const data = await res.json();

        if (res.ok) {
            Swal.fire('✅ Success', 'Book added successfully.', 'success');
            form.reset();
            fetchHomepageBook();
            const selected = document.getElementById('categorySelector').value;
            if (selected) fetchByCategory(selected);
        } else {
            Swal.fire('❌ Error', data.message || 'Validation failed', 'error');
        }
    } catch (err) {
        Swal.fire('❌ Error', 'Server error occurred', 'error');
    }
});

async function fetchHomepageBook() {
    const res = await fetch('/api/sahitya/homepage-books');
    const books = await res.json();
    renderHomepage(books);
}

async function fetchByCategory(category) {
    const container = document.getElementById('allBooks');
    if (!category) {
        container.innerHTML = '<div class="text-muted">Please select a category.</div>';
        return;
    }
    try {
        const res = await fetch(`/api/sahitya/category/${category}`);
        const books = await res.json();
        renderTable(container, books);
    } catch {
        container.innerHTML = '<div class="text-danger">Failed to load books.</div>';
    }
}

async function deleteBook(id) {
    const confirm = await Swal.fire({
        title: 'Are you sure?',
        text: 'This will delete the book permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    });
    if (!confirm.isConfirmed) return;

    const res = await fetch(`/api/sahitya/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    if (res.ok) {
        Swal.fire('✅ Deleted', 'Book removed.', 'success');
        fetchHomepageBook();
        const selected = document.getElementById('categorySelector').value;
        if (selected) fetchByCategory(selected);
    } else {
        Swal.fire('❌ Error', 'Failed to delete.', 'error');
    }
}

async function setHomepageBook(id) {
    const confirm = await Swal.fire({
        title: 'Set as homepage?',
        text: 'This will replace the current homepage book.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, set it!'
    });
    if (!confirm.isConfirmed) return;

    const res = await fetch(`/api/sahitya/set-homepage/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    const data = await res.json();
    if (res.ok) {
        Swal.fire('✅ Updated', data.message, 'success');
        fetchHomepageBook();
        const selected = document.getElementById('categorySelector').value;
        if (selected) fetchByCategory(selected);
    } else {
        Swal.fire('❌ Error', data.message || 'Failed', 'error');
    }
}

fetchHomepageBook();
</script>
@endsection
