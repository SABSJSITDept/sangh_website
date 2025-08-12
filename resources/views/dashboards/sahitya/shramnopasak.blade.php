@extends('includes.layouts.sahitya')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .shram-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .shram-card {
        width: 180px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    .shram-thumb {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    .month-year {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 8px;
    }
    .pagination-btn {
        border: none;
        background-color: #eee;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
    }
    .pagination-btn.active {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }
    .pagination-btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }
</style>

<div class="container mt-4 mb-5">
    <h2 class="mb-4 text-center">📘 श्रमणोपासक</h2>

    <div class="row mb-5">
        <!-- Form -->
        <div class="col-md-5">
            <form id="shramForm" enctype="multipart/form-data" class="border p-3 rounded shadow-sm">
                <input type="hidden" id="editId">

                <div class="mb-3">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-select" required id="yearSelect"></select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select" required id="monthSelect">
                        <option value="">Select Month</option>
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $month)
                            <option value="{{ $month }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cover Photo (Max 200KB)</label>
                    <input type="file" name="cover_photo" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">File Type</label><br>
                    <input type="radio" name="file_type" value="pdf" checked onchange="toggleFileType('pdf')"> PDF
                    <input type="radio" name="file_type" value="drive" class="ms-3" onchange="toggleFileType('drive')"> Google Drive Link
                </div>

                <div class="mb-3" id="pdfUploadField">
                    <label class="form-label">PDF (Max 2MB)</label>
                    <input type="file" name="pdf" class="form-control" accept="application/pdf">
                </div>

                <div class="mb-3 d-none" id="driveLinkField">
                    <label class="form-label">Google Drive Link</label>
                    <input type="url" name="drive_link" class="form-control" placeholder="https://drive.google.com/...">
                </div>

                <button class="btn btn-primary" type="submit" id="submitBtn">Upload</button>
                <button type="button" class="btn btn-secondary ms-2 d-none" id="cancelEdit" onclick="cancelEdit()">Cancel</button>
            </form>
        </div>

        <!-- Latest Entry -->
        <div class="col-md-7 d-flex align-items-center justify-content-center">
            <div class="card shadow-sm p-3 w-100" style="max-width: 360px;">
                <div class="text-center">
                    <h6 class="text-muted mb-2">Latest Entry</h6>
                    <div id="latestCover" class="mb-2"></div>
                    <p class="mb-1 small fw-semibold" id="latestDate"></p>
                    <a id="readMoreBtn" href="#" target="_blank" class="btn btn-sm btn-outline-primary d-none">📄 Read More</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards -->
    <div class="shram-container" id="shramCards"></div>
    <div class="pagination-container" id="paginationControls"></div>
</div>

<script>
let allData = [];
let currentPage = 1;
const perPage = 12;

// Toggle PDF / Drive fields
function toggleFileType(type) {
    document.getElementById('pdfUploadField').classList.toggle('d-none', type !== 'pdf');
    document.getElementById('driveLinkField').classList.toggle('d-none', type !== 'drive');
}

// Handle form submit
document.getElementById('shramForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const id = document.getElementById('editId').value;
    const url = id ? `/api/shramnopasak/${id}` : '/api/shramnopasak';
    if (id) formData.append('_method', 'PUT');

    const year = formData.get('year');
    const month = formData.get('month');
    const fileType = formData.get('file_type');
    const cover = formData.get('cover_photo');
    const pdf = formData.get('pdf');
    const driveLink = formData.get('drive_link');

    // Frontend validation with SweetAlert
    if (!year || !month) return Swal.fire('Error', 'Year and Month are required', 'error');
    if (!id && (!cover || cover.size === 0)) return Swal.fire('Error', 'Cover photo is required', 'error');
    if (cover && cover.size > 200 * 1024) return Swal.fire('Error', 'Cover photo must be less than 200KB', 'error');

    if (fileType === 'pdf') {
        if (!id && (!pdf || pdf.size === 0)) return Swal.fire('Error', 'PDF file is required', 'error');
        if (pdf && pdf.size > 2 * 1024 * 1024) return Swal.fire('Error', 'PDF must be less than 2MB', 'error');
    }
    if (fileType === 'drive') {
        if (!driveLink) return Swal.fire('Error', 'Google Drive link is required', 'error');
        const urlPattern = /^https?:\/\/(drive\.google\.com|docs\.google\.com)\/.+$/;
        if (!urlPattern.test(driveLink)) return Swal.fire('Error', 'Invalid Google Drive link', 'error');
    }

    Swal.fire({
        title: 'Uploading...',
        text: 'Please wait while we upload your file.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });
    const result = await res.json();

    Swal.close();

    if (result.status === 'success') {
        Swal.fire('Success', id ? 'Updated Successfully' : 'Uploaded Successfully', 'success');
        form.reset();
        cancelEdit();
        fetchAllShramData();
        fetchLatest();
        toggleFileType('pdf'); // reset
    } else {
        Swal.fire('Error', 'Something went wrong', 'error');
    }
});

function fetchAllShramData() {
    fetch('/api/shramnopasak')
        .then(res => res.json())
        .then(response => {
            allData = Array.isArray(response) ? response : response.data;
            renderPage(currentPage);
            renderPaginationControls();
        });
}

function renderPage(page) {
    const start = (page - 1) * perPage;
    const sliced = allData.slice(start, start + perPage);
    const container = document.getElementById("shramCards");
    container.innerHTML = "";

    sliced.forEach(item => {
        container.innerHTML += `
        <div class="shram-card" id="shram-card-${item.id}">
            <img src="${item.cover_photo ? '/storage/' + item.cover_photo : 'https://via.placeholder.com/150'}" class="shram-thumb" />
            <div class="month-year">${item.month} ${item.year}</div>
            ${item.file_type === 'pdf'
                ? (item.pdf ? `<a href="/storage/${item.pdf}" target="_blank" class="btn btn-sm btn-outline-primary w-100">📄 Read</a>` : `<div class="text-muted">No PDF</div>`)
                : (item.drive_link ? `<a href="${item.drive_link}" target="_blank" class="btn btn-sm btn-outline-primary w-100">🔗 View Drive</a>` : `<div class="text-muted">No Link</div>`)}
            <button onclick='editItem(${JSON.stringify(item)})' class="btn btn-sm btn-warning mt-2 w-100">✏️ Edit</button>
            <button onclick="confirmDelete(${item.id})" class="btn btn-sm btn-danger mt-1 w-100">🗑️ Delete</button>
        </div>`;
    });
}

function renderPaginationControls() {
    const totalPages = Math.ceil(allData.length / perPage);
    const container = document.getElementById("paginationControls");
    container.innerHTML = `
        <button class="pagination-btn" onclick="gotoPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>
        ${Array.from({ length: totalPages }, (_, i) => `
            <button class="pagination-btn ${currentPage === i + 1 ? 'active' : ''}" onclick="gotoPage(${i + 1})">${i + 1}</button>
        `).join('')}
        <button class="pagination-btn" onclick="gotoPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>
    `;
}

function gotoPage(page) {
    currentPage = page;
    renderPage(page);
    renderPaginationControls();
}

function cancelEdit() {
    document.getElementById('editId').value = '';
    document.getElementById('shramForm').reset();
    document.getElementById('submitBtn').innerText = 'Upload';
    document.getElementById('cancelEdit').classList.add('d-none');
}

function editItem(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('yearSelect').value = item.year;
    document.getElementById('monthSelect').value = item.month;
    document.querySelector(`input[name="file_type"][value="${item.file_type}"]`).checked = true;
    toggleFileType(item.file_type);
    if (item.file_type === 'drive') {
        document.querySelector('input[name="drive_link"]').value = item.drive_link || '';
    }
    document.getElementById('submitBtn').innerText = 'Update';
    document.getElementById('cancelEdit').classList.remove('d-none');
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this record?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            deleteItem(id);
        }
    });
}

function deleteItem(id) {
    fetch(`/api/shramnopasak/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(res => res.json())
      .then(res => {
        if (res.status === "deleted") {
            document.getElementById(`shram-card-${id}`).remove();
            Swal.fire('Deleted!', 'Record has been deleted.', 'success');
        }
    });
}

function fetchLatest() {
    fetch("/api/shramnopasak/latest")
        .then(res => res.json())
        .then(data => {
            const item = data.data;
            const preview = document.getElementById("latestCover");
            const dateEl = document.getElementById("latestDate");
            const readBtn = document.getElementById("readMoreBtn");

            if (item) {
                preview.innerHTML = item.cover_photo
                    ? `<img src="/storage/${item.cover_photo}" class="img-fluid rounded shadow-sm" style="max-height:180px;">`
                    : `<div class="text-muted">No Cover</div>`;
                dateEl.innerText = `${item.month} ${item.year}`;
                if (item.file_type === 'pdf' && item.pdf) {
                    readBtn.href = `/storage/${item.pdf}`;
                    readBtn.classList.remove("d-none");
                } else if (item.file_type === 'drive' && item.drive_link) {
                    readBtn.href = item.drive_link;
                    readBtn.classList.remove("d-none");
                } else {
                    readBtn.classList.add("d-none");
                }
            }
        });
}

function populateYearDropdown() {
    const yearSelect = document.getElementById("yearSelect");
    const currentYear = new Date().getFullYear();
    for (let year = 2015; year <= currentYear; year++) {
        const option = document.createElement("option");
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }
}

window.onload = function () {
    populateYearDropdown();
    fetchAllShramData();
    fetchLatest();
};
</script>
@endsection
