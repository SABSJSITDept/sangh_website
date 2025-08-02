@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">📋 JSP बेसिक (List View)</h2>

    <!-- Form -->
    <form id="jspForm" class="mb-4" enctype="multipart/form-data">
        <input type="hidden" id="recordId">
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea id="content" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="dtp" class="form-label">Upload Image</label>
            <input type="file" id="dtp" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <!-- List View Table -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📄 JSP Records List</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Content</th>
                        <th>Image</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="jspList"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const apiUrl = "/api/jsp-basic";

    function fetchAll() {
        fetch(apiUrl)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.content}</td>
                            <td>
                                <img src="/storage/${item.dtp}" alt="Image" style="height: 80px; max-width: 120px; object-fit: cover;">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning me-2" onclick="editRecord(${item.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteRecord(${item.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('jspList').innerHTML = html;
            });
    }

    function editRecord(id) {
        fetch(`${apiUrl}/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('recordId').value = data.id;
                document.getElementById('content').value = data.content;
                // For security reasons, file input cannot be pre-filled
            });
    }

    function deleteRecord(id) {
        fetch(`${apiUrl}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(() => fetchAll());
    }

    document.getElementById('jspForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('recordId').value;
        const url = id ? `${apiUrl}/${id}` : apiUrl;
        const method = 'POST'; // Resource controller handles POST for both create & update

        const formData = new FormData();
        formData.append('content', document.getElementById('content').value);
        const file = document.getElementById('dtp').files[0];
        if (file) formData.append('dtp', file);

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        }).then(() => {
            document.getElementById('jspForm').reset();
            document.getElementById('recordId').value = '';
            fetchAll();
        });
    });

    fetchAll();
</script>
@endsection
