@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.toast-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 1055;
}
</style>

<div class="container py-4">
    <h2 class="mb-4">समता जन कल्याण प्रणयाश</h2>

    {{-- 🔹 FORM --}}
    <form id="addForm" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">नाम</label>
            <input type="text" name="name" class="form-control" required />
        </div>
        <div class="col-md-6">
            <label class="form-label">शहर</label>
            <input type="text" name="city" class="form-control" required />
        </div>
        <div class="col-md-6">
            <label class="form-label">मोबाइल</label>
            <input type="text" name="mobile" class="form-control" required maxlength="10" />
        </div>
        <div class="col-md-6">
            <label class="form-label">फोटो</label>
            <input type="file" name="photo" accept="image/*" class="form-control" />
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">सेव करें</button>
            <button type="button" class="btn btn-secondary" id="cancelEdit" style="display:none;">रद्द करें</button>
        </div>
    </form>

    <hr>

    {{-- 🔹 DATA TABLE --}}
    <div class="table-responsive mt-4">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>📸 फोटो</th>
                    <th>नाम</th>
                    <th>शहर</th>
                    <th>मोबाइल</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dataList"></tbody>
        </table>
    </div>
</div>

{{-- 🔹 TOASTS --}}
<div class="toast-container">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="successMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="errorMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let isEditMode = false;
let editId = null;

function showToast(type, message) {
    const toastEl = document.getElementById(type + 'Toast');
    const toastMsg = document.getElementById(type + 'Message');
    toastMsg.innerText = message;
    new bootstrap.Toast(toastEl).show();
}

document.getElementById('addForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const url = isEditMode 
        ? `/api/samta-jan-kalyan-pranayash/${editId}`
        : `/api/samta-jan-kalyan-pranayash`;

    if (isEditMode) formData.append('_method', 'PUT');

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            showToast('success', isEditMode ? 'डेटा अपडेट हुआ!' : 'डेटा जोड़ा गया!');
            form.reset();
            isEditMode = false;
            editId = null;
            document.querySelector('button[type="submit"]').innerText = 'सेव करें';
            document.getElementById('cancelEdit').style.display = 'none';
            fetchData();
        } else {
            let msg = data.message || 'त्रुटि आई।';
            if (data.errors) {
                msg = Object.values(data.errors).flat().join(', ');
            }
            showToast('error', msg);
        }
    } catch (error) {
        showToast('error', 'सर्वर से कनेक्ट नहीं हो पाया।');
    }
});

document.getElementById('cancelEdit').addEventListener('click', () => {
    document.getElementById('addForm').reset();
    isEditMode = false;
    editId = null;
    document.querySelector('button[type="submit"]').innerText = 'सेव करें';
    document.getElementById('cancelEdit').style.display = 'none';
});

async function fetchData() {
    const res = await fetch('/api/samta-jan-kalyan-pranayash');
    const data = await res.json();
    const tbody = document.getElementById('dataList');
    tbody.innerHTML = '';

    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><img src="/storage/${item.photo ?? 'default.png'}" height="60" class="rounded-circle" onerror="this.src='/default.png'"></td>
            <td>${item.name}</td>
            <td>${item.city}</td>
            <td>${item.mobile}</td>
            <td>
                <button onclick="editItem(${item.id})" class="btn btn-sm btn-warning">Edit</button>
                <button onclick="deleteItem(${item.id})" class="btn btn-sm btn-danger">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

async function editItem(id) {
    const res = await fetch(`/api/samta-jan-kalyan-pranayash/${id}`);
    if (!res.ok) return showToast('error', "डेटा प्राप्त करने में त्रुटि");

    const item = await res.json();
    const form = document.getElementById('addForm');

    form.name.value = item.name;
    form.city.value = item.city;
    form.mobile.value = item.mobile;
    form.photo.value = ''; // File input can't be preset

    isEditMode = true;
    editId = id;
    document.querySelector('button[type="submit"]').innerText = 'अपडेट करें';
    document.getElementById('cancelEdit').style.display = 'inline-block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function deleteItem(id) {
    if (!confirm("क्या आप वाकई हटाना चाहते हैं?")) return;

    const res = await fetch(`/api/samta-jan-kalyan-pranayash/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    if (res.ok) {
        showToast('success', 'डेटा हटाया गया!');
        fetchData();
    } else {
        showToast('error', 'हटाने में त्रुटि हुई!');
    }
}

fetchData();
</script>
@endsection
