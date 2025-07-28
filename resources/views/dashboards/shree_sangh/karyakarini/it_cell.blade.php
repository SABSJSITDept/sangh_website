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
                <div class="col-md-6 mb-3">
                    <label>नाम</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>पोस्ट</label>
                    <input type="text" class="form-control" name="post" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>शहर</label>
                    <input type="text" class="form-control" name="city" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>मोबाइल</label>
                    <input type="text" class="form-control" name="mobile" required>
                </div>
            </div>

            <div class="mb-3">
                <label>फोटो (200KB तक)</label>
                <input type="file" class="form-control" name="photo" accept="image/*">
            </div>

            <button type="submit" class="btn btn-success px-4">💾 सहेजें</button>
        </form>
    </div>
</div>


    {{-- ✅ LIST --}}
    <hr>
    <h4 class="mt-4">📃 सभी IT CELL सदस्य</h4>
    <div id="itCellList" class="row"></div>
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
                data.forEach(item => {
                    list.innerHTML += `
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>${item.name}</h5>
                                    <p>📌 ${item.post} - ${item.city}</p>
                                    <p>📞 ${item.mobile}</p>
                                    ${item.photo ? `<img src="/storage/${item.photo}" class="img-fluid rounded mb-2" style="max-height:150px;">` : ''}
                                    <button onclick="editItem(${item.id})" class="btn btn-warning btn-sm">✏️ Edit</button>
                                    <button onclick="deleteItem(${item.id})" class="btn btn-danger btn-sm">🗑 Delete</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
    }

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
                    document.getElementById('editId').value = item.id;
                }
            });
    }

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
    }

    fetchList();
});
</script>
@endsection
