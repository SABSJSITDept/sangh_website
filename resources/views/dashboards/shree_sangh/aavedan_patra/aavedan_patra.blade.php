@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4 text-center">आवेदन पत्र</h4>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">➕ Add / Edit Aavedan Patra</div>
        <div class="card-body">
            <form id="aavedanForm">
                <input type="hidden" id="edit_id">

                <div class="mb-3">
                    <label>नाम</label>
                    <input type="text" class="form-control" id="name" required>
                </div>

                <div class="mb-3">
                    <label>फ़ाइल का प्रकार</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="file_type" id="fileTypePdf" value="pdf" checked>
                        <label class="form-check-label" for="fileTypePdf">Offline</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="file_type" id="fileTypeGoogle" value="google_form">
                        <label class="form-check-label" for="fileTypeGoogle">Online</label>
                    </div>
                </div>

                <div class="mb-3" id="pdfInputGroup">
                    <label>PDF फ़ाइल</label>
                    <input type="file" class="form-control" id="fileInput" accept=".pdf">
                </div>

                <div class="mb-3 d-none" id="googleInputGroup">
                    <label>Google Form लिंक</label>
                    <input type="text" class="form-control" id="googleFormLink" placeholder="https://...">
                </div>

                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">📄 Existing Aavedan Patra</div>
        <div class="card-body">
            <table class="table table-bordered" id="aavedanTable">
                <thead>
                    <tr>
                        <th>नाम</th>
                        <th>प्रकार</th>
                        <th>फ़ाइल</th>
                        <th>एक्शन</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bootstrap 5 CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Axios & JS --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    toggleFileInputs();
    fetchData();
});

document.querySelectorAll('input[name="file_type"]').forEach(input => {
    input.addEventListener('change', toggleFileInputs);
});

function toggleFileInputs() {
    const type = document.querySelector('input[name="file_type"]:checked').value;
    document.getElementById('pdfInputGroup').classList.toggle('d-none', type !== 'pdf');
    document.getElementById('googleInputGroup').classList.toggle('d-none', type !== 'google_form');
}

function fetchData() {
    axios.get('/api/aavedan-patra').then(res => {
        const tbody = document.querySelector('#aavedanTable tbody');
        tbody.innerHTML = '';
        res.data.forEach(item => {
            const fileLink = item.file_type === 'pdf'
                ? `<a href="/storage/aavedan_patra/${item.file}" target="_blank">📎 PDF</a>`
                : `<a href="${item.file}" target="_blank">🔗 Google Form</a>`;
            tbody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.file_type}</td>
                    <td>${fileLink}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editItem(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                </tr>
            `;
        });
    });
}

document.getElementById('aavedanForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('edit_id').value;
    const fileType = document.querySelector('input[name="file_type"]:checked').value;
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('file_type', fileType);

    const fileInput = document.getElementById('fileInput').files[0];
    const googleFormLink = document.getElementById('googleFormLink').value;

    if (fileType === 'pdf') {
        if (!fileInput && !id) return alert("कृपया PDF चुनें");
        if (fileInput) formData.append('file', fileInput);
    } else {
        if (!googleFormLink) return alert("Google Form लिंक डालें");
        formData.append('file', googleFormLink);
    }

    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    };

    const method = id ? 'post' : 'post';
    const url = id ? `/api/aavedan-patra/${id}` : '/api/aavedan-patra';
    if (id) formData.append('_method', 'PUT');

    axios({ method, url, data: formData, headers }).then(() => {
        document.getElementById('aavedanForm').reset();
        document.getElementById('edit_id').value = '';
        toggleFileInputs();
        fetchData();
    });
});

function editItem(id) {
    axios.get('/api/aavedan-patra').then(res => {
        const data = res.data.find(i => i.id === id);
        document.getElementById('edit_id').value = data.id;
        document.getElementById('name').value = data.name;

        if (data.file_type === 'google_form') {
            document.getElementById('fileTypeGoogle').checked = true;
            document.getElementById('googleFormLink').value = data.file;
        } else {
            document.getElementById('fileTypePdf').checked = true;
        }

        toggleFileInputs();
    });
}

function deleteItem(id) {
    if (confirm("क्या आप वाकई हटाना चाहते हैं?")) {
        axios.delete(`/api/aavedan-patra/${id}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(fetchData);
    }
}
</script>
@endsection
