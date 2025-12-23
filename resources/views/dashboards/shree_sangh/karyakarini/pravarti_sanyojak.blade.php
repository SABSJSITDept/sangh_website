@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4">📋 प्रवर्ती संयोजक प्रबंधन</h3>

    {{-- Info Message --}}
    <div class="alert alert-info">
        ⚠️ सभी फ़ील्ड अनिवार्य हैं और फोटो का आकार <strong>200 KB</strong> से अधिक नहीं होना चाहिए।
    </div>

    {{-- 🔹 FORM --}}
    <div class="card shadow-sm p-4 mb-4 rounded-4 border-start border-success border-3">
        <form id="pravartiSanyojakForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editId" name="editId">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>नाम</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="नाम">
                </div>
                <div class="col-md-4">
                    <label>पद</label>
                    <select class="form-control" id="post" name="post" required>
                        <option value="">चयन करें</option>
                        <option value="संयोजक">संयोजक</option>
                        <option value="संयोजिका">संयोजिका</option>
                        <option value="सह संयोजक">सह संयोजक</option>
                        <option value="संयोजन मण्डल सदस्य">संयोजन मण्डल सदस्य</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>शहर</label>
                    <input type="text" class="form-control" id="city" name="city" required placeholder="शहर">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>प्रवर्ती</label>
                    <select class="form-control" id="pravarti_id" name="pravarti_id" required>
                        <option value="">चयन करें</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>मोबाइल</label>
                    <input type="text" class="form-control" id="mobile" name="mobile"
                        maxlength="10" pattern="\d{10}" required
                        placeholder="10 अंकों का मोबाइल नंबर"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="col-md-4">
                    <label>फोटो (200KB तक)</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    <div id="preview" class="mt-2"></div>
                </div>
                <div class="col-md-4">
                    <label>सत्र</label>
                    <select class="form-control" id="session" name="session" required>
                        <option value="">चयन करें</option>
                        <option value="2025-27" selected>2025-27</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success px-4">💾 सेव करें</button>
        </form>
    </div>

    {{-- 🔹 TABLE --}}
    <div id="pravartiSanyojakList"></div>
</div>

{{-- Toast Container --}}
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="toastBox"></div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// Toast Function
function showToast(message, type = 'success') {
    const bg = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white ${bg} border-0 show mb-2`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
        </div>
    `;
    document.getElementById('toastBox').appendChild(toast);
    toast.querySelector('.btn-close').onclick = () => toast.remove();
    setTimeout(() => toast.remove(), 3000);
}

// 🔹 Dropdown Load
fetch("/api/pravarti")
    .then(res => res.json())
    .then(data => {
        const dropdown = document.getElementById("pravarti_id");
        data.forEach(p => {
            dropdown.innerHTML += `<option value="${p.id}">${p.name}</option>`;
        });
    });

// 🔹 Image Preview
document.getElementById("photo").addEventListener("change", function () {
    const file = this.files[0];
    if (file && file.type.startsWith("image/")) {
        if (file.size > 200 * 1024) {
            showToast("❌ फोटो का आकार 200KB से अधिक नहीं होना चाहिए!", 'error');
            this.value = '';
            document.getElementById("preview").innerHTML = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("preview").innerHTML = `<img src="${e.target.result}" class="img-thumbnail" width="100">`;
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById("preview").innerHTML = "";
    }
});

// 🔹 Submit
document.getElementById("pravartiSanyojakForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const id = document.getElementById("editId").value;
    const formData = new FormData(this);
    formData.append('_token', csrf);
    if (id) formData.append('_method', 'PUT');

    const url = id ? `/api/pravarti-sanyojak/${id}` : '/api/pravarti-sanyojak';

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const result = await res.json();
        if (!res.ok) {
            showToast(result.error || "❌ कोई त्रुटि हुई।", 'error');
            return;
        }

        showToast("✅ सफलतापूर्वक सहेजा गया!");
        loadData();
        this.reset();
        document.getElementById('editId').value = '';
        document.getElementById('preview').innerHTML = '';
    } catch {
        showToast("❌ नेटवर्क या सर्वर की समस्या है।", 'error');
    }
});

// 🔹 Load Data
async function loadData() {
    const res = await fetch('/api/pravarti-sanyojak');
    const data = await res.json();
    const container = document.getElementById("pravartiSanyojakList");
    container.innerHTML = '';

    for (const [pravartiName, members] of Object.entries(data)) {
        const table = `
            <h5 class="text-primary border-bottom pb-1 mt-4">${pravartiName}</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>फोटो</th>
                            <th>नाम</th>
                            <th>पद</th>
                            <th>शहर</th>
                            <th>मोबाइल</th>
                            <th>सत्र</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${members.map(d => `
                            <tr class="text-center">
                                <td>
                                    <img src="${d.photo ? '/storage/' + d.photo : 'https://via.placeholder.com/80x100?text=No+Image'}"
                                        class="img-thumbnail" style="width: 80px; height: 100px; object-fit: cover;">
                                </td>
                                <td>${d.name}</td>
                                <td>${d.post}</td>
                                <td>${d.city}</td>
                                <td>${d.mobile}</td>
                                <td>${d.session || '2025-27'}</td>
                                <td>
                                    <button onclick="editEntry(${d.id})" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button onclick="deleteEntry(${d.id})" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        container.innerHTML += table;
    }
}

// 🔹 Edit
function editEntry(id) {
    fetch('/api/pravarti-sanyojak')
        .then(res => res.json())
        .then(data => {
            let all = Object.values(data).flat();
            const d = all.find(i => i.id === id);
            if (d) {
                document.getElementById("editId").value = d.id;
                document.getElementById("name").value = d.name;
                document.getElementById("post").value = d.post;
                document.getElementById("city").value = d.city;
                document.getElementById("pravarti_id").value = d.pravarti_id;
                document.getElementById("mobile").value = d.mobile;
                document.getElementById("session").value = d.session || '2025-27';
                document.getElementById("preview").innerHTML = `<img src="/storage/${d.photo}" class="img-thumbnail" width="100">`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
}

// 🔹 Delete
function deleteEntry(id) {
    if (!confirm("क्या आप वाकई इसे हटाना चाहते हैं?")) return;

    fetch(`/api/pravarti-sanyojak/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (res.ok) {
            showToast("✅ सफलतापूर्वक हटाया गया!");
            loadData();
        } else {
            showToast("❌ हटाने में समस्या आई", 'error');
        }
    });
}

// 🔹 Init
loadData();
</script>
@endsection
