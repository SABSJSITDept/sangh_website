@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mt-4">
    <h3>📋 IT CELL प्रबंधन</h3>

    {{-- ✅ FORM --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="itCellForm" enctype="multipart/form-data">
                <input type="hidden" id="formMethod" value="POST">
                <input type="hidden" id="editId">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>नाम</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>पोस्ट</label>
                        <input type="text" class="form-control" name="post" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>शहर</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>मोबाइल</label>
                        <input type="text" class="form-control" name="mobile" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>प्राथमिकता (Priority)</label>
                        <input type="number" class="form-control" name="priority" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>फोटो (200KB तक)</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>
                </div>

                <button type="submit" class="btn btn-success px-4">💾 सहेजें</button>
            </form>
        </div>
    </div>

    {{-- ✅ LIST --}}
    <hr>
    <h4 class="mt-4">📃 सभी IT CELL सदस्य</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>📷 फोटो</th>
                    <th>👤 नाम</th>
                    <th>📌 पोस्ट</th>
                    <th>🏙 शहर</th>
                    <th>📞 मोबाइल</th>
                    <th>🔢 प्राथमिकता</th>
                    <th>⚙️ कार्रवाई</th>
                </tr>
            </thead>
            <tbody id="itCellList"></tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('itCellForm');
    const list = document.getElementById('itCellList');

    const fetchList = () => {
        fetch('/api/it-cell')
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '';
                data.sort((a, b) => a.priority - b.priority); // Priority sorting

                data.forEach(item => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>
                            ${item.photo 
                                ? `<img src="/storage/${item.photo}" style="height:60px; width:auto;" class="rounded">`
                                : '❌'}
                        </td>
                        <td>${item.name}</td>
                        <td>${item.post}</td>
                        <td>${item.city}</td>
                        <td>${item.mobile}</td>
                        <td>${item.priority}</td>
                        <td>
                            <button onclick="editItem(${item.id})" class="btn btn-sm btn-warning me-1">✏️</button>
                            <button onclick="deleteItem(${item.id})" class="btn btn-sm btn-danger">🗑</button>
                        </td>
                    `;

                    list.appendChild(row);
                });
            });
    };

    form.addEventListener('submit', e => {
        e.preventDefault();

        const formData = new FormData(form);
        const id = document.getElementById('editId').value;
        const method = id ? `/api/it-cell/${id}` : '/api/it-cell';

        fetch(method, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => {
            form.reset();
            document.getElementById('editId').value = '';
            fetchList();
        });
    });

    window.editItem = id => {
        fetch(`/api/it-cell`)
            .then(res => res.json())
            .then(data => {
                const item = data.find(i => i.id === id);
                if (item) {
                    form.name.value = item.name;
                    form.post.value = item.post;
                    form.city.value = item.city;
                    form.mobile.value = item.mobile;
                    form.priority.value = item.priority;
                    document.getElementById('editId').value = item.id;

                    form.scrollIntoView({ behavior: 'smooth' });
                }
            });
    };

    window.deleteItem = id => {
        if (confirm('हटाना निश्चित है?')) {
            fetch(`/api/it-cell/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(() => fetchList());
        }
    };

    fetchList();
});
</script>
@endsection
