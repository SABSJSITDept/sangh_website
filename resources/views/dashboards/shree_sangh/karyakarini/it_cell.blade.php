@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h3>📋 IT CELL प्रबंधन</h3>

    {{-- ℹ️ Note Message --}}
    <div class="alert alert-info">
        ⚠️ सभी फ़ील्ड अनिवार्य हैं और फोटो का आकार <strong>200 KB</strong> से अधिक नहीं होना चाहिए।
    </div>

    {{-- ✅ FORM --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="itCellForm" enctype="multipart/form-data">
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

{{-- Toast Container --}}
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="toastBox"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('itCellForm');
    const list = document.getElementById('itCellList');
    const toastBox = document.getElementById('toastBox');

    const showToast = (message, type = 'success') => {
        const bg = type === 'success' ? 'bg-success' : 'bg-danger';
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white ${bg} border-0 show mb-2`;
        toast.role = 'alert';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        toastBox.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    };

    const fetchList = () => {
        fetch('/api/it-cell')
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '';
                data.sort((a, b) => a.priority - b.priority);
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.photo ? `<img src="/storage/${item.photo}" style="height:60px;" class="rounded">` : '❌'}</td>
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

        // Frontend validation
        const photo = form.photo.files[0];
        if (photo && photo.size > 200 * 1024) {
            showToast('फोटो का आकार 200KB से अधिक नहीं होना चाहिए!', 'error');
            return;
        }

        const formData = new FormData(form);
        const id = document.getElementById('editId').value;
        const url = id ? `/api/it-cell/${id}` : '/api/it-cell';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(async res => {
            if (!res.ok) {
                const errorData = await res.json();
                showToast(errorData.message || 'कुछ गलती हुई है', 'error');
                return;
            }
            return res.json();
        })
        .then(data => {
            if (data) {
                form.reset();
                document.getElementById('editId').value = '';
                fetchList();
                showToast('सफलतापूर्वक सहेजा गया!');
            }
        })
        .catch(() => showToast('नेटवर्क त्रुटि!', 'error'));
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => {
                if (res.ok) {
                    fetchList();
                    showToast('सफलतापूर्वक हटाया गया!');
                } else {
                    showToast('हटाने में समस्या आई', 'error');
                }
            });
        }
    };

    fetchList();
});
</script>
@endsection
