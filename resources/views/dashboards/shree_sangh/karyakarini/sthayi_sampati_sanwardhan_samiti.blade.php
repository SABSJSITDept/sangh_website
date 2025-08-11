@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
</style>

<div class="container mt-4">
    <!-- Instruction Message -->
    <div class="alert alert-info">
        <strong>Note:</strong> All fields are compulsory & image size should not exceed 200 KB.
    </div>

    <form id="memberForm" enctype="multipart/form-data">
        <input type="hidden" name="id" id="member_id">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>City:</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Mobile:</label>
                <input type="text" name="mobile" id="mobile" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Photo:</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>

    <hr>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="listBody"></tbody>
    </table>
</div>

<!-- Toast -->
<div class="toast-container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showToast(message, type = 'success') {
    let toastId = 'toast-' + Date.now();
    let bgClass = type === 'success' ? 'bg-success' : 'bg-danger';

    document.querySelector('.toast-container').innerHTML += `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 mb-2" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    new bootstrap.Toast(document.getElementById(toastId)).show();
}

document.getElementById('memberForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Image size check
    let photo = document.getElementById('photo').files[0];
    if (photo && photo.size > 200 * 1024) {
        showToast('Image size should not exceed 200 KB', 'error');
        return;
    }

    let formData = new FormData(this);
    let id = document.getElementById('member_id').value;
    let url = id ? `/sthayi-sampati/${id}` : '/sthayi-sampati';
    let method = 'POST';

    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: method,
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            this.reset();
            document.getElementById('member_id').value = '';
            fetchMembers();
        } else {
            showToast(data.message || 'Something went wrong', 'error');
        }
    })
    .catch(() => showToast('Server error', 'error'));
});

function fetchMembers() {
    fetch('/api/sthayi_sampati_sanwardhan_samiti')
        .then(res => res.json())
        .then(data => {
            listBody.innerHTML = '';
            data.forEach(member => {
                let photoUrl = member.photo 
                    ? member.photo 
                    : 'https://via.placeholder.com/60x60?text=No+Image';
                listBody.innerHTML += `
                    <tr>
                        <td><img src="${photoUrl}" width="60" height="60" style="object-fit:cover;border-radius:50%;"></td>
                        <td>${member.name}</td>
                        <td>${member.city}</td>
                        <td>${member.mobile}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editMember(${member.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteMember(${member.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function editMember(id) {
    fetch(`/sthayi-sampati/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('member_id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('city').value = data.city;
            document.getElementById('mobile').value = data.mobile;
        });
}

function deleteMember(id) {
    if (!confirm('Are you sure?')) return;

    fetch(`/sthayi-sampati/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            fetchMembers();
        } else {
            showToast('Failed to delete', 'error');
        }
    })
    .catch(() => showToast('Server error', 'error'));
}

fetchMembers();
</script>
@endsection
