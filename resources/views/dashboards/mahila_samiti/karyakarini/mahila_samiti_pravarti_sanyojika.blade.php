@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4">महिला समिति प्रवर्त्ति संयोजिका</h3>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3"></div>

    <!-- Form -->
    <form id="pravartiForm" enctype="multipart/form-data" class="mb-4">
        <input type="hidden" id="member_id">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Name *</label>
                <input type="text" id="name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">City *</label>
                <input type="text" id="city" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Mobile *</label>
                <input type="text" id="mobile" maxlength="10" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Post *</label>
                <select id="post" class="form-select" required>
                    <option value="">Select</option>
                    <option value="संयोजिका">संयोजिका</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Pravarti *</label>
                <select id="pravarti" class="form-select" required>
                    <option value="">Select</option>
                    <option value="Kesariya Karyashala">Kesariya Karyashala</option>
                    <option value="Sangathan">Sangathan</option>
                    <option value="Sarwadharmi Sahyog">Sarwadharmi Sahyog</option>
                    <option value="Yuvati Shakti">Yuvati Shakti</option>
                    <option value="Sadhumargi Women's Motivational Forum">Sadhumargi Women's Motivational Forum</option>
                    <option value="Parivaranjali">Parivaranjali</option>
                    <option value="Samta Chhatravratti">Samta Chhatravratti</option>
                    <option value="Reporting System">Reporting System</option>
                    <option value="Shramanopasak Sanyojika">Shramanopasak Sanyojika</option>
                    <option value="International">International</option>
                    <option value="Pratikraman Sanyojika">Pratikraman Sanyojika</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Photo (Max 200KB)</label>
                <input type="file" id="photo" class="form-control" accept="image/*">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>

    <!-- Pravarti Buttons -->
    <div class="mb-3">
        <h5>View Pravarti-wise Members:</h5>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Kesariya Karyashala')">Kesariya Karyashala</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Sangathan')">Sangathan</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Sarwadharmi Sahyog')">Sarwadharmi Sahyog</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Yuvati Shakti')">Yuvati Shakti</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Sadhumargi Womens Motivational Forum')">Sadhumargi Women's Motivational Forum</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Parivaranjali')">Parivaranjali</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Samta Chhatravratti')">Samta Chhatravratti</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Reporting System')">Reporting System</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Shramanopasak Sanyojika')">Shramanopasak Sanyojika</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('International')">International</button>
            <button class="btn btn-outline-primary" onclick="fetchPravarti('Pratikraman Sanyojika')">Pratikraman Sanyojika</button>
        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>City</th>
                <th>Post</th>
                <th>Pravarti</th>
                <th>Mobile</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="pravartiTable"></tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const apiUrl = "/api/mahila_pravarti_sanyojika";

// Toast helper
function showToast(message, type="success") {
    Swal.fire({
        toast: true,
        icon: type,
        title: message,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

// Convert Pravarti name to slug (for clean URL)
function toSlug(str){
    return str.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '');
}

// Fetch all members (default)
async function fetchData() {
    let res = await fetch(apiUrl);
    let data = await res.json();
    renderTable(data);
}
fetchData();

// Render table helper
function renderTable(data){
    let rows = "";
    data.forEach(m => {
        rows += `
        <tr>
            <td><img src="${m.photo}" width="60"></td>
            <td>${m.name}</td>
            <td>${m.city}</td>
            <td>${m.post}</td>
            <td>${m.pravarti}</td>
            <td>${m.mobile}</td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editMember(${m.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteMember(${m.id})">Delete</button>
            </td>
        </tr>`;
    });
    document.getElementById("pravartiTable").innerHTML = rows;
}

// Fetch Pravarti-wise
async function fetchPravarti(pravarti){
    let slug = toSlug(pravarti);
    let res = await fetch(`${apiUrl}/pravarti/${slug}`);
    let data = await res.json();
    renderTable(data);
}

// Add / Update member
document.getElementById("pravartiForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    let id = document.getElementById("member_id").value;

    let formData = new FormData();
    formData.append("name", document.getElementById("name").value);
    formData.append("city", document.getElementById("city").value);
    formData.append("mobile", document.getElementById("mobile").value);
    formData.append("post", document.getElementById("post").value);
    formData.append("pravarti", document.getElementById("pravarti").value);

    if(document.getElementById("photo").files[0]){
        if(document.getElementById("photo").files[0].size > 200*1024){
            showToast("Photo size must be less than 200KB","error");
            return;
        }
        formData.append("photo", document.getElementById("photo").files[0]);
    }

    if(id) formData.append("_method", "PUT");

    let url = id ? `${apiUrl}/${id}` : apiUrl;

    let res = await fetch(url, {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    if(res.status === 422){
        let data = await res.json();
        let errors = [];
        for(let key in data.errors){
            errors.push(...data.errors[key]);
        }
        showToast(errors.join(", "), "error");
    } else {
        showToast(id ? "Updated Successfully" : "Added Successfully");
        document.getElementById("pravartiForm").reset();
        document.getElementById("member_id").value = "";
        fetchData();
    }
});

// Edit member
async function editMember(id){
    let res = await fetch(`${apiUrl}/${id}`);
    let m = await res.json();
    document.getElementById("member_id").value = m.id;
    document.getElementById("name").value = m.name;
    document.getElementById("city").value = m.city;
    document.getElementById("mobile").value = m.mobile;
    document.getElementById("post").value = m.post;
    document.getElementById("pravarti").value = m.pravarti;
}

// Delete member
async function deleteMember(id){
    Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete"
    }).then(async (result) => {
        if(result.isConfirmed){
            await fetch(`${apiUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            showToast("Deleted Successfully");
            fetchData();
        }
    });
}
</script>
@endsection
