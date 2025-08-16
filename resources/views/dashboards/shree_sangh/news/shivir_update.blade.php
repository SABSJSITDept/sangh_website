@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<div class="container py-4">

    <!-- Alert Message -->
    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            🖼 Photo size 200 KB से ज़्यादा नहीं होना चाहिए। <br>
            📌 सभी fields भरना अनिवार्य है।
        </div>
    </div>

    <h2 class="mb-4">शिविर सूची</h2>

    <button class="btn btn-success mb-3" onclick="openAddModal()">
        <i class="bi bi-plus-circle"></i> Add New Shivir
    </button>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Photo</th>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Description</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody id="shivirList"></tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="shivirModal" tabindex="-1" aria-labelledby="shivirModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="shivirForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="shivirModalLabel">शिविर अपडेट</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="shivir_id" id="shivir_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Date</label>
                            <input type="text" name="date" id="date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Location</label>
                            <input type="text" name="location" id="location" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Description</label>
                            <textarea name="description" id="description" class="form-control" rows="1" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Photo (Max 200KB)</label>
                            <input type="file" name="photo" id="photo" accept="image/*" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchShivir();

    const form = document.getElementById('shivirForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const id = document.getElementById('shivir_id').value;
        const url = id ? `/api/shivir/${id}` : '/api/shivir';
        const method = 'POST';

        if (id) {
            formData.append('_method', 'PUT');
        }

        // Photo size validation
        const photo = formData.get('photo');
        if (photo && photo.size > 204800) {
            showToast("Photo must be under 200 KB!", "error");
            return;
        }

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showToast(Object.values(data.errors).join('<br>'), 'error');
            } else {
                showToast(id ? "Updated successfully" : "Added successfully", 'success');
                form.reset();
                bootstrap.Modal.getInstance(document.getElementById('shivirModal')).hide();
                fetchShivir();
            }
        })
        .catch(err => {
            console.error(err);
            showToast("Something went wrong!", 'error');
        });
    });
});

function fetchShivir() {
    fetch('/api/shivir')
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('shivirList');
            list.innerHTML = '';
            data.forEach(shivir => {
                list.innerHTML += `
                    <tr>
                        <td><img src="/storage/${shivir.photo}" width="80" height="60" style="object-fit:cover"></td>
                        <td>${shivir.title}</td>
                        <td>${shivir.date}</td>
                        <td>${shivir.location}</td>
                        <td>${shivir.description}</td>
                        <td>
                            <button class="btn btn-warning btn-sm me-1" onclick="editShivir(${shivir.id})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteShivir(${shivir.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        });
}

function openAddModal() {
    document.getElementById('shivirForm').reset();
    document.getElementById('shivir_id').value = '';
    document.getElementById('submitBtn').textContent = 'Submit';
    new bootstrap.Modal(document.getElementById('shivirModal')).show();
}

function editShivir(id) {
    fetch(`/api/shivir/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('shivir_id').value = data.id;
            document.getElementById('title').value = data.title;
            document.getElementById('date').value = data.date;
            document.getElementById('location').value = data.location;
            document.getElementById('description').value = data.description;
            document.getElementById('submitBtn').textContent = 'Update';
            new bootstrap.Modal(document.getElementById('shivirModal')).show();
        });
}

function deleteShivir(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/shivir/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(() => {
                showToast("Deleted successfully", 'success');
                fetchShivir();
            });
        }
    });
}

function showToast(message, type = 'success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000
    });
}
</script>
@endsection
