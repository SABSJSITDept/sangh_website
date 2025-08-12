    @extends('includes.layouts.sahitya_publication')

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
        .blurred {
            filter: blur(3px);
            pointer-events: none;
            opacity: 0.6;
        }
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
    </style>

    <!-- Spinner -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container my-4" id="mainContainer">
        <div class="row">
            <!-- Form Section -->
            <div class="col-md-8">
                <h4 class="mb-3 text-primary">📚 Add / Edit Sahitya</h4>
                <form id="sahityaForm" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id" />

                    <div class="col-md-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach([
                                'naneshvani' => 'नानेशवाणी साहित्य',
                                'ram_uvach' => 'राम उवाच साहित्य',
                                'shri_ram_dhwani' => 'श्री राम ध्वनि',
                                'ram_darshan' => 'राम दर्शन',
                                'samta_katha_mala' => 'समता कथा माला',
                                'any' => 'अन्य प्रकाशित साहित्य',
                                'agam' => 'आगम, अहिंसा-समता एवं प्राकृत संस्थान'
                            ] as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Book Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"  />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cover Photo (max 200KB)</label>
                        <input type="file" name="cover_photo" accept="image/*" class="form-control" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">File Type</label>
                        <select name="file_type" id="file_type" class="form-select" onchange="toggleFileType()" required>
                            <option value="pdf">PDF Upload</option>
                            <option value="drive">Google Drive Link</option>
                        </select>
                    </div>

                    <div class="col-md-6 file-input">
                        <label class="form-label">PDF (max 20MB)</label>
                        <input type="file" name="pdf" accept="application/pdf" class="form-control" />
                    </div>

                    <div class="col-md-6 drive-input" style="display:none;">
                        <label class="form-label">Google Drive Link</label>
                        <input type="url" name="drive_link" class="form-control" placeholder="https://drive.google.com/..." />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Preference</label>
                        <input type="number" name="preference" class="form-control" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Show on Homepage</label>
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

            <!-- Homepage Preview -->
            <div class="col-md-4">
                <h5 class="text-success">🏠 Homepage Book</h5>
                <div id="homepageBooks" class="homepage-card"></div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Category Filter -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Select Category</label>
                <select id="categorySelector" class="form-select" onchange="fetchByCategory(this.value)">
                    <option value="">-- Select Category --</option>
                    @foreach([
                        'naneshvani' => 'नानेशवाणी साहित्य',
                        'ram_uvach' => 'राम उवाच साहित्य',
                        'shri_ram_dhwani' => 'श्री राम ध्वनि',
                        'ram_darshan' => 'राम दर्शन',
                        'samta_katha_mala' => 'समता कथा माला',
                        'any' => 'अन्य प्रकाशित साहित्य',
                        'agam' => 'आगम, अहिंसा-समता एवं प्राकृत संस्थान'
                    ] as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
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
        'agam': 'आगम, अहिंसा-समता एवं प्राकृत संस्थान'
    };

    function toggleFileType() {
        const type = document.getElementById('file_type').value;
        document.querySelector('.file-input').style.display = (type === 'pdf') ? 'block' : 'none';
        document.querySelector('.drive-input').style.display = (type === 'drive') ? 'block' : 'none';
    }

    function renderHomepage(books) {
        const container = document.getElementById('homepageBooks');
        container.innerHTML = books.length
            ? books.map(book => `
                <div class="card mb-2">
                    <img src="${book.cover_photo}" alt="Cover" />
                    <div class="card-body">
                        <h6>${book.name}</h6>
                        <p class="text-muted">${categoryMap[book.category]}</p>
                    </div>
                </div>`).join('')
            : '<p class="text-muted">No homepage books set.</p>';
    }

    function renderBooksTable(books) {
        const container = document.getElementById('allBooks');
        if (!books.length) {
            container.innerHTML = '<p class="text-muted">No books available.</p>';
            return;
        }
        container.innerHTML = `
            <table class="table table-bordered">
                <thead>
                    <tr><th>Image</th><th>Name & Category</th><th>File</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    ${books.map(book => `
                        <tr>
                            <td><img src="${book.cover_photo}" width="80"></td>
                            <td>
                                <strong>${book.name}</strong><br>
                                <small>${categoryMap[book.category]}</small><br>
                                <small class="text-secondary">Preference: ${book.preference ?? '-'}</small>
                            </td>
                            <td>
                                ${book.file_type === 'pdf' && book.pdf
                                    ? `<a href="${book.pdf}" target="_blank">View PDF</a>`
                                    : book.file_type === 'drive' && book.drive_link
                                        ? `<a href="${book.drive_link}" target="_blank">Drive Link</a>`
                                        : 'N/A'}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick='editBook(${JSON.stringify(book)})'>✏️ Edit</button>
                                <button class="btn btn-sm btn-success" onclick="setHomepageBook(${book.id})">🏠</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">❌</button>
                            </td>
                        </tr>`).join('')}
                </tbody>
            </table>
        `;
    }

    async function fetchHomepageBook() {
        try {
            const res = await fetch('/api/sahitya/homepage-books');
            renderHomepage(await res.json());
        } catch {
            Swal.fire('❌ Error', 'Failed to load homepage books.', 'error');
        }
    }

    async function fetchByCategory(category) {
        if (!category) {
            document.getElementById('allBooks').innerHTML = '';
            return;
        }
        try {
            const res = await fetch(`/api/sahitya/category/${category}`);
            renderBooksTable(await res.json());
        } catch {
            Swal.fire('❌ Error', 'Failed to load category books.', 'error');
        }
    }

    function editBook(book) {
        form.category.value = book.category;
        form.name.value = book.name;
        form.preference.value = book.preference ?? '';
        form.show_on_homepage.value = book.show_on_homepage;
        form.file_type.value = book.file_type || 'pdf';
        toggleFileType();
        form.drive_link.value = book.file_type === 'drive' ? (book.drive_link || '') : '';
        document.getElementById('edit_id').value = book.id;
        form.scrollIntoView({ behavior: 'smooth' });
    }

    async function deleteBook(id) {
        if (!confirm('Delete this book?')) return;
        try {
            await fetch(`/api/sahitya/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            fetchHomepageBook();
            fetchByCategory(document.getElementById('categorySelector').value);
        } catch {
            Swal.fire('❌ Error', 'Failed to delete book.', 'error');
        }
    }

   async function setHomepageBook(id) {
    try {
        const response = await fetch(`/api/sahitya/set-homepage/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (response.ok) {
            Swal.fire('✅ Success', 'Homepage book set successfully.', 'success');
            fetchHomepageBook();
            fetchByCategory(document.getElementById('categorySelector').value);
        } else {
            Swal.fire('❌ Error', 'Failed to set homepage book.', 'error');
        }
    } catch {
        Swal.fire('❌ Error', 'Failed to set homepage book.', 'error');
    }
}
    

    // Form submission
    const form = document.getElementById('sahityaForm');
    form.addEventListener('submit', async e => {
        e.preventDefault();

        const { category, name, file_type, cover_photo, pdf, drive_link } = form;
        const coverFile = cover_photo.files[0];
        const pdfFile = pdf.files[0];

        if (!category.value.trim() || !name.value.trim()) {
            return Swal.fire('⚠️ Required', 'Please fill all required fields.', 'warning');
        }
        if (coverFile && coverFile.size > 200 * 1024) {
            return Swal.fire('⚠️ File too large', 'Cover photo must be less than 200KB.', 'warning');
        }
        if (file_type.value === 'pdf' && pdfFile && pdfFile.size > 20 * 1024 * 1024) {
            return Swal.fire('⚠️ File too large', 'PDF must be less than 20MB.', 'warning');
        }
        if (file_type.value === 'drive' && !drive_link.value.trim()) {
            return Swal.fire('⚠️ Required', 'Please enter Google Drive link.', 'warning');
        }

        document.getElementById('loadingOverlay').style.display = 'flex';
        document.getElementById('mainContainer').classList.add('blurred');

        const formData = new FormData(form);
        const editId = document.getElementById('edit_id').value;
        const url = editId ? `/api/sahitya/${editId}?_method=PUT` : '/api/sahitya';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            });

            if (!res.ok) throw new Error();

            Swal.fire('✅ Success', editId ? 'Updated successfully' : 'Book added', 'success');
            form.reset();
            document.getElementById('edit_id').value = '';
            toggleFileType();
            fetchHomepageBook();
            fetchByCategory(document.getElementById('categorySelector').value);
        } catch {
            Swal.fire('❌ Error', 'Something went wrong', 'error');
        } finally {
            document.getElementById('loadingOverlay').style.display = 'none';
            document.getElementById('mainContainer').classList.remove('blurred');
        }
    });

    fetchHomepageBook();
    </script>
    @endsection
