@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Highlight effect for form */
    .highlight {
        animation: glow 1.5s ease-in-out 2;
    }
    @keyframes glow {
        0%   { box-shadow: 0 0 0px rgba(0, 123, 255, 0.5); }
        50%  { box-shadow: 0 0 20px rgba(0, 123, 255, 0.9); }
        100% { box-shadow: 0 0 0px rgba(0, 123, 255, 0.5); }
    }
    /* Table row hover */
    table tbody tr:hover {
        background: #f8f9fa;
        transition: 0.3s;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-3px);
    }
</style>

<div class="container py-4">
    <h3 class="mb-4 text-center fw-bold text-primary">महिला समिति आवेदन पत्र</h3>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3"></div>

    <!-- Row: Left Message + Form -->
    <div class="row justify-content-center mb-5">
        <!-- Left Side Message -->
        <div class="col-md-5 mb-3">
            <div class="alert alert-info shadow-sm border-0 rounded-4">
                <h5 class="fw-bold text-primary mb-2">⚠️ ध्यान दें</h5>
                <ul class="mb-0">
                    <li>Google Form का लिंक डालने के बाद <strong>एक बार ज़रूर चेक</strong> करें ✅</li>
                    <li>PDF फ़ाइल का साइज़ <strong>2MB तक ही</strong> होना चाहिए 📄</li>
                </ul>
            </div>
        </div>

        <!-- Form Card -->
        <div class="col-md-7">
            <div id="formCard" class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <form id="aavedanForm" enctype="multipart/form-data">
                        <input type="hidden" id="formId" name="id">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">नाम</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">प्रकार</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="pdfOption" name="type" value="pdf" class="form-check-input" checked>
                                <label class="form-check-label" for="pdfOption">PDF</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="googleOption" name="type" value="google_form" class="form-check-input">
                                <label class="form-check-label" for="googleOption">Google Form</label>
                            </div>
                        </div>

                        <div class="mb-3 pdf-field">
                            <label class="form-label fw-semibold">PDF Upload (Max 2MB)</label>
                            <input type="file" name="pdf" class="form-control" accept="application/pdf">
                        </div>

                        <div class="mb-3 google-field d-none">
                            <label class="form-label fw-semibold">Google Form Link</label>
                            <input type="url" name="google_form_link" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" id="saveBtn" class="btn btn-primary px-4">Save</button>
                            <button type="button" id="cancelEdit" class="btn btn-outline-secondary d-none px-4">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Offline Forms -->
    <h4 class="mb-3 text-success">📂 Offline Forms (PDF)</h4>
    <table class="table table-hover align-middle shadow-sm rounded" id="offlineTable">
        <thead class="table-primary">
            <tr>
                <th>नाम</th>
                <th>प्रकार</th>
                <th>PDF</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Online Forms -->
    <h4 class="mt-5 mb-3 text-info">🌐 Online Forms (Google Form)</h4>
    <table class="table table-hover align-middle shadow-sm rounded" id="onlineTable">
        <thead class="table-info">
            <tr>
                <th>नाम</th>
                <th>प्रकार</th>
                <th>Google Form Link</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
// Toggle Fields
document.querySelectorAll('input[name="type"]').forEach(el => {
    el.addEventListener('change', function(){
        if(this.value === 'pdf'){
            document.querySelector('.pdf-field').classList.remove('d-none');
            document.querySelector('.google-field').classList.add('d-none');
        } else {
            document.querySelector('.google-field').classList.remove('d-none');
            document.querySelector('.pdf-field').classList.add('d-none');
        }
    });
});

// Toast
function showToast(message, type="success"){
    let container = document.querySelector('.toast-container');
    let toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0 show mb-2`;
    toast.role = "alert";
    toast.innerHTML = `<div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>`;
    container.appendChild(toast);
    setTimeout(()=>toast.remove(),3000);
}

// Fetch Offline (PDF) Forms
function fetchOfflineForms(){
    fetch("{{ url('api/mahila-aavedan-patra/offline') }}")
    .then(res=>res.json())
    .then(data=>{
        let tbody = document.querySelector("#offlineTable tbody");
        tbody.innerHTML = "";
        data.forEach(row=>{
            tbody.innerHTML += `<tr>
                <td>${row.name}</td>
                <td>${row.type}</td>
                <td><a href="/storage/${row.pdf}" target="_blank" class="btn btn-link btn-sm">View PDF</a></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning me-1" onclick="editData(${row.id}, '${row.name}', '${row.type}', '${row.pdf}', '')">✏️ Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteData(${row.id})">🗑 Delete</button>
                </td>
            </tr>`;
        })
    });
}

// Fetch Online (Google Form) Forms
function fetchOnlineForms(){
    fetch("{{ url('api/mahila-aavedan-patra/online') }}")
    .then(res=>res.json())
    .then(data=>{
        let tbody = document.querySelector("#onlineTable tbody");
        tbody.innerHTML = "";
        data.forEach(row=>{
            tbody.innerHTML += `<tr>
                <td>${row.name}</td>
                <td>${row.type}</td>
                <td><a href="${row.google_form_link}" target="_blank" class="btn btn-link btn-sm">Open Form</a></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning me-1" onclick="editData(${row.id}, '${row.name}', '${row.type}', '', '${row.google_form_link}')">✏️ Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteData(${row.id})">🗑 Delete</button>
                </td>
            </tr>`;
        })
    });
}

// Edit Data
function editData(id, name, type, pdf, link){
    document.querySelector("input[name='name']").value = name;
    document.querySelector(`input[name='type'][value='${type}']`).checked = true;
    document.getElementById('formId').value = id;

    if(type === "pdf"){
        document.querySelector('.pdf-field').classList.remove('d-none');
        document.querySelector('.google-field').classList.add('d-none');
    } else {
        document.querySelector('.google-field').classList.remove('d-none');
        document.querySelector('.pdf-field').classList.add('d-none');
        document.querySelector("input[name='google_form_link']").value = link;
    }

    document.getElementById("saveBtn").textContent = "Update";
    document.getElementById("cancelEdit").classList.remove("d-none");

    // Scroll to form and highlight
    document.getElementById("formCard").scrollIntoView({ behavior: "smooth", block: "center" });
    document.getElementById("formCard").classList.add("highlight");
    setTimeout(()=>document.getElementById("formCard").classList.remove("highlight"), 2000);
}

// Cancel Edit
document.getElementById("cancelEdit").addEventListener("click", function(){
    document.getElementById('aavedanForm').reset();
    document.getElementById("saveBtn").textContent = "Save";
    this.classList.add("d-none");
    document.getElementById('formId').value = "";
});

// Submit Form
document.getElementById('aavedanForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    let id = document.getElementById('formId').value;
    let method = id ? "POST" : "POST";
    let url = id ? `{{ url('api/mahila-aavedan-patra') }}/${id}?_method=PUT` : "{{ url('api/mahila-aavedan-patra') }}";

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async res=>{
        let data = await res.json();
        if(!res.ok){
            Object.values(data.errors).forEach(err=>showToast(err,"danger"));
        }else{
            showToast(data.message,"success");
            this.reset();
            document.getElementById("saveBtn").textContent = "Save";
            document.getElementById("cancelEdit").classList.add("d-none");
            document.getElementById('formId').value = "";
            fetchOfflineForms();
            fetchOnlineForms();
        }
    });
});

// Delete Data
function deleteData(id){
    if(confirm("Are you sure?")){
        fetch("{{ url('api/mahila-aavedan-patra') }}/" + id, {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res=>res.json())
        .then(data=>{
            showToast(data.message,"success");
            fetchOfflineForms();
            fetchOnlineForms();
        });
    }
}

// Initial Load
fetchOfflineForms();
fetchOnlineForms();
</script>
@endsection
