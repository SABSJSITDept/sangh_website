@extends('includes.layouts.sahitya')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

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
        <!-- Upload Form -->
        <div class="col-md-5">
            <form id="shramForm" enctype="multipart/form-data" class="border p-3 rounded shadow-sm">
                <input type="hidden" id="editId" value="">
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
                    <label class="form-label">PDF (Max 2MB)</label>
                    <input type="file" name="pdf" class="form-control" accept="application/pdf">
                </div>
                <button class="btn btn-primary" type="submit" id="submitBtn">Upload</button>
                <button type="button" class="btn btn-secondary ms-2 d-none" id="cancelEdit" onclick="cancelEdit()">Cancel</button>
            </form>
        </div>

        <!-- Latest Preview -->
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

    <!-- All Cards -->
    <div class="shram-container" id="shramCards"></div>
    <div class="pagination-container" id="paginationControls"></div>
</div>

<script>
    let allData = [];
    let currentPage = 1;
    const perPage = 12;

    document.getElementById('shramForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('editId').value;
        const url = id ? `/api/shramnopasak/${id}` : '/api/shramnopasak';
        if (id) formData.append('_method', 'PUT');

        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        const result = await res.json();
        if (result.status === 'success') {
            this.reset();
            cancelEdit();
            fetchAllShramData();
            fetchLatest();
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

    function fetchLatest() {
        fetch('/api/shramnopasak/latest')
            .then(res => res.json())
            .then(data => {
                const preview = document.getElementById("latestCover");
                const dateEl = document.getElementById("latestDate");
                const readBtn = document.getElementById("readMoreBtn");
                if (data.status === 'success') {
                    const item = data.data;
                    preview.innerHTML = item.cover_photo
                        ? `<img src="/storage/${item.cover_photo}" class="img-fluid rounded shadow-sm" style="max-height:180px;">`
                        : `<div class="text-muted">No Cover</div>`;
                    dateEl.innerText = `${item.month} ${item.year}`;
                    if (item.pdf) {
                        readBtn.href = `/storage/${item.pdf}`;
                        readBtn.classList.remove("d-none");
                    } else {
                        readBtn.classList.add("d-none");
                    }
                }
            });
    }

    function renderPage(page) {
        const start = (page - 1) * perPage;
        const sliced = allData.slice(start, start + perPage);
        const container = document.getElementById("shramCards");
        container.innerHTML = "";
        sliced.forEach(item => {
            container.innerHTML += `
                <div class="shram-card">
                    <img src="${item.cover_photo ? '/storage/' + item.cover_photo : 'https://via.placeholder.com/150'}" class="shram-thumb" />
                    <div class="month-year">${item.month} ${item.year}</div>
                    ${item.pdf ? `<a href="/storage/${item.pdf}" target="_blank" class="btn btn-sm btn-outline-primary w-100">📄 Read</a>` : `<div class="text-muted">No PDF</div>`}
                </div>`;
        });
    }

    function renderPaginationControls() {
        const totalPages = Math.ceil(allData.length / perPage);
        const container = document.getElementById("paginationControls");
        container.innerHTML = "";
        container.innerHTML += `<button class="pagination-btn" onclick="gotoPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>`;
        for (let i = 1; i <= totalPages; i++) {
            container.innerHTML += `<button class="pagination-btn ${currentPage === i ? 'active' : ''}" onclick="gotoPage(${i})">${i}</button>`;
        }
        container.innerHTML += `<button class="pagination-btn" onclick="gotoPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>`;
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

    function populateYearDropdown() {
        const yearSelect = document.getElementById("yearSelect");
        const currentYear = new Date().getFullYear();
        for (let year = 2000; year <= currentYear + 5; year++) {
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
    }
</script>
@endsection