@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* 🔹 Toast container */
.toast-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 1055;
}

/* 🔹 Form styling */
#addForm input.form-control {
    border: 2px solid #343a40; /* dark border */
    background-color: #f8f9fa; /* subtle background */
    color: #212529;
    transition: all 0.3s ease;
}

#addForm input.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
}

/* 🔹 Buttons */
#addForm button {
    font-weight: 600;
}

/* 🔹 Table styling */
.table {
    border: 2px solid #343a40;
}

.table th, .table td {
    vertical-align: middle !important;
    border: 1px solid #343a40;
}

.table tbody tr:hover {
    background-color: #e9ecef;
}

/* 🔹 Icons inside table */
.table td button i {
    margin-right: 4px;
}

/* 🔹 Small notes */
.text-muted {
    font-size: 0.85rem;
}
</style>

<div class="container py-4 shadow rounded border border-dark">
    <h2 class="mb-4 text-center text-primary"><i class="bi bi-people-fill"></i> समता जन कल्याण प्रणयाश</h2>

    {{-- 🔹 FORM --}}
    <form id="addForm" enctype="multipart/form-data" class="row g-3 p-3 bg-white rounded border border-dark">
        <input type="hidden" id="editId" value="">
        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-fill"></i> नाम</label>
            <input type="text" id="name" name="name" class="form-control border border-dark" required />
        </div>
        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-geo-alt-fill"></i> शहर</label>
            <input type="text" id="city" name="city" class="form-control border border-dark" required />
        </div>
        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-telephone-fill"></i> मोबाइल</label>
            <input type="text" id="mobile" name="mobile" class="form-control border border-dark" maxlength="10" required />
        </div>
        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-image-fill"></i> फोटो</label>
            <input type="file" id="photo" name="photo" accept="image/*" class="form-control border border-dark" />
            <small class="text-muted">* नई फ़ोटो चुनें यदि अपडेट करना हो (max 200 KB)</small>
        </div>
        <div class="col-md-6">
            <label class="form-label"><i class="bi bi-calendar-fill"></i> सत्र</label>
            <select id="session" name="session" class="form-select border border-dark" required>
                <option value="">सत्र चुनें</option>
                <option value="2025-27" selected>2025-27</option>
            </select>
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> सेव करें</button>
            <button type="button" class="btn btn-secondary" id="cancelEdit" style="display:none;"><i class="bi bi-x-circle"></i> रद्द करें</button>
        </div>
    </form>

    <hr class="my-4">

    {{-- 🔹 DATA TABLE --}}
    <div class="table-responsive border border-dark rounded shadow-sm p-2">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>📸 फोटो</th>
                    <th>नाम</th>
                    <th>शहर</th>
                    <th>मोबाइल</th>
                    <th>📅 सत्र</th>
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

    const name = document.getElementById('name').value.trim();
    const city = document.getElementById('city').value.trim();
    const mobile = document.getElementById('mobile').value.trim();
    const photoInput = document.getElementById('photo');
    const photoFile = photoInput.files[0];

    // ✅ Client-side validation
    if (!name || !city || !mobile) {
        showToast('error', 'कृपया सभी आवश्यक फ़ील्ड्स भरें।');
        return;
    }

    // Photo required check for create mode
    if (!isEditMode && !photoFile) {
        showToast('error', 'कृपया फ़ोटो चुनें।');
        return;
    }

    // Photo size check
    if (photoFile && photoFile.size > 200 * 1024) { // 200 KB
        showToast('error', 'फ़ोटो का आकार 200 KB से अधिक नहीं होना चाहिए।');
        return;
    }

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
            <td>${item.session || '2025-27'}</td>
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
    form.session.value = item.session || '2025-27';
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
