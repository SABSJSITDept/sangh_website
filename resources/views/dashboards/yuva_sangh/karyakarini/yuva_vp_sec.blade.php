@extends('includes.layouts.yuva_sangh')

@section('title', 'उपाध्यक्ष / मंत्री - Yuva Sangh')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h2 class="fw-bold outfit-font mb-1">उपाध्यक्ष / मंत्री — सूची</h2>
                <p class="text-muted small mb-0">Manage the extended leadership team across different aanchals.</p>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <div class="position-relative d-none d-md-block">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input id="searchInput" class="form-control rounded-pill ps-5 border-0 shadow-sm" placeholder="Search members..." style="width: 250px;">
                </div>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#entryModal">
                    <i class="bi bi-plus-lg me-2"></i> Add New Member
                </button>
            </div>
        </div>
    </div>

    <!-- Stats & Filters -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-auto mb-3 mb-md-0">
            <ul class="nav nav-pills bg-white p-1 rounded-pill shadow-sm" id="postTabs">
                <li class="nav-item">
                    <button class="nav-link active rounded-pill px-4 py-2 small fw-bold" data-post="">All Members</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 py-2 small fw-bold" data-post="उपाध्यक्ष">उपाध्यक्ष</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 py-2 small fw-bold" data-post="मंत्री">मंत्री</button>
                </li>
            </ul>
        </div>
        <div class="col-md d-md-none mb-3">
             <input id="searchInputMobile" class="form-control rounded-pill border-0 shadow-sm" placeholder="Search members...">
        </div>
    </div>

    <!-- Table Section -->
    <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 border-0" style="width:60px">#</th>
                            <th class="py-3 border-0">Member</th>
                            <th class="py-3 border-0">Position</th>
                            <th class="py-3 border-0">Location</th>
                            <th class="py-3 border-0">Contact</th>
                            <th class="pe-4 py-3 border-0 text-end" style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vpTbody">
                        <tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary spinner-border-sm"></div></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Professional Modal -->
<div class="modal fade" id="entryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
            <form id="vpForm" enctype="multipart/form-data">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0" id="modalTitle">Add Member Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="editId">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3" required placeholder="Enter full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">Position <span class="text-danger">*</span></label>
                            <select name="post" id="post" class="form-select rounded-3" required>
                                <option value="">Select Post</option>
                                <option value="उपाध्यक्ष">उपाध्यक्ष</option>
                                <option value="मंत्री">मंत्री</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="aanchalSelectWrapper">
                            <label class="form-label fw-600 small text-uppercase text-muted">Aanchal <span class="text-danger">*</span></label>
                            <select name="aanchal" id="aanchal" class="form-select rounded-3" required>
                                <option value="">Loading aanchal...</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-none" id="aanchalTextWrapper">
                            <label class="form-label fw-600 small text-uppercase text-muted">Aanchal (Manual)</label>
                            <input type="text" name="aanchal_fallback" id="aanchal_fallback" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">City</label>
                            <input type="text" name="city" id="city" class="form-control rounded-3" placeholder="Enter city">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 small text-uppercase text-muted">Mobile Number</label>
                            <input type="text" name="mobile" id="mobile" class="form-control rounded-3" placeholder="Enter mobile number">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600 small text-uppercase text-muted">Photo <span class="text-danger" id="photoReq">*</span></label>
                            <div class="upload-area border rounded-3 p-3 text-center bg-light cursor-pointer" onclick="document.getElementById('photo').click()">
                                <i class="bi bi-camera-fill fs-3 text-muted d-block mb-2"></i>
                                <span class="small text-muted" id="fileName">Select Photo (Max 200KB)</span>
                                <input type="file" name="photo" id="photo" class="d-none" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow" id="saveBtn">Save Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const API_BASE = "/api/yuva-vp-sec";
let currentPostFilter = "";
let allData = [];

function toast(type, title, text='') {
    Swal.fire({ 
        icon: type, 
        title, 
        text, 
        timer: 2000, 
        showConfirmButton: false, 
        toast: true, 
        position: 'top-end',
        background: '#fff',
        color: '#1e293b'
    });
}

// Photo Selection Logic
document.getElementById('photo').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        if (file.size > 200 * 1024) {
            toast('error', 'Too large', 'Image must be under 200KB');
            this.value = "";
            return;
        }
        document.getElementById('fileName').textContent = file.name;
    }
});

async function loadAanchalOptions(){
  try {
    const res = await fetch("https://website.sadhumargi.in/api/aanchal");
    const arr = await res.json();
    const options = ['<option value="">Select Aanchal</option>'];
    arr.forEach(a => options.push(`<option value="${a.name}">${a.name}</option>`));
    document.getElementById('aanchal').innerHTML = options.join('');
    document.getElementById('aanchalSelectWrapper').classList.remove('d-none');
    document.getElementById('aanchalTextWrapper').classList.add('d-none');
  } catch(e) {
    document.getElementById('aanchalSelectWrapper').classList.add('d-none');
    document.getElementById('aanchalTextWrapper').classList.remove('d-none');
    toast('warning','Aanchal API unavailable','Enter manually.');
  }
}

async function fetchList(){
  const url = currentPostFilter ? `${API_BASE}?post=${encodeURIComponent(currentPostFilter)}` : API_BASE;
  const tbody = document.getElementById('vpTbody');
  tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary spinner-border-sm"></div></td></tr>`;
  try {
    const res = await fetch(url);
    allData = await res.json();
    renderTable();
  } catch(e) {
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">Failed to load data.</td></tr>`;
  }
}

function renderTable(){
  const tbody = document.getElementById('vpTbody');
  const searchQ = document.getElementById('searchInput').value.toLowerCase().trim() || document.getElementById('searchInputMobile').value.toLowerCase().trim();
  
  let rows = allData.filter(r => {
    if(!searchQ) return true;
    const hay = [r.name, r.city, r.aanchal, r.post, r.mobile].join(' ').toLowerCase();
    return hay.includes(searchQ);
  });
  
  if(!rows.length) {
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">No records found.</td></tr>`;
    return;
  }
  
  tbody.innerHTML = rows.map((r, i) => `
    <tr class="transition-all">
      <td class="ps-4 fw-bold text-muted small">${i + 1}</td>
      <td class="py-3">
        <div class="d-flex align-items-center gap-3">
          ${r.photo ? `<img src="${r.photo}" class="rounded-circle border shadow-sm" style="width:40px;height:40px;object-fit:cover;">` : `<div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted" style="width:40px;height:40px;"><i class="bi bi-person"></i></div>`}
          <div>
            <h6 class="mb-0 fw-bold outfit-font text-dark">${r.name}</h6>
            <small class="text-muted">${r.id}</small>
          </div>
        </div>
      </td>
      <td class="py-3">
        <span class="badge ${r.post === 'उपाध्यक्ष' ? 'bg-indigo-subtle text-indigo' : 'bg-success-subtle text-success'} rounded-pill px-3 py-1 border fw-normal small">
          ${r.post}
        </span>
      </td>
      <td class="py-3">
        <div class="fw-medium text-dark small">${r.aanchal ?? '—'}</div>
        <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>${r.city ?? '—'}</div>
      </td>
      <td class="py-3">
        <div class="small fw-bold text-primary">${r.mobile ?? '—'}</div>
      </td>
      <td class="pe-4 py-3 text-end">
        <div class="btn-group shadow-sm rounded-3 overflow-hidden">
            <button class="btn btn-sm btn-white border px-3" onclick="openEdit(${r.id})" title="Edit">
                <i class="bi bi-pencil-square text-primary"></i>
            </button>
            <button class="btn btn-sm btn-white border px-3" onclick="deleteRow(${r.id})" title="Delete">
                <i class="bi bi-trash3 text-danger"></i>
            </button>
        </div>
      </td>
    </tr>
  `).join('');
}

let _t;
['searchInput', 'searchInputMobile'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        clearTimeout(_t);
        _t = setTimeout(renderTable, 250);
    });
});

document.querySelectorAll('#postTabs .nav-link').forEach(btn => {
  btn.addEventListener('click', (e) => {
    document.querySelectorAll('#postTabs .nav-link').forEach(b => b.classList.remove('active'));
    e.target.classList.add('active');
    currentPostFilter = e.target.dataset.post || '';
    fetchList();
  });
});

let entryModal;
document.addEventListener("DOMContentLoaded", function() {
    entryModal = new bootstrap.Modal('#entryModal');
});

function openEdit(id){
  const r = allData.find(x => x.id == id);
  if(!r) return;
  document.getElementById('modalTitle').innerText = 'Edit Member Details';
  document.getElementById('editId').value = r.id;
  document.getElementById('name').value = r.name || '';
  document.getElementById('post').value = r.post || '';
  document.getElementById('city').value = r.city || '';
  document.getElementById('mobile').value = r.mobile || '';
  if(!document.getElementById('aanchalSelectWrapper').classList.contains('d-none')) {
      document.getElementById('aanchal').value = r.aanchal || '';
  } else {
      document.getElementById('aanchal_fallback').value = r.aanchal || '';
  }
  document.getElementById('photoReq').classList.add('d-none');
  document.getElementById('saveBtn').innerText = "Update Member";
  entryModal.show();
}

function resetForm(){
  document.getElementById('modalTitle').innerText = 'Add Member Details';
  document.getElementById('vpForm').reset();
  document.getElementById('editId').value = '';
  document.getElementById('photoReq').classList.remove('d-none');
  document.getElementById('saveBtn').innerText = "Save Member";
  document.getElementById('fileName').textContent = "Select Photo (Max 200KB)";
}

document.getElementById('vpForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const id = document.getElementById('editId').value;
  const form = new FormData(this);
  
  if(document.getElementById('aanchalSelectWrapper').classList.contains('d-none')) {
      form.set('aanchal', document.getElementById('aanchal_fallback').value.trim());
  } else {
      form.set('aanchal', document.getElementById('aanchal').value);
  }

  const btn = document.getElementById('saveBtn');
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

  const url = id ? `${API_BASE}/${id}` : API_BASE;
  if (id) form.append('_method', 'PUT');

  try {
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: form
    });
    
    btn.disabled = false;
    btn.innerHTML = originalHtml;

    if(!res.ok) {
        const err = await res.json();
        toast('error', 'Action Failed', err.message || 'Check form fields.');
        return;
    }
    
    const data = await res.json();
    toast('success', 'Success', data.message || 'Record saved.');
    entryModal.hide();
    fetchList();
  } catch(e) {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
    toast('error', 'Network Error');
  }
});

async function deleteRow(id){
    Swal.fire({
        title: 'Delete this member?',
        text: 'This record will be permanently removed.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Delete',
        borderRadius: '1.25rem'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const res = await fetch(`${API_BASE}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            toast('success', 'Deleted', data.message || 'Record removed.');
            fetchList();
        }
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    await loadAanchalOptions();
    await fetchList();
});
</script>

<style>
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .btn-white { background-color: #fff; border-color: #e2e8f0; }
    .btn-white:hover { background-color: #f1f5f9; }
    .text-indigo { color: #6366f1; }
    .bg-indigo-subtle { background-color: #e0e7ff; }
    .upload-area { border: 2px dashed #e2e8f0; cursor: pointer; }
    .upload-area:hover { border-color: #6366f1; background-color: #f8fafc; }
</style>
@endsection
