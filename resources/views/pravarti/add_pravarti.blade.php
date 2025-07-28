@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mt-4">
    <h3>📝 प्रवर्ति प्रबंधन</h3>

    <div class="mb-3">
        <input type="text" id="name" class="form-control" placeholder="प्रवर्ति नाम">
        <button class="btn btn-success mt-2" onclick="addPravarti()">➕ जोड़ें</button>
    </div>

    <table class="table table-bordered">
        <thead><tr><th>आईडी</th><th>नाम</th><th>क्रिया</th></tr></thead>
        <tbody id="pravartiList"></tbody>
    </table>
</div>

<script>
    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    };

    function loadPravartis() {
        fetch('/api/pravarti')
            .then(res => res.json())
            .then(data => {
                let rows = '';
                data.forEach(item => {
                    rows += `<tr>
                        <td>${item.id}</td>
                        <td><input id="name_${item.id}" value="${item.name}" class="form-control"></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="updatePravarti(${item.id})">संपादित करें</button>
                            <button class="btn btn-danger btn-sm" onclick="deletePravarti(${item.id})">हटाएं</button>
                        </td>
                    </tr>`;
                });
                document.getElementById('pravartiList').innerHTML = rows;
            });
    }

    function addPravarti() {
        const name = document.getElementById('name').value;
        fetch('/api/pravarti', {
            method: 'POST',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({ name })
        }).then(() => {
            document.getElementById('name').value = '';
            loadPravartis();
        });
    }

    function updatePravarti(id) {
        const name = document.getElementById(`name_${id}`).value;
        fetch(`/api/pravarti/${id}`, {
            method: 'PUT',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({ name })
        }).then(loadPravartis);
    }

    function deletePravarti(id) {
        if (!confirm("क्या आप वाकई हटाना चाहते हैं?")) return;
        fetch(`/api/pravarti/${id}`, {
            method: 'DELETE',
            headers
        }).then(loadPravartis);
    }

    document.addEventListener('DOMContentLoaded', loadPravartis);
</script>
@endsection
