@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
.toast-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
}
.card-custom {
    border-radius: 15px;
    box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
}
.card-custom img {
    border-radius: 12px;
    object-fit: cover;
}
</style>

<div class="container py-4">
    <h3 class="mb-4 text-center fw-bold text-primary">
        <i class="bi bi-people-fill"></i> महिला समिति पूर्व अध्यक्ष प्रबंधन
    </h3>

    <!-- ✅ Toast -->
    <div class="toast-container"></div>

    <!-- ✅ Latest Entry Section -->
    <div class="card card-custom mb-4 p-3">
        <h5 class="mb-3 text-secondary">🌟 Latest Entry</h5>
        <div id="latestEntry" class="d-flex align-items-center">
            <p class="text-muted">Loading latest entry...</p>
        </div>
    </div>

    <!-- ✅ Form -->
    <div class="card card-custom mb-4 p-3">
        <h5 class="mb-3 text-secondary">➕ नई एंट्री जोड़ें / अपडेट करें</h5>
        <form id="prsidentForm" enctype="multipart/form-data">
            <input type="hidden" id="prsident_id">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">कार्यकाल</label>
                    <input type="text" class="form-control" id="karyakal" placeholder="कार्यकाल" >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Place</label>
                    <input type="text" class="form-control" id="place" placeholder="Place" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Photo (Max 200KB)</label>
                    <input type="file" class="form-control" id="photo" accept="image/*">
                    <small class="text-danger">Only images allowed</small>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i> Save</button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()"><i class="bi bi-arrow-repeat"></i> Reset</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ✅ List Section -->
    <h5 class="mb-3 text-secondary">📋 Entries List</h5>
    <div id="prsidentList" class="row g-3"></div>
</div>

<script>
// ✅ Toast Function
function showToast(message, type = 'success') {
    let bg = type === 'success' ? 'bg-success' : 'bg-danger';
    document.querySelector('.toast-container').innerHTML = `
        <div class="toast align-items-center text-white ${bg} border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`;
}

// ✅ Fetch Latest Entry
async function fetchLatest() {
    let res = await fetch('/api/mahila-ex-prsident/latest');
    let item = await res.json();

    let latestHtml = '';
    if(item) {
        latestHtml = `
            <img src="${item.photo}" alt="${item.name}" width="100" height="100" class="me-3">
            <div>
                <h5 class="mb-1">${item.name}</h5>
                <p class="mb-1"><strong>कार्यकाल:</strong> ${item.karyakal ?? '-'}</p>
                <p class="mb-1"><strong>Place:</strong> ${item.place}</p>
            </div>
        `;
    } else {
        latestHtml = "<p class='text-muted'>No latest entry found.</p>";
    }

    document.getElementById('latestEntry').innerHTML = latestHtml;
}

// ✅ Fetch Data (All Entries)
async function fetchData() {
    let res = await fetch('/api/mahila-ex-prsident');
    let data = await res.json();
    let cards = '';
    data.forEach(item => {
        cards += `
        <div class="col-md-6">
            <div class="card card-custom p-3 d-flex flex-row align-items-center">
                <img src="${item.photo}" alt="${item.name}" width="100" height="100" class="me-3">
                <div class="flex-grow-1">
                    <h5 class="mb-1">${item.name}</h5>
                    <p class="mb-1"><strong>कार्यकाल:</strong> ${item.karyakal}</p>
                    <p class="mb-1"><strong>Place:</strong> ${item.place}</p>
                    <div>
                        <button class="btn btn-sm btn-warning" onclick="editRow(${item.id}, '${item.name}', '${item.karyakal}', '${item.place}', '${item.photo}')"><i class="bi bi-pencil-square"></i> Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteRow(${item.id})"><i class="bi bi-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>`;
    });
    document.getElementById('prsidentList').innerHTML = cards || "<p class='text-muted'>No entries found.</p>";
}

// ✅ Save/Update
document.getElementById('prsidentForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    let id = document.getElementById('prsident_id').value;
    let formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('karyakal', document.getElementById('karyakal').value);
    formData.append('place', document.getElementById('place').value);
    if(document.getElementById('photo').files[0]) {
        formData.append('photo', document.getElementById('photo').files[0]);
    }

    let url = '/api/mahila-ex-prsident' + (id ? '/' + id : '');
    let method = id ? 'POST' : 'POST';
    if(id) formData.append('_method','PUT');

    let res = await fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    let result = await res.json();

    if(result.success){
        showToast('Saved Successfully');
        resetForm();
        fetchData();
        fetchLatest(); // ✅ Latest भी update हो जाएगा
    } else {
        if(result.errors){  
            Object.values(result.errors).forEach(errArr => {
                errArr.forEach(err => showToast(err, 'error'));
            });
        } else if(result.message) {
            showToast(result.message, 'error');
        } else {
            showToast('Error Occurred', 'error');
        }
    }
});

// ✅ Edit Row
function editRow(id, name, karyakal, place, photo) {
    document.getElementById('prsident_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('karyakal').value = karyakal;
    document.getElementById('place').value = place;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ✅ Delete Row
async function deleteRow(id) {
    if(!confirm('Are you sure?')) return;
    let res = await fetch('/api/mahila-ex-prsident/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    let result = await res.json();
    if(result.success){
        showToast('Deleted Successfully');
        fetchData();
        fetchLatest(); // ✅ delete के बाद भी latest refresh होगा
    }
}

// ✅ Reset Form
function resetForm() {
    document.getElementById('prsident_id').value = '';
    document.getElementById('prsidentForm').reset();
}

// Load Data Initially
fetchLatest();
fetchData();
</script>
@endsection
