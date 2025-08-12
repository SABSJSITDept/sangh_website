@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">📋 JSP बेसिक</h2>

    <!-- Add Button
    <div class="mb-3 text-end">
        <button class="btn btn-primary" onclick="openAddModal()">➕ Add New</button>
    </div> -->

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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addJspForm" enctype="multipart/form-data" novalidate>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add JSP Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea id="add_content" class="form-control" rows="3" required></textarea>
                    <div class="invalid-feedback">Please enter content.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" id="add_dtp" class="form-control" accept="image/*" required>
                    <div class="invalid-feedback">Please select an image file.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="jspForm" enctype="multipart/form-data" novalidate>
        <input type="hidden" id="recordId">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit JSP Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea id="content" class="form-control" rows="3" required></textarea>
                    <div class="invalid-feedback">Please enter content.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Change Image (optional)</label>
                    <input type="file" id="dtp" class="form-control" accept="image/*">
                    <div class="invalid-feedback">Please select a valid image file.</div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const apiUrl = "/api/jsp-basic";
    let editModal, addModal;

    document.addEventListener('DOMContentLoaded', () => {
        editModal = new bootstrap.Modal(document.getElementById('editModal'));
        addModal = new bootstrap.Modal(document.getElementById('addModal'));
        fetchAll();
    });

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
                                <button class="btn btn-sm btn-warning" onclick="editRecord(${item.id})">Edit</button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('jspList').innerHTML = html;
            })
            .catch(err => console.error(err));
    }

    function openAddModal() {
        document.getElementById('addJspForm').reset();
        document.querySelectorAll('#addJspForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        addModal.show();
    }

    // Validate & Add
    document.getElementById('addJspForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;

        const content = document.getElementById('add_content');
        const dtp = document.getElementById('add_dtp');

        if (!content.value.trim()) {
            content.classList.add('is-invalid');
            valid = false;
        } else {
            content.classList.remove('is-invalid');
        }

        if (!dtp.files.length) {
            dtp.classList.add('is-invalid');
            valid = false;
        } else {
            dtp.classList.remove('is-invalid');
        }

        if (!valid) return;

        const formData = new FormData();
        formData.append('content', content.value);
        formData.append('dtp', dtp.files[0]);

        fetch(apiUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        }).then(res => {
            if (!res.ok) throw new Error('Add failed');
            return res.json();
        }).then(() => {
            addModal.hide();
            fetchAll();
        }).catch(err => {
            alert("Add failed.");
            console.error(err);
        });
    });

    // Edit Record
    function editRecord(id) {
        fetch(`${apiUrl}/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('recordId').value = data.id;
                document.getElementById('content').value = data.content;
                document.getElementById('dtp').value = '';
                document.querySelectorAll('#jspForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
                editModal.show();
            })
            .catch(err => console.error(err));
    }

    // Validate & Update
    document.getElementById('jspForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let valid = true;

        const content = document.getElementById('content');
        if (!content.value.trim()) {
            content.classList.add('is-invalid');
            valid = false;
        } else {
            content.classList.remove('is-invalid');
        }

        if (!valid) return;

        const id = document.getElementById('recordId').value;
        const formData = new FormData();
        formData.append('content', content.value);
        const file = document.getElementById('dtp').files[0];
        if (file) formData.append('dtp', file);

        fetch(`${apiUrl}/${id}`, {
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
            editModal.hide();
            fetchAll();
        }).catch(err => {
            alert("Update failed.");
            console.error(err);
        });
    });
</script>
@endsection
