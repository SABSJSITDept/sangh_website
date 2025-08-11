@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4">
    <h2 class="mb-4">स्थायी संपत्ति संवर्धन समिति</h2>

    <!-- Form -->
    <form id="sampatiForm" enctype="multipart/form-data">
        <input type="hidden" id="edit_id" name="edit_id">

        <div class="row mb-3">
            <div class="col">
                <input type="text" id="name" name="name" class="form-control" placeholder="नाम" required>
            </div>
            <div class="col">
                <select id="post" name="post" class="form-control" required>
                    <option value="">पोस्ट चुनें</option>
                    <option value="sanyojak">संयोजक</option>
                    <option value="seh sanyojak">सह संयोजक </option>
                    <option value="sanyojan mandal sadasy">संयोजन मण्डल सदस्य</option>
                </select>
            </div>
            <div class="col">
                <input type="text" id="city" name="city" class="form-control" placeholder="शहर" required>
            </div>
            <div class="col">
                <input type="text" id="mobile_number" name="mobile_number" class="form-control" placeholder="मोबाइल नंबर" required>
            </div>
            <div class="col">
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th>फोटो</th>
                <th>नाम</th>
                <th>पोस्ट</th>
                <th>शहर</th>
                <th>मोबाइल नंबर</th>
                <th>एक्शन</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", fetchData);

function fetchData() {
    fetch('/api/sthayi-sampati')
        .then(res => res.json())
        .then(data => {
            let rows = '';
            data.forEach(item => {
                rows += `
                <tr>
                    <td><img src="${item.photo}" width="50"></td>
                    <td>${item.name}</td>
                    <td>${item.post}</td>
                    <td>${item.city}</td>
                    <td>${item.mobile_number}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editItem(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                </tr>`;
            });
            document.querySelector("#dataTable tbody").innerHTML = rows;
        });
}

document.getElementById('sampatiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let id = document.getElementById('edit_id').value;
    let formData = new FormData(this);

    let url = id ? `/api/sthayi-sampati/${id}` : '/api/sthayi-sampati';
    let method = id ? 'POST' : 'POST';
    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire('Success', 'Saved Successfully', 'success');
        this.reset();
        document.getElementById('edit_id').value = '';
        fetchData();
    })
    .catch(() => Swal.fire('Error', 'Something went wrong', 'error'));
});

function editItem(id) {
    fetch('/api/sthayi-sampati')
        .then(res => res.json())
        .then(data => {
            let item = data.find(i => i.id === id);
            if (!item) return;

            document.getElementById('edit_id').value = item.id;
            document.getElementById('name').value = item.name;
            document.getElementById('post').value = item.post;
            document.getElementById('city').value = item.city;
            document.getElementById('mobile_number').value = item.mobile_number;
        });
}

function deleteItem(id) {
    Swal.fire({
        title: 'Delete?',
        text: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/sthayi-sampati/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(() => {
                Swal.fire('Deleted', 'Record removed', 'success');
                fetchData();
            });
        }
    });
}
</script>
@endsection
