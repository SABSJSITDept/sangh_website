@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4 text-success"><i class="bi bi-journal-code"></i> JSP Exam Management</h2>

    <div class="card shadow-sm border-success mb-4">
        <div class="card-header bg-success text-white">
            <strong><i class="bi bi-file-earmark-plus"></i> Add / Edit Exam</strong>
        </div>
        <div class="card-body">
            <form id="examForm" class="row g-3" enctype="multipart/form-data">
                <input type="hidden" id="exam_id">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter exam name" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Type</label>
                    <div class="d-flex align-items-center gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type_pdf" value="pdf" checked>
                            <label class="form-check-label" for="type_pdf">PDF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type_form" value="form">
                            <label class="form-check-label" for="type_form">Google Form</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" id="pdf_input">
                    <label class="form-label fw-semibold">Upload PDF</label>
                    <input type="file" class="form-control" id="pdf" accept="application/pdf">
                </div>

                <div class="col-md-6 d-none" id="form_input">
                    <label class="form-label fw-semibold">Google Form Link</label>
                    <input type="text" class="form-control" id="google_form_link" placeholder="https://forms.gle/...">
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong><i class="bi bi-list-task"></i> Exam List</strong>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0" id="examTable">
                <thead class="table-success text-center">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-center"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest'
};

const apiUrl = "/api/jsp-exam";

function fetchData() {
    fetch(apiUrl)
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#examTable tbody");
            tbody.innerHTML = "";
            data.forEach(row => {
                const type = row.pdf ? 'PDF' : 'Google Form';
                const link = row.pdf ? `/storage/${row.pdf}` : row.google_form_link;

                tbody.innerHTML += `
                    <tr>
                        <td>${row.name}</td>
                        <td><span class="badge bg-${row.pdf ? 'primary' : 'info'}">${type}</span></td>
                        <td><a href="${link}" target="_blank" class="text-decoration-none"><i class="bi bi-box-arrow-up-right"></i> View</a></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editRow(${row.id}, '${row.name}', '${row.pdf ?? ''}', '${row.google_form_link ?? ''}')"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteRow(${row.id})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>`;
            });
        });
}

function editRow(id, name, pdf, googleForm) {
    document.getElementById('exam_id').value = id;
    document.getElementById('name').value = name;

    if (pdf && pdf !== "null") {
        document.getElementById('type_pdf').checked = true;
        toggleInputs('pdf');
    } else {
        document.getElementById('type_form').checked = true;
        toggleInputs('form');
        document.getElementById('google_form_link').value = googleForm;
    }
}

function deleteRow(id) {
    if (confirm("Are you sure you want to delete this exam?")) {
        fetch(`${apiUrl}/${id}`, {
            method: "DELETE",
            headers
        }).then(() => fetchData());
    }
}

document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function () {
        toggleInputs(this.value);
    });
});

function toggleInputs(type) {
    document.getElementById('pdf_input').classList.toggle('d-none', type !== 'pdf');
    document.getElementById('form_input').classList.toggle('d-none', type !== 'form');
}

document.getElementById('examForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const id = document.getElementById('exam_id').value;
    const selectedType = document.querySelector('input[name="type"]:checked').value;

    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);

    if (selectedType === 'pdf') {
        const pdfFile = document.getElementById('pdf').files[0];
        if (pdfFile) formData.append('pdf', pdfFile);
    } else {
        formData.append('google_form_link', document.getElementById('google_form_link').value);
    }

    const url = id ? `${apiUrl}/${id}` : apiUrl;
    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: "POST",
        headers,
        body: formData
    }).then(() => {
        this.reset();
        document.getElementById('exam_id').value = "";
        toggleInputs('pdf');
        fetchData();
    });
});

fetchData();
</script>
@endsection
