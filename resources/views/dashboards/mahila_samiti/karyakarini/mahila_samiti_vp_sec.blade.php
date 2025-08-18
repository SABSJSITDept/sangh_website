@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: #f8f9fa;
    }
    h3 {
        font-weight: 600;
        color: #444;
    }
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .form-label {
        font-weight: 500;
    }
    .btn-success {
        border-radius: 30px;
        padding: 8px 20px;
        font-weight: 500;
    }
    .table thead {
        background: #198754;
        color: #fff;
    }
    .table tbody tr:hover {
        background: #f1f9f3;
    }
    .action-btns .btn {
        border-radius: 8px;
        padding: 4px 10px;
    }
    .toast {
        border-radius: 10px;
        font-size: 14px;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-people-fill text-success"></i> महिला समिति उपाध्यक्ष/मंत्री</h3>
        <button class="btn btn-outline-success" onclick="document.getElementById('vpSecForm').scrollIntoView({behavior:'smooth'})">
            <i class="bi bi-plus-circle"></i> नया जोड़ें
        </button>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3"></div>

    <!-- Form -->
    <div class="card p-4 mb-5">
        <h5 class="mb-3"><i class="bi bi-pencil-square"></i> जानकारी भरें</h5>
        <form id="vpSecForm" enctype="multipart/form-data">
            <input type="hidden" id="vpsec_id">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">नाम</label>
                    <input type="text" id="name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">पद</label>
                    <select id="post" class="form-select" required>
                        <option value="">चुनें</option>
                        <option value="उपाध्यक्ष">उपाध्यक्ष</option>
                        <option value="मंत्री">मंत्री</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">शहर</label>
                    <input type="text" id="city" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">मोबाइल</label>
                    <input type="text" id="mobile" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">आंचल</label>
                    <select id="aanchal_id" class="form-select" required></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">फोटो (max 200KB)</label>
                    <input type="file" id="photo" accept="image/*" class="form-control">
                </div>
            </div>
            <button class="btn btn-success mt-4" type="submit">
                <i class="bi bi-check-circle"></i> Save
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="card p-3">
        <h5 class="mb-3"><i class="bi bi-table"></i> सूची</h5>
        <div class="table-responsive">
            <table class="table align-middle" id="vpSecTable">
                <thead>
                    <tr>
                        <th>नाम</th>
                        <th>पद</th>
                        <th>शहर</th>
                        <th>मोबाइल</th>
                        <th>आंचल</th>
                        <th>फोटो</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showToast(message, type="success") {
    let toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.role = "alert";
    toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.querySelector('.toast-container').appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

async function loadAanchal() {
    let res = await fetch('/api/aanchal');
    let data = await res.json();
    let select = document.getElementById('aanchal_id');
    select.innerHTML = '<option value="">चुनें</option>';
    data.forEach(d => {
        select.innerHTML += `<option value="${d.id}">${d.name}</option>`;
    });
}

async function loadData() {
    let res = await fetch('/api/mahila_vp_sec');
    let data = await res.json();
    let tbody = document.querySelector('#vpSecTable tbody');
    tbody.innerHTML = "";
    data.forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td>${row.name}</td>
                <td><span class="badge bg-success">${row.post}</span></td>
                <td>${row.city}</td>
                <td>${row.mobile}</td>
                <td>${row.aanchal ? row.aanchal.name : ''}</td>
                <td><img src="${row.photo}" class="rounded-circle border" width="45" height="45"></td>
                <td class="text-center action-btns">
                    <button class="btn btn-sm btn-primary me-1" onclick="editRow(${row.id})"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteRow(${row.id})"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

document.getElementById('vpSecForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    let id = document.getElementById('vpsec_id').value;
    let formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('post', document.getElementById('post').value);
    formData.append('city', document.getElementById('city').value);
    formData.append('mobile', document.getElementById('mobile').value);
    formData.append('aanchal_id', document.getElementById('aanchal_id').value);
    if(document.getElementById('photo').files[0]){
        formData.append('photo', document.getElementById('photo').files[0]);
    }

    let url = id ? `/api/mahila_vp_sec/${id}` : '/api/mahila_vp_sec';
    let method = 'POST';
    if (id) formData.append('_method','PUT');

    let res = await fetch(url, {
        method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    let result = await res.json();
    if(res.ok && result.success){
        showToast("Saved successfully");
        loadData();
        e.target.reset();
        document.getElementById('vpsec_id').value = "";
    } else {
        if(result.message){
            showToast(result.message, "danger");
        } else if(result.errors){
            Object.values(result.errors).forEach(err => showToast(err, "danger"));
        } else {
            showToast("कुछ गड़बड़ हुई", "danger");
        }
    }
});

async function editRow(id){
    let res = await fetch(`/api/mahila_vp_sec/${id}`);
    let row = await res.json();
    document.getElementById('vpsec_id').value = row.id;
    document.getElementById('name').value = row.name;
    document.getElementById('post').value = row.post;
    document.getElementById('city').value = row.city;
    document.getElementById('mobile').value = row.mobile;
    document.getElementById('aanchal_id').value = row.aanchal_id;
    window.scrollTo({top:0, behavior:'smooth'});
}

async function deleteRow(id){
    if(!confirm("Delete this entry?")) return;
    let res = await fetch(`/api/mahila_vp_sec/${id}`, {
        method:'DELETE',
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    let result = await res.json();
    if(result.success){
        showToast("Deleted","danger");
        loadData();
    }
}

loadAanchal();
loadData();
</script>
@endsection
