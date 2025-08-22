@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h3 class="mb-4 text-center">पूर्व अध्यक्ष (Ex-President)</h3>

    <div class="row">
        <!-- Form -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 id="formTitle">Add Ex-President</h5>
                    <form id="exPresidentForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="editId">

                        <div class="mb-3">
                            <label class="form-label">Name*</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Karyakal</label>
                            <input type="text" class="form-control" name="karyakal" id="karyakal">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" id="city">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo*</label>
                            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
                            <small class="text-muted">Max 200KB</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="submitBtn">Add</button>
                        <button type="button" class="btn btn-secondary w-100 mt-2 d-none" id="cancelEdit">Cancel Edit</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>List of Ex-Presidents</h5>
                    <table class="table table-bordered table-hover mt-3 align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Karyakal</th>
                                <th>City</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="exPresidentTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showToast(icon, message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 2000
    });
}

function fetchList() {
    fetch("/api/yuva-ex-president")
        .then(res => res.json())
        .then(data => {
            let html = "";
            data.forEach(item => {
                html += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.karyakal ?? ''}</td>
                    <td>${item.city ?? ''}</td>
                    <td><img src="${item.photo}" width="60" class="rounded"></td>
                    <td>
                        <button onclick="editItem(${item.id}, '${item.name}', '${item.karyakal ?? ''}', '${item.city ?? ''}', '${item.photo}')" class="btn btn-warning btn-sm me-1">Edit</button>
                        <button onclick="deleteItem(${item.id})" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>`;
            });
            document.getElementById("exPresidentTable").innerHTML = html;
        });
}

document.getElementById("exPresidentForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let id = document.getElementById("editId").value;
    let method = id ? "POST" : "POST";
    let url = id ? `/api/yuva-ex-president/${id}?_method=PUT` : "/api/yuva-ex-president";

    let photo = formData.get("photo");
    if (photo && photo.size > 200*1024) {
        showToast("error","Image must be under 200KB");
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
    .then(response => {
        if(response.errors){
            Object.values(response.errors).forEach(err => showToast("error",err[0]));
        } else {
            showToast("success", response.message);
            this.reset();
            resetForm();
            fetchList();
        }
    });
});

function editItem(id, name, karyakal, city, photo){
    document.getElementById("editId").value = id;
    document.getElementById("name").value = name;
    document.getElementById("karyakal").value = karyakal;
    document.getElementById("city").value = city;

    document.getElementById("formTitle").innerText = "Edit Ex-President";
    document.getElementById("submitBtn").innerText = "Update";
    document.getElementById("cancelEdit").classList.remove("d-none");
}

document.getElementById("cancelEdit").addEventListener("click", resetForm);

function resetForm(){
    document.getElementById("exPresidentForm").reset();
    document.getElementById("editId").value = "";
    document.getElementById("formTitle").innerText = "Add Ex-President";
    document.getElementById("submitBtn").innerText = "Add";
    document.getElementById("cancelEdit").classList.add("d-none");
}

function deleteItem(id){
    Swal.fire({
        title: "Are you sure?",
        text: "This will delete the record permanently.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/yuva-ex-president/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(response => {
                showToast("success", response.message);
                fetchList();
            });
        }
    });
}

fetchList();
</script>
@endsection
