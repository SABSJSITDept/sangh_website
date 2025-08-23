@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4">
    <h3 class="mb-3">Yuva Sangh Pravartiya</h3>

    {{-- Info Note --}}
    <div class="alert alert-info">
        Photo optional hai. Agar upload karte ho to <strong>200KB</strong> tak ki <strong>image</strong> (jpg, jpeg, png, webp, gif) hi allow hogi.
    </div>

    {{-- Create / Update Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="pravartiyaForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" value="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Heading <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="heading" required maxlength="255">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" rows="4" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Photo (optional, ≤ 200KB, image only)</label>
                        <input type="file" class="form-control" id="photo" accept="image/*">
                        <div class="form-text">If updating without changing image, leave this empty.</div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" id="submitBtn" class="btn btn-primary">Save</button>
                    <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>

    {{-- List --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">All Entries</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px">#</th>
                            <th>Heading</th>
                            <th>Content</th>
                            <th>Photo</th>
                            <th style="width:180px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE = "/api/yuva-pravartiya";

// Toast helper
function toast(icon, title) {
    Swal.fire({ icon, title, timer: 1400, showConfirmButton: false, position: 'top-end', toast: true });
}

// Confirm helper
function confirmBox(title="Are you sure?") {
    return Swal.fire({
        title, icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Yes', cancelButtonText: 'Cancel'
    });
}

function clearForm() {
    document.getElementById('edit_id').value = "";
    document.getElementById('heading').value = "";
    document.getElementById('content').value = "";
    document.getElementById('photo').value = "";
    document.getElementById('submitBtn').textContent = "Save";
}

// Client-side checks for image size/type
function validateFile(input) {
    if (!input.files || !input.files[0]) return true; // optional
    const file = input.files[0];
    const isImage = file.type.startsWith("image/");
    const under200KB = file.size <= 200 * 1024;
    if (!isImage) { toast('error', 'Only image files allowed'); return false; }
    if (!under200KB) { toast('error', 'Image must be ≤ 200KB'); return false; }
    return true;
}

// Fetch list
function fetchAll() {
    fetch(API_BASE, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => renderTable(data))
    .catch(() => toast('error','Failed to load data'));
}

function renderTable(data) {
    const tbody = document.getElementById('tbody');
    tbody.innerHTML = "";
    if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No data</td></tr>`;
        return;
    }
    data.forEach((item, idx) => {
        const imgHtml = item.photo ? `<img src="${item.photo}" alt="photo" class="img-thumbnail" style="max-width:80px">` : `<span class="text-muted">—</span>`;
        const row = `
            <tr>
                <td>${idx + 1}</td>
                <td>${escapeHtml(item.heading)}</td>
                <td>${escapeHtml(item.content)}</td>
                <td>${imgHtml}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-2" onclick='editItem(${JSON.stringify(item)})'>Edit</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="delItem(${item.id})">Delete</button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}

// Basic XSS escape for display
function escapeHtml(str) {
    return (str ?? '').toString().replace(/[&<>"'`=\/]/g, s => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    }[s]));
}

// Edit button -> fill form
function editItem(item) {
    document.getElementById('edit_id').value = item.id;
    document.getElementById('heading').value = item.heading ?? '';
    document.getElementById('content').value = item.content ?? '';
    document.getElementById('photo').value = ''; // choose new to replace
    document.getElementById('submitBtn').textContent = "Update";
    window.scrollTo({top: 0, behavior: 'smooth'});
}

// Delete
function delItem(id) {
    confirmBox("Delete this entry?")
    .then(res => {
        if (!res.isConfirmed) return;
        return fetch(`${API_BASE}/${id}`, {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    })
    .then(r => r ? r.json() : null)
    .then(resp => {
        if (!resp) return;
        toast('success', resp.message || 'Deleted');
        fetchAll();
    })
    .catch(() => toast('error', 'Delete failed'));
}

// Create/Update submit
document.getElementById('pravartiyaForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Basic required checks
    const heading = document.getElementById('heading').value.trim();
    const content = document.getElementById('content').value.trim();
    const photoInput = document.getElementById('photo');

    if (!heading) { toast('error','Heading required'); return; }
    if (!content) { toast('error','Content required'); return; }
    if (!validateFile(photoInput)) return;

    const id = document.getElementById('edit_id').value;
    const isUpdate = Boolean(id);

    const form = new FormData();
    form.append('heading', heading);
    form.append('content', content);
    if (photoInput.files[0]) form.append('photo', photoInput.files[0]);

    fetch(isUpdate ? `${API_BASE}/${id}` : API_BASE, {
        method: isUpdate ? "POST" : "POST", // for PUT/PATCH with method spoof
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: (function(){
            // Laravel can accept PUT/PATCH via _method
            if (isUpdate) form.append('_method', 'PUT');
            return form;
        })()
    })
    .then(async r => {
        const data = await r.json();
        if (!r.ok) throw data;
        toast('success', data.message || (isUpdate ? 'Updated' : 'Created'));
        clearForm();
        fetchAll();
    })
    .catch(err => {
        // Backend validation errors
        if (err && err.errors) {
            const firstKey = Object.keys(err.errors)[0];
            toast('error', err.errors[firstKey][0]);
        } else {
            toast('error', 'Save failed');
        }
    });
});

document.getElementById('resetBtn').addEventListener('click', clearForm);

// Init
fetchAll();
</script>
@endsection
