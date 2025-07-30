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
                    <label>फ़ाइल (PDF या Google Form लिंक)</label>
                    <input type="file" class="form-control" id="fileInput" accept=".pdf">
                    <input type="text" class="form-control mt-2" id="googleFormLink" placeholder="या Google Form लिंक डालें">
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
document.addEventListener('DOMContentLoaded', fetchData);

function fetchData() {
    axios.get('/api/aavedan-patra').then(res => {
        const tbody = document.querySelector('#aavedanTable tbody');
        tbody.innerHTML = '';
        res.data.forEach(item => {
            const tr = document.createElement('tr');
            const fileLink = item.file.endsWith('.pdf')
                ? `<a href="/storage/aavedan_patra/${item.file}" target="_blank">📎 PDF</a>`
                : `<a href="${item.file}" target="_blank">🔗 Google Form</a>`;

            tr.innerHTML = `
                <td>${item.name}</td>
                <td>${fileLink}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editItem(${item.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    });
}

document.getElementById('aavedanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);

    const fileInput = document.getElementById('fileInput').files[0];
    const googleFormLink = document.getElementById('googleFormLink').value;

    if (fileInput) {
        formData.append('file', fileInput);
    } else if (googleFormLink) {
        formData.append('file', googleFormLink);
    } else {
        alert('PDF या Google Form लिंक दें!');
        return;
    }

    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    };

    const method = id ? 'post' : 'post';
    const url = id ? `/api/aavedan-patra/${id}` : '/api/aavedan-patra';

    if (id) formData.append('_method', 'PUT');

    axios({
        method,
        url,
        data: formData,
        headers
    }).then(() => {
        document.getElementById('aavedanForm').reset();
        document.getElementById('edit_id').value = '';
        fetchData();
    });
});

function editItem(id) {
    axios.get('/api/aavedan-patra').then(res => {
        const data = res.data.find(i => i.id === id);
        document.getElementById('edit_id').value = data.id;
        document.getElementById('name').value = data.name;
        if (!data.file.endsWith('.pdf')) {
            document.getElementById('googleFormLink').value = data.file;
        }
    });
}

function deleteItem(id) {
    if (confirm("Delete this?")) {
        axios.delete(`/api/aavedan-patra/${id}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(fetchData);
    }
}
</script>
@endsection
