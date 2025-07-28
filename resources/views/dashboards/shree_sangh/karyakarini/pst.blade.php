@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container mt-4">
    <h3>📋 पोस्ट जोड़ें</h3>
    <form id="pstForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="formMethod" value="POST">
        <input type="hidden" id="editId">

        <div class="mb-3">
            <label>नाम:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>पद:</label>
            <input type="text" name="post" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>फोटो (केवल छवि, 200KB तक):</label>
            <input type="file" name="photo" accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">सबमिट करें</button>
    </form>

    <hr>
    <h4>🔽 सभी पोस्ट</h4>
    <table class="table mt-3" id="pstTable">
        <thead>
            <tr>
                <th>नाम</th>
                <th>पद</th>
                <th>फोटो</th>
                <th>कार्य</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchData();

    document.getElementById('pstForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const id = document.getElementById('editId').value;
        const method = document.getElementById('formMethod').value;

        const url = id ? `/api/pst/${id}` : '/api/pst';
        if (id) formData.append('_method', 'PUT');

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(async res => {
            if (res.status === 403) {
                const data = await res.json();
                alert(data.error);  // "केवल 4 प्रविष्टियाँ ही अनुमत हैं।"
                return;
            }
            if (res.status === 422) {
                const data = await res.json();
                alert("⚠️ Validation Error:\n" + Object.values(data.errors).join('\n'));
                return;
            }

            return res.json();
        })
        .then(data => {
            if (!data) return;
            fetchData();
            form.reset();
            document.getElementById('editId').value = '';
            document.getElementById('formMethod').value = 'POST';
        });
    });
});

function fetchData() {
    fetch('/api/pst')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#pstTable tbody');
            tbody.innerHTML = '';
            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.post}</td>
                        <td>${item.photo ? `<img src="/storage/${item.photo}" width="60">` : ''}</td>
                        <td>
                            <button onclick="editPst(${item.id})" class="btn btn-sm btn-warning">Edit</button>
                            <button onclick="deletePst(${item.id})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function editPst(id) {
    fetch(`/api/pst/${id}`)
        .then(res => res.json())
        .then(data => {
            document.querySelector('[name="name"]').value = data.name;
            document.querySelector('[name="post"]').value = data.post;
            document.getElementById('editId').value = data.id;
            document.getElementById('formMethod').value = 'PUT';
        });
}

function deletePst(id) {
    if (confirm('क्या आप वाकई हटाना चाहते हैं?')) {
        fetch(`/api/pst/${id}`, {
            method: 'DELETE'
        }).then(() => fetchData());
    }
}
</script>

@endsection
