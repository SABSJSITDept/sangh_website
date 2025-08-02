@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">📋 JSP बेसिक </h2>

    <!-- List Table -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📄 JSP Records List</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>SR</th>
                        <th>Content</th>
                        <th>Image</th>
                        <th style="width: 100px;">Edit</th>
                    </tr>
                </thead>
                <tbody id="jspList"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="jspForm" enctype="multipart/form-data">
        <input type="hidden" id="recordId">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit JSP Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="dtp" class="form-label">Change Image (optional)</label>
                    <input type="file" id="dtp" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const apiUrl = "/api/jsp-basic";
    let editModal;

    document.addEventListener('DOMContentLoaded', () => {
        editModal = new bootstrap.Modal(document.getElementById('editModal'));
        fetchAll();
    });

    function fetchAll() {
        fetch(apiUrl)
            .then(res => {
                if (!res.ok) throw new Error("Failed to load records");
                return res.json();
            })
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
                                <button class="btn btn-sm btn-warning" onclick="editRecord(${item.id})">Edit</button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('jspList').innerHTML = html;
            })
            .catch(err => {
                alert("Error loading data.");
                console.error(err);
            });
    }

    function editRecord(id) {
        fetch(`${apiUrl}/${id}`)
            .then(res => {
                if (!res.ok) throw new Error("Record not found");
                return res.json();
            })
            .then(data => {
                document.getElementById('recordId').value = data.id;
                document.getElementById('content').value = data.content;
                document.getElementById('dtp').value = ''; // reset file input
                editModal.show();
            })
            .catch(err => {
                alert("Unable to load the record.");
                console.error(err);
            });
    }

    document.getElementById('jspForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('recordId').value;
        const url = `${apiUrl}/${id}`;
        const formData = new FormData();

        formData.append('content', document.getElementById('content').value);
        const file = document.getElementById('dtp').files[0];
        if (file) formData.append('dtp', file);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        }).then(res => {
            if (!res.ok) throw new Error('Update failed');
            return res.json();
        }).then(() => {
            document.getElementById('jspForm').reset();
            editModal.hide();
            fetchAll();
        }).catch(err => {
            alert("Update failed.");
            console.error(err);
        });
    });
</script>
@endsection
