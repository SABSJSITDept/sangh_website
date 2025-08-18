@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .highlight-form {
    background-color: #fff3cd;  /* हल्का yellow highlight */
    transition: background-color 1s ease;
    border-radius: 8px;
    padding: 10px;
}

</style>
<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h3 class="mb-4">महिला समिति KSM Members</h3>

    <!-- Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3"></div>

    <!-- Form -->
    <form id="memberForm" enctype="multipart/form-data" class="mb-4">
        <input type="hidden" id="member_id">

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Name</label>
                <input type="text" id="name" class="form-control" placeholder="Enter Name" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">City</label>
                <input type="text" id="city" class="form-control" placeholder="Enter City" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Mobile</label>
                <input type="text" id="mobile" class="form-control" placeholder="10-digit Mobile" required maxlength="10">
            </div>
            <div class="col-md-3">
                <label class="form-label">Aanchal</label>
                <select id="aanchal_id" class="form-select" required></select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Photo</label>
                <input type="file" id="photo" class="form-control" accept="image/*">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Aanchal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="memberTable"></tbody>
    </table>
</div>

<script>
    const apiUrl = "/api/mahila_ksm_members";

    document.addEventListener("DOMContentLoaded", () => {
        fetchAanchals();
        fetchMembers();
    });

    // Fetch Aanchals
    async function fetchAanchals() {
        let res = await fetch("/api/aanchal");
        let aanchals = await res.json();
        let dropdown = document.getElementById("aanchal_id");
        dropdown.innerHTML = "<option value=''>Select Aanchal</option>";
        aanchals.forEach(a => {
            dropdown.innerHTML += `<option value="${a.id}">${a.name}</option>`;
        });
    }

    // Fetch Members
    async function fetchMembers() {
        let res = await fetch(apiUrl);
        let data = await res.json();
        let rows = "";
        data.forEach(m => {
            rows += `
                <tr>
                    <td><img src="${m.photo}" width="50" class="rounded"></td>
                    <td>${m.name}</td>
                    <td>${m.city}</td>
                    <td>${m.mobile}</td>
                    <td>${m.aanchal?.name ?? ''}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editMember(${m.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteMember(${m.id})">Delete</button>
                    </td>
                </tr>
            `;
        });
        document.getElementById("memberTable").innerHTML = rows;
    }

    // Save / Update Member
    document.getElementById("memberForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        // ✅ Client-side validation
        if (!document.getElementById("name").value || 
            !document.getElementById("city").value || 
            !document.getElementById("mobile").value || 
            !document.getElementById("aanchal_id").value) {
            Swal.fire("Missing Fields", "Please fill all required fields!", "warning");
            return;
        }

        let file = document.getElementById("photo").files[0];
        if (file && file.size > 200 * 1024) {
            Swal.fire("Image Too Large", "Photo must be under 200KB!", "error");
            return;
        }

        let id = document.getElementById("member_id").value;
        let formData = new FormData();
        formData.append("name", document.getElementById("name").value);
        formData.append("city", document.getElementById("city").value);
        formData.append("mobile", document.getElementById("mobile").value);
        formData.append("aanchal_id", document.getElementById("aanchal_id").value);
        if (file) formData.append("photo", file);

        let url = id ? `${apiUrl}/${id}` : apiUrl;
        if (id) formData.append("_method", "PUT");

        let res = await fetch(url, {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        let data = await res.json();
        if (data.success) {
            showToast("Saved Successfully", "success");
            fetchMembers();
            document.getElementById("memberForm").reset();
            document.getElementById("member_id").value = "";
        } else {
            let msg = Object.values(data.errors || {}).flat().join("<br>");
            Swal.fire("Error", msg || "Something went wrong", "error");
        }
    });

    // Edit
async function editMember(id) {
    let res = await fetch(`${apiUrl}/${id}`);
    let m = await res.json();

    document.getElementById("member_id").value = m.id;
    document.getElementById("name").value = m.name;
    document.getElementById("city").value = m.city;
    document.getElementById("mobile").value = m.mobile;
    document.getElementById("aanchal_id").value = m.aanchal_id;
    document.getElementById("photo").value = "";

    let form = document.getElementById("memberForm");

    // ✅ Scroll to form
    form.scrollIntoView({
        behavior: "smooth",
        block: "start"
    });

    // ✅ Highlight effect
    form.classList.add("highlight-form");
    setTimeout(() => {
        form.classList.remove("highlight-form");
    }, 1500);
}

    // Delete
    async function deleteMember(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This member will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Delete",
            cancelButtonText: "Cancel"
        }).then(async (result) => {
            if (result.isConfirmed) {
                let res = await fetch(`${apiUrl}/${id}`, {
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                let data = await res.json();
                if (data.success) {
                    showToast("Deleted Successfully", "success");
                    fetchMembers();
                }
            }
        });
    }

    // Bootstrap Toast
    function showToast(message, type="info") {
        let toast = document.createElement("div");
        toast.className = `toast align-items-center text-bg-${type} border-0 show`;
        toast.role = "alert";
        toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div></div>`;
        document.querySelector(".toast-container").appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endsection
