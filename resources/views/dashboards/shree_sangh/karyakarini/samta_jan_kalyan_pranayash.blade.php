@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">समता जन कल्याण प्रणयाश</h2>

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

    <div class="row mt-4" id="dataList">
        <!-- Cards will be rendered here -->
    </div>
</div>

<script>
let isEditMode = false;
let editId = null;

document.getElementById('addForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const url = isEditMode 
        ? `/api/samta-jan-kalyan-pranayash/${editId}`
        : `/api/samta-jan-kalyan-pranayash`;

    if (isEditMode) {
        formData.append('_method', 'PUT');
    }

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    if (response.ok) {
        alert(isEditMode ? "Updated successfully!" : "Saved successfully!");
        form.reset();
        isEditMode = false;
        editId = null;
        document.querySelector('button[type="submit"]').innerText = 'सेव करें';
        document.getElementById('cancelEdit').style.display = 'none';
        fetchData();
    } else {
        alert("Error submitting form.");
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
    const list = document.getElementById('dataList');
    list.innerHTML = '';

    data.forEach(item => {
        const col = document.createElement('div');
        col.className = 'col-md-4';
        col.innerHTML = `
            <div class="card mb-3">
                <img src="/storage/${item.photo ?? 'default.png'}" class="card-img-top" style="height:200px; object-fit:cover;" onerror="this.src='/default.png'">
                <div class="card-body">
                    <h5 class="card-title">${item.name}</h5>
                    <p class="card-text">${item.city} <br> ${item.mobile}</p>
                    <button onclick="editItem(${item.id})" class="btn btn-sm btn-warning">Edit</button>
                    <button onclick="deleteItem(${item.id})" class="btn btn-sm btn-danger">Delete</button>
                </div>
            </div>
        `;
        list.appendChild(col);
    });
}

async function editItem(id) {
    const res = await fetch(`/api/samta-jan-kalyan-pranayash/${id}`);
    if (!res.ok) return alert("Error fetching item");

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
    if (!confirm("Are you sure you want to delete this?")) return;

    const res = await fetch(`/api/samta-jan-kalyan-pranayash/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    if (res.ok) {
        alert("Deleted successfully!");
        fetchData();
    } else {
        alert("Failed to delete!");
    }
}

fetchData();
</script>
@endsection
