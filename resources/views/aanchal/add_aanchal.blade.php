@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mt-4">
    <h3>📋 आँचल जोड़ें</h3>

    <form id="aanchalForm">
        <input type="hidden" id="formMethod" value="POST">
        <input type="hidden" id="editId">
        <div class="mb-3">
            <label>नाम</label>
            <input type="text" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">सबमिट करें</button>
    </form>

    <hr>

    <h4>📄 आँचल सूची</h4>
    <table class="table table-bordered" id="aanchalTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>नाम</th>
                <th>एक्शन</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    loadAanchal();

    document.getElementById('aanchalForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('editId').value;
        const name = document.getElementById('name').value;
        const method = document.getElementById('formMethod').value;

        const url = id ? `/api/aanchal/${id}` : '/api/aanchal';
        const httpMethod = id ? 'PUT' : 'POST';

        fetch(url, {
            method: httpMethod,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ name: name })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('aanchalForm').reset();
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('editId').value = '';
            loadAanchal();
        });
    });
});

function loadAanchal() {
    fetch('/api/aanchal')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#aanchalTable tbody');
            tbody.innerHTML = '';
            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>
                            <button onclick="editAanchal(${item.id})" class="btn btn-sm btn-warning">Edit</button>
                            <button onclick="deleteAanchal(${item.id})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function editAanchal(id) {
    fetch(`/api/aanchal/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('editId').value = data.id;
            document.getElementById('formMethod').value = 'PUT';
        });
}

function deleteAanchal(id) {
    if (confirm('क्या आप इसको हटाना चाहते हैं?')) {
        fetch(`/api/aanchal/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(() => loadAanchal());
    }
}
</script>
@endsection
