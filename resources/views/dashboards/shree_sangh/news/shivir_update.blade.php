@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

<div class="container py-4">
    <h2 class="mb-4">शिविर अपडेट</h2>

    <form id="shivirForm" enctype="multipart/form-data">
        <input type="hidden" name="shivir_id" id="shivir_id">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Date</label>
                <input type="text" name="date" id="date" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Location</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control" rows="1" required></textarea>
            </div>
            <div class="col-md-4 mb-3">
                <label>Photo (Max 200KB)</label>
                <input type="file" name="photo" id="photo" accept="image/*" class="form-control">
            </div>
            <div class="col-md-4 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" id="submitBtn">Submit</button>
            </div>
        </div>
    </form>

    <hr>

    <div id="shivirList" class="row row-cols-1 row-cols-md-2 g-4 mt-4"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchShivir();

    const form = document.getElementById('shivirForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const id = document.getElementById('shivir_id').value;

        const url = id ? `/api/shivir/${id}` : '/api/shivir';
        const method = id ? 'POST' : 'POST';

        if (id) {
            formData.append('_method', 'PUT');
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
            alert(id ? "Updated successfully" : "Added successfully");
            form.reset();
            document.getElementById('shivir_id').value = '';
            submitBtn.textContent = 'Submit';
            fetchShivir();
        })
        .catch(err => console.error(err));
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
                    <div class="col">
                        <div class="card">
                            <img src="/storage/${shivir.photo}" class="card-img-top" style="height:200px;object-fit:cover">
                            <div class="card-body">
                                <h5 class="card-title">${shivir.title}</h5>
                                <p class="card-text">
                                    <strong>Date:</strong> ${shivir.date}<br>
                                    <strong>Location:</strong> ${shivir.location}<br>
                                    <strong>Description:</strong> ${shivir.description}
                                </p>
                                <button class="btn btn-warning btn-sm me-2" onclick="editShivir(${shivir.id})">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteShivir(${shivir.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
        });
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
        });
}

function deleteShivir(id) {
    if (!confirm('Are you sure to delete?')) return;

    fetch(`/api/shivir/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(() => fetchShivir());
}
</script>
@endsection
