@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">📄 JSP Old Papers</h2>

    <!-- Toast -->
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    <!-- Form -->
    <form id="paperForm" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Class</label>
                <select class="form-select" name="class" required>
                    <option value="">Select Class</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Year</label>
                <select class="form-select" name="year" required>
                    <option value="">Select Year</option>
                    @for ($i = 0; $i < 5; $i++)
                        <option value="{{ now()->year - $i }}">{{ now()->year - $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">PDF (Max 2MB)</label>
                <input type="file" class="form-control" name="pdf" accept="application/pdf" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Upload</button>
    </form>

    <!-- Table -->
    <table class="table table-bordered" id="papersTable">
        <thead>
            <tr>
                <th>Class</th>
                <th>Year</th>
                <th>PDF</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchData();

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0 show`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    function fetchData() {
        fetch('/api/jsp-old-papers')
            .then(res => res.json())
            .then(data => {
                let tbody = '';
                Object.keys(data).forEach(classNum => {
                    tbody += `
                        <tr class="table-secondary">
                            <th colspan="4" class="text-start">📘 Class ${classNum}</th>
                        </tr>`;
                    data[classNum].forEach(paper => {
                        tbody += `
                            <tr>
                                <td>${paper.class}</td>
                                <td>${paper.year}</td>
                                <td><a href="/storage/${paper.pdf}" target="_blank">View PDF</a></td>
                                <td><button class="btn btn-danger btn-sm" onclick="deletePaper(${paper.id})">Delete</button></td>
                            </tr>`;
                    });
                });
                document.querySelector('#papersTable tbody').innerHTML = tbody;
            });
    }

    document.getElementById('paperForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = new FormData(this);

        fetch('/api/jsp-old-papers', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: form
        })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                Object.values(data.errors).forEach(msgs =>
                    msgs.forEach(msg => showToast(msg, 'danger'))
                );
            } else {
                showToast(data.message, 'success');
                this.reset();
                fetchData();
            }
        })
        .catch(() => showToast('Something went wrong!', 'danger'));
    });

    window.deletePaper = function (id) {
        if (!confirm('Are you sure you want to delete this paper?')) return;

        fetch(`/api/jsp-old-papers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message, 'success');
            fetchData();
        });
    }
});
</script>
@endsection
