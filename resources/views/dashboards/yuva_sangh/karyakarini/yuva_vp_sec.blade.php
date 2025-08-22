@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .table thead th { position: sticky; top: 0; background: #fff; z-index: 1; }
    .modal-content { display: flex; flex-direction: column; max-height: 90vh; }
    .modal-body { overflow-y: auto; }
    .modal-footer { position: sticky; bottom: 0; background: #fff; z-index: 10; }
</style>

<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h3 class="m-0">उपाध्यक्ष / मंत्री — सूची</h3>
        <div class="d-flex gap-2">
            <input id="searchInput" class="form-control" placeholder="Search by name, city, aanchal">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#entryModal">
                + Add New
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-3" id="postTabs">
        <li class="nav-item"><button class="nav-link active" data-post="">All</button></li>
        <li class="nav-item"><button class="nav-link" data-post="उपाध्यक्ष">उपाध्यक्ष</button></li>
        <li class="nav-item"><button class="nav-link" data-post="मंत्री">मंत्री</button></li>
    </ul>

    <!-- Table -->
    <div class="table-responsive border rounded-3">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th style="width:120px">Photo</th>
                    <th>Name</th>
                    <th>Post</th>
                    <th>Aanchal</th>
                    <th>City</th>
                    <th>Mobile</th>
                    <th style="width:140px">Actions</th>
                </tr>
            </thead>
            <tbody id="vpTbody">
                <tr><td colspan="8" class="text-center py-4">Loading...</td></tr>
            </tbody>
        </table>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="entryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="vpForm" enctype="multipart/form-data" class="h-100 d-flex flex-column">
        
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body flex-grow-1">
          <input type="hidden" id="editId">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Post <span class="text-danger">*</span></label>
              <select name="post" id="post" class="form-select" required>
                  <option value="">Select Post</option>
                  <option value="उपाध्यक्ष">उपाध्यक्ष</option>
                  <option value="मंत्री">मंत्री</option>
              </select>
            </div>

            <div class="col-md-6" id="aanchalSelectWrapper">
              <label class="form-label">Aanchal <span class="text-danger">*</span></label>
              <select name="aanchal" id="aanchal" class="form-select" required>
                  <option value="">Loading aanchal...</option>
              </select>
            </div>

            <div class="col-md-6 d-none" id="aanchalTextWrapper">
              <label class="form-label">Aanchal (Fallback) <span class="text-danger">*</span></label>
              <input type="text" name="aanchal_fallback" id="aanchal_fallback" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">City</label>
              <input type="text" name="city" id="city" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Mobile</label>
              <input type="text" name="mobile" id="mobile" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Photo <span class="text-danger" id="photoReq">*</span></label>
              <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
              <div class="form-text">Only image, max 200 KB</div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API_BASE = "/api/yuva-vp-sec";
let currentPostFilter = "";
let allData = [];

function toast(type,title,text=''){Swal.fire({icon:type,title,text,timer:1800,showConfirmButton:false});}

// aanchal load
async function loadAanchalOptions(){
  try{
    const res=await fetch("https://website.sadhumargi.in/api/aanchal");
    const arr=await res.json();
    const options=['<option value="">Select Aanchal</option>'];
    arr.forEach(a=>options.push(`<option value="${a.name}">${a.name}</option>`));
    document.getElementById('aanchal').innerHTML=options.join('');
    document.getElementById('aanchalSelectWrapper').classList.remove('d-none');
    document.getElementById('aanchalTextWrapper').classList.add('d-none');
  }catch(e){
    document.getElementById('aanchalSelectWrapper').classList.add('d-none');
    document.getElementById('aanchalTextWrapper').classList.remove('d-none');
    toast('warning','Aanchal API failed','Please enter manually.');
  }
}

// fetch list
async function fetchList(){
  const url=currentPostFilter?`${API_BASE}?post=${encodeURIComponent(currentPostFilter)}`:API_BASE;
  const tbody=document.getElementById('vpTbody');
  tbody.innerHTML=`<tr><td colspan="8" class="text-center py-4">Loading...</td></tr>`;
  const res=await fetch(url);allData=await res.json();renderTable();
}

function renderTable(){
  const tbody=document.getElementById('vpTbody');
  const q=document.getElementById('searchInput').value.toLowerCase().trim();
  let rows=allData.filter(r=>{
    if(!q)return true;
    const hay=[r.name,r.city,r.aanchal,r.post,r.mobile].join(' ').toLowerCase();
    return hay.includes(q);
  });
  if(!rows.length){tbody.innerHTML=`<tr><td colspan="8" class="text-center py-4">No records found</td></tr>`;return;}
  let i=1;
  tbody.innerHTML=rows.map(r=>`
    <tr>
      <td>${i++}</td>
      <td>${r.photo?`<img src="${r.photo}" style="width:90px;height:60px;object-fit:cover;border-radius:.4rem;">`:''}</td>
      <td>${r.name}</td>
      <td>${r.post}</td>
      <td>${r.aanchal??''}</td>
      <td>${r.city??''}</td>
      <td>${r.mobile??''}</td>
      <td>
        <div class="d-flex gap-1">
          <button class="btn btn-sm btn-outline-primary" onclick='openEdit(${JSON.stringify(r)})'>Edit</button>
          <button class="btn btn-sm btn-outline-danger" onclick="deleteRow(${r.id})">Delete</button>
        </div>
      </td>
    </tr>
  `).join('');
}

// search
let _t;document.getElementById('searchInput').addEventListener('input',()=>{clearTimeout(_t);_t=setTimeout(renderTable,250);});

// tabs
document.querySelectorAll('#postTabs .nav-link').forEach(btn=>{
  btn.addEventListener('click',(e)=>{
    document.querySelectorAll('#postTabs .nav-link').forEach(b=>b.classList.remove('active'));
    e.target.classList.add('active');
    currentPostFilter=e.target.dataset.post||'';fetchList();
  });
});

// modal helpers
const entryModal=new bootstrap.Modal('#entryModal');
document.getElementById('entryModal').addEventListener('hidden.bs.modal',resetForm);

function openEdit(r){
  document.getElementById('modalTitle').innerText='Edit Entry';
  document.getElementById('editId').value=r.id;
  document.getElementById('name').value=r.name||'';
  document.getElementById('post').value=r.post||'';
  document.getElementById('city').value=r.city||'';
  document.getElementById('mobile').value=r.mobile||'';
  if(!document.getElementById('aanchalSelectWrapper').classList.contains('d-none'))document.getElementById('aanchal').value=r.aanchal||'';
  else document.getElementById('aanchal_fallback').value=r.aanchal||'';
  document.getElementById('photoReq').classList.add('d-none');
  entryModal.show();
}

function resetForm(){
  document.getElementById('modalTitle').innerText='Add Entry';
  document.getElementById('vpForm').reset();
  document.getElementById('editId').value='';
  document.getElementById('photoReq').classList.remove('d-none');
}

// save
document.getElementById('vpForm').addEventListener('submit',async function(e){
  e.preventDefault();
  const id=document.getElementById('editId').value;
  const form=new FormData(this);
  if(document.getElementById('aanchalSelectWrapper').classList.contains('d-none'))form.set('aanchal',document.getElementById('aanchal_fallback').value.trim());
  else form.set('aanchal',document.getElementById('aanchal').value);
  const photo=document.getElementById('photo').files[0];
  if(photo){if(photo.size>200*1024){toast('error','Image too large');return;}form.set('photo',photo);}
  else if(!id){toast('error','Photo required');return;}
  const url=id?`${API_BASE}/${id}`:API_BASE;if(id)form.append('_method','PUT');
  try{
    const res=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'X-Requested-With':'XMLHttpRequest'},body:form});
    if(!res.ok){const err=await res.json().catch(()=>({message:'Error'}));toast('error','Error',err.message||'Validation failed');return;}
    const data=await res.json();toast('success',data.message||'Saved');entryModal.hide();fetchList();
  }catch(e){toast('error','Network error');}
});

// delete
async function deleteRow(id){
  const ok=await Swal.fire({icon:'warning',title:'Delete?',showCancelButton:true});
  if(!ok.isConfirmed)return;
  const res=await fetch(`${API_BASE}/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'X-Requested-With':'XMLHttpRequest'}});
  const data=await res.json();toast('success',data.message||'Deleted');fetchList();
}

// init
document.addEventListener('DOMContentLoaded',async()=>{await loadAanchalOptions();await fetchList();});
</script>
@endsection
