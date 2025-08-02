@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h2 class="mb-4 text-center">📘 श्रमणोपासक</h2>

    <div class="row">
        <!-- Upload Form -->
        <div class="col-md-5">
            <form id="shramForm" enctype="multipart/form-data" class="border p-3 rounded shadow-sm">
                <input type="hidden" id="editId" value="">
                <div class="mb-3">
                    <label for="yearSelect" class="form-label">Year</label>
                    <select name="year" class="form-select" required id="yearSelect">
                        <option value="">Select Year</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="monthSelect" class="form-label">Month</label>
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

        <!-- Latest Entry Preview -->
        <div class="col-md-7">
            <div class="card shadow-sm h-100 text-center">
                <div id="latestPreview" class="p-3">
                    <h5 class="text-muted">Latest Entry</h5>
                    <div id="latestCover"></div>
                    <p class="mt-2 fw-bold" id="latestDate"></p>
                    <a id="readMoreBtn" href="#" target="_blank" class="btn btn-outline-primary btn-sm mt-2 d-none">📄 Read More</a>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- Toast Alert -->
    <div id="toast" class="toast align-items-center text-white bg-success border-0 position-fixed end-0 m-3"
        role="alert" style="top: 70px; z-index: 9999;">
        <div class="d-flex">
            <div class="toast-body" id="toastMsg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="hideToast()"></button>
        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Cover Photo</th>
                <th>PDF</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="shramTableBody"></tbody>
    </table>
</div>

<script>
document.getElementById('shramForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const id = document.getElementById('editId').value;
    const url = id ? `/api/shramnopasak/${id}` : '/api/shramnopasak';

    if (id) formData.append('_method', 'PUT');

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    const result = await response.json();
    if (result.status === 'success') {
        showToast(id ? "Updated Successfully!" : "Uploaded Successfully!");
        this.reset();
        document.getElementById('editId').value = '';
        document.getElementById('submitBtn').innerText = 'Upload';
        document.getElementById('cancelEdit').classList.add('d-none');
        fetchData();
        fetchLatest();
    }
});

function fetchData() {
    fetch("/api/shramnopasak")
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("shramTableBody");
            tbody.innerHTML = "";
            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.year}</td>
                        <td>${item.month}</td>
                        <td>${item.cover_photo ? `<a href="/storage/${item.cover_photo}" target="_blank">View</a>` : '-'}</td>
                        <td>${item.pdf ? `<a href="/storage/${item.pdf}" target="_blank">Download</a>` : '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-info me-1" onclick='editItem(${JSON.stringify(item)})'>Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function deleteItem(id) {
    fetch(`/api/shramnopasak/${id}`, {
        method: "DELETE",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(res => res.json())
      .then(res => {
        if (res.status === "deleted") {
            showToast("Deleted Successfully!");
            fetchData();
            fetchLatest();
        }
    });
}

function editItem(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('yearSelect').value = item.year;
    document.getElementById('monthSelect').value = item.month;
    document.getElementById('submitBtn').innerText = 'Update';
    document.getElementById('cancelEdit').classList.remove('d-none');
}

function cancelEdit() {
    document.getElementById('editId').value = '';
    document.getElementById('shramForm').reset();
    document.getElementById('submitBtn').innerText = 'Upload';
    document.getElementById('cancelEdit').classList.add('d-none');
}

function showToast(message) {
    const toastEl = document.getElementById("toast");
    document.getElementById("toastMsg").innerText = message;
    toastEl.classList.add("show");
    setTimeout(() => toastEl.classList.remove("show"), 3000);
}

function hideToast() {
    document.getElementById("toast").classList.remove("show");
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

function fetchLatest() {
    fetch("/api/shramnopasak/latest")
        .then(res => res.json())
        .then(data => {
            const preview = document.getElementById("latestCover");
            const dateEl = document.getElementById("latestDate");
            const readBtn = document.getElementById("readMoreBtn");

            if (data.status === 'success' && data.data) {
                const item = data.data;
                preview.innerHTML = item.cover_photo 
                    ? `<img src="/storage/${item.cover_photo}" class="img-fluid rounded shadow" style="max-height:300px;" alt="Cover">`
                    : `<div class="text-muted">No Cover Photo</div>`;
                dateEl.innerText = `${item.month} ${item.year}`;
                if (item.pdf) {
                    readBtn.href = `/storage/${item.pdf}`;
                    readBtn.classList.remove("d-none");
                } else {
                    readBtn.classList.add("d-none");
                }
            } else {
                preview.innerHTML = '<p class="text-muted">No latest entry found.</p>';
                dateEl.innerText = '';
                readBtn.classList.add("d-none");
            }
        });
}

window.onload = function () {   
    populateYearDropdown();
    fetchData();
    fetchLatest();
}
</script>
@endsection
