@extends('includes.layouts.shree_sangh')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JSP Results</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .toast-container { position: fixed; top: 70px; right: 20px; z-index: 9999; }
    </style>
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4">JSP Result CRUD & Bulk Upload</h2>
    <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('jsp.bulk_results') }}" class="btn btn-success">
        Go to Bulk Upload Page
    </a>
</div>

    <form id="jspResultForm" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4"><input type="text" name="Student_Name" class="form-control" placeholder="Student Name"></div>
            <div class="col-md-4"><input type="text" name="Guardian_Name" class="form-control" placeholder="Guardian Name"></div>
            <div class="col-md-4"><input type="text" name="Mobile" class="form-control" placeholder="Mobile"></div>
            <div class="col-md-4"><input type="text" name="City" class="form-control" placeholder="City"></div>
            <div class="col-md-4"><input type="text" name="State" class="form-control" placeholder="State"></div>
            <div class="col-md-4"><input type="text" name="Class" class="form-control" placeholder="Class"></div>
            <div class="col-md-4"><input type="number" name="Marks" class="form-control" placeholder="Marks"></div>
            <div class="col-md-4"><input type="number" name="Rank" class="form-control" placeholder="Rank"></div>
            <div class="col-md-4"><input type="text" name="Remarks" class="form-control" placeholder="Remarks"></div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Add Result</button>
    </form>
    <form id="bulkUploadForm" enctype="multipart/form-data" class="mb-4">
        <div class="input-group">
           
        </div>
    </form> 
    <div id="resultsTable"></div>
</div>
<div class="toast-container" id="toastContainer"></div>
<script>
const apiUrl = '/api/jsp-result';
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0 show`;
    toast.role = 'alert';
    toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
function fetchResults() {
    fetch(apiUrl, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            let html = `<table class='table table-bordered'><thead><tr><th>ID</th><th>Student Name</th><th>Guardian Name</th><th>Mobile</th><th>City</th><th>State</th><th>Class</th><th>Marks</th><th>Rank</th><th>Remarks</th><th>Actions</th></tr></thead><tbody>`;
            data.forEach(row => {
                html += `<tr>
                    <td>${row.id}</td>
                    <td>${row.Student_Name||''}</td>
                    <td>${row.Guardian_Name||''}</td>
                    <td>${row.Mobile||''}</td>
                    <td>${row.City||''}</td>
                    <td>${row.State||''}</td>
                    <td>${row.Class||''}</td>
                    <td>${row.Marks||''}</td>
                    <td>${row.Rank||''}</td>
                    <td>${row.Remarks||''}</td>
                    <td>
                        <button class='btn btn-sm btn-warning' onclick='editResult(${row.id})'>Edit</button>
                        <button class='btn btn-sm btn-danger' onclick='deleteResult(${row.id})'>Delete</button>
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
            document.getElementById('resultsTable').innerHTML = html;
        });
}
document.getElementById('jspResultForm').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            showToast('Result added successfully', 'success');
            fetchResults();
            document.getElementById('jspResultForm').reset();
        } else {
            let errorMsg = 'Error adding result';
            if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join(', ');
            }
            showToast(errorMsg, 'danger');
        }
    })
    .catch(() => showToast('Error adding result', 'danger'));
};
document.getElementById('bulkUploadForm').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(apiUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            showToast('Bulk upload successful', 'success');
            fetchResults();
            this.reset();
        } else {
            showToast('Bulk upload failed', 'danger');
        }
    })
    .catch(() => showToast('Bulk upload failed', 'danger'));
};
function editResult(id) {
    fetch(`${apiUrl}/${id}`, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            Object.keys(data).forEach(key => {
                if(document.querySelector(`[name="${key}"]`)) {
                    document.querySelector(`[name="${key}"]`).value = data[key] || '';
                }
            });
            document.getElementById('jspResultForm').onsubmit = function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(`${apiUrl}/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest', 'X-HTTP-Method-Override': 'PUT' },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        showToast('Result updated', 'success');
                        fetchResults();
                        this.reset();
                        document.getElementById('jspResultForm').onsubmit = defaultFormSubmit;
                    } else {
                        showToast('Update failed', 'danger');
                    }
                })
                .catch(() => showToast('Update failed', 'danger'));
            };
        });
}
function deleteResult(id) {
    if(confirm('Delete this result?')) {
        fetch(`${apiUrl}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast('Result deleted', 'success');
                fetchResults();
            } else {
                showToast('Delete failed', 'danger');
            }
        })
        .catch(() => showToast('Delete failed', 'danger'));
    }
}
const defaultFormSubmit = document.getElementById('jspResultForm').onsubmit;
fetchResults();
</script>
</body>
</html>

<div class="container mt-5">
        <h2 class="text-center">Fetch Result</h2>
        <form id="fetchResultForm" method="POST">
            <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" class="form-control" id="class" name="class" required>
            </div>
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="mobile" name="mobile" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="fetchResult()">Submit</button>
        </form>
        <div id="result" class="mt-4"></div>
    </div>

    <script>
        async function fetchResult() {
            const classValue = document.getElementById('class').value;
            const mobileValue = document.getElementById('mobile').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch('/api/get-result', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ class: classValue, mobile: mobileValue }),
            });

            const resultDiv = document.getElementById('result');
            if (response.ok) {
                const data = await response.json();
                resultDiv.innerHTML = `<pre>${JSON.stringify(data.result, null, 2)}</pre>`;
            } else {
                const error = await response.json();
                resultDiv.innerHTML = `<p class="text-danger">${error.message || 'Error fetching result'}</p>`;
            }
        }
    </script>
@endsection
