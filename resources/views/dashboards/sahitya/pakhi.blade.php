@extends('includes.layouts.sahitya')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pakhi Ka Panna</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light p-4">

<div class="container">
    <h2 class="mb-4 text-center text-primary fw-bold">📄 पक्खी का पाना </h2>

    <!-- Form Card -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white fw-semibold">
            <span id="formTitle">➕ Upload PDF</span>
        </div>
        <div class="card-body">
            <form id="pakhiForm">
                <input type="hidden" id="pakhi_id" name="pakhi_id">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Year</label>
                    <select name="year" id="year" class="form-select" required>
                        <option value="">-- Select Year --</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload PDF (Max 2MB)</label>
                    <input type="file" name="pdf" id="pdf" class="form-control" accept="application/pdf">
                    <div class="form-text text-muted">Only PDF allowed, size must be ≤ 2 MB</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" id="submitBtn" class="btn btn-success">Submit</button>
                    <button type="button" id="resetBtn" class="btn btn-secondary d-none">Cancel Edit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Uploaded PDFs Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-semibold">📂 Uploaded PDFs</div>
        <div class="card-body">
            <table class="table table-bordered align-middle text-center" id="pakhiTable">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Year</th>
                        <th>PDF</th>
                        <th style="width: 200px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will load here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", fetchPakhi);

function fetchPakhi() {
    fetch("/api/pakhi")
        .then(res => res.json())
        .then(data => {
            let rows = "";
            if (data.length === 0) {
                rows = `<tr><td colspan="4" class="text-muted">No records found</td></tr>`;
            } else {
                data.forEach(item => {
                    rows += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.year}</td>
                            <td>
                                <a href="${item.pdf}" target="_blank" class="btn btn-sm btn-outline-primary">View PDF</a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning me-2" onclick="editPakhi(${item.id}, '${item.year}')">✏ Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deletePakhi(${item.id})">🗑 Delete</button>
                            </td>
                        </tr>`;
                });
            }
            document.querySelector("#pakhiTable tbody").innerHTML = rows;
        });
}

document.querySelector("#pakhiForm").addEventListener("submit", function(e){
    e.preventDefault();
    let formData = new FormData(this);
    let pakhiId = document.getElementById("pakhi_id").value;

    let url = "/api/pakhi" + (pakhiId ? "/" + pakhiId : "");
    let method = "POST";

    if(pakhiId){
        formData.append('_method', 'PUT'); // Laravel update support
    }

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    })
    .then(async res => {
        let data = await res.json();

        if(res.ok && data.success){
            Swal.fire("✅ Success", pakhiId ? "Updated Successfully!" : "Uploaded Successfully!", "success");
            resetForm();
            fetchPakhi();
        } else {
            if(data.errors){
                let errorMessages = "";
                Object.values(data.errors).forEach(errArr => {
                    errArr.forEach(msg => errorMessages += `• ${msg}<br>`);
                });
                Swal.fire({ icon: "error", title: "Validation Error", html: errorMessages });
            } else {
                Swal.fire("❌ Error", data.message || "Something went wrong!", "error");
            }
        }
    })
    .catch(err => Swal.fire("❌ Error", err.message, "error"));
});

function editPakhi(id, year){
    document.getElementById("pakhi_id").value = id;
    document.getElementById("year").value = year;

    document.getElementById("submitBtn").textContent = "Update";
    document.getElementById("submitBtn").classList.remove("btn-success");
    document.getElementById("submitBtn").classList.add("btn-primary");

    document.getElementById("resetBtn").classList.remove("d-none");
    document.getElementById("formTitle").textContent = "✏ Edit PDF";
}

function resetForm(){
    document.getElementById("pakhiForm").reset();
    document.getElementById("pakhi_id").value = "";

    document.getElementById("submitBtn").textContent = "Submit";
    document.getElementById("submitBtn").classList.remove("btn-primary");
    document.getElementById("submitBtn").classList.add("btn-success");

    document.getElementById("resetBtn").classList.add("d-none");
    document.getElementById("formTitle").textContent = "➕ Upload PDF";
}

document.getElementById("resetBtn").addEventListener("click", resetForm);

function deletePakhi(id){
    Swal.fire({
        title: "Are you sure?",
        text: "This PDF will be deleted permanently.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete it!",
        cancelButtonText: "Cancel"
    }).then(result => {
        if(result.isConfirmed){
            fetch(`/api/pakhi/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(res => res.json())
            .then(res => {
                if(res.success){
                    Swal.fire("🗑 Deleted!", "PDF has been deleted.", "success");
                    fetchPakhi();
                }
            });
        }
    });
}
</script>

</body>
</html>
@endsection
