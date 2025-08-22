@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card-compact { max-width: 520px; }
    .news-img, .pst-img { height: 190px; object-fit: cover; }
    .form-text-sm { font-size: 0.825rem; color:#6c757d; }
    .sticky-card { position: sticky; top: 20px; }
</style>

<div class="container mt-4">
    <div class="container mt-4">
    <!-- 🔹 Info Message -->
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            <strong>नियम:</strong> प्रत्येक पद (अध्यक्ष, महामंत्री, कोषाध्यक्ष, सह कोषाध्यक्ष) पर केवल <b>एक ही एंट्री</b> की अनुमति है।<br>
            अपलोड की गई फोटो <b>200KB</b> से अधिक नहीं होनी चाहिए (केवल JPG/PNG)।
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">युवा संघ कार्यकारिणी - पदाधिकारी</h3>
    </div>
    
</div>

   

    <div class="row g-3">
        <!-- Compact Create Form -->
        <div class="col-lg-4">
            <div class="card shadow-sm card-compact sticky-card">
                <div class="card-body">
                    <h5 class="card-title mb-3">नई एंट्री</h5>
                    <form id="pstForm" enctype="multipart/form-data">
                        <div class="mb-2">
                            <label class="form-label">नाम</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="नाम दर्ज करें" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">पद</label>
                            <select name="post" id="postSelect" class="form-select form-select-sm" required>
                                <option value="">Select</option>
                                <option>अध्यक्ष</option>
                                <option>महामंत्री</option>
                                <option>कोषाध्यक्ष</option>
                                <option>सह कोषाध्यक्ष</option>
                            </select>
                            <div class="form-text form-text-sm">पहले से भरे पद अपने-आप disabled हो जाएंगे।</div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">फोटो (200KB तक)</label>
                            <input type="file" id="photoInput" name="photo" accept="image/*" class="form-control form-control-sm" required>
                            <div class="form-text form-text-sm">केवल jpg/jpeg/png इमेज अपलोड करें।</div>
                            <img id="photoPreview" class="mt-2 rounded d-none" style="height: 120px; object-fit: cover;" />
                        </div>
                        <button class="btn btn-primary btn-sm w-100">Save</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List -->
        <div class="col-lg-8">
            <div class="row" id="pstList"></div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="editForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">एंट्री अपडेट करें</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" id="editId">
            <div class="mb-2">
                <label class="form-label">नाम</label>
                <input type="text" name="name" id="editName" class="form-control form-control-sm" required>
            </div>
            <div class="mb-2">
                <label class="form-label">पद</label>
                <select name="post" id="editPost" class="form-select form-select-sm" required>
                    <option value="">Select</option>
                    <option>अध्यक्ष</option>
                    <option>महामंत्री</option>
                    <option>कोषाध्यक्ष</option>
                    <option>सह कोषाध्यक्ष</option>
                </select>
                <div class="form-text form-text-sm">यहां आवश्यक हो तो पद बदल सकते हैं।</div>
            </div>
            <div class="mb-2">
                <label class="form-label">फोटो (200KB तक) <span class="text-muted">(ऐच्छिक)</span></label>
                <input type="file" id="editPhoto" name="photo" accept="image/*" class="form-control form-control-sm">
                <div class="d-flex gap-2 align-items-center mt-2">
                    <img id="editPreview" class="rounded" style="height: 90px; object-fit: cover;">
                    <small class="text-muted">नई फोटो चुनेंगे तो पुरानी replace होगी।</small>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" type="button">Cancel</button>
          <button class="btn btn-primary btn-sm" type="submit">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// ---------- Helpers ----------
const toasty = (title, icon='success') => {
    Swal.fire({ toast:true, position:'top-end', showConfirmButton:false, timer:2200, icon, title });
};
const csrfHeaders = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest'
};
const POST_OPTIONS = ['अध्यक्ष','महामंत्री','कोषाध्यक्ष','सह कोषाध्यक्ष'];
let usedPosts = new Set();

// ---------- Client-side validations ----------
const MAX_BYTES = 200 * 1024; // 200KB
function validateImageFile(input) {
    const file = input.files?.[0];
    if(!file) return true;
    const okType = ['image/jpeg','image/jpg','image/png'].includes(file.type);
    if(!okType) { toasty('केवल JPG/PNG इमेज अपलोड करें','error'); input.value=''; return false; }
    if(file.size > MAX_BYTES) { toasty('फोटो 200KB से कम होनी चाहिए','error'); input.value=''; return false; }
    return true;
}
document.getElementById('photoInput').addEventListener('change', function(){
    if(!validateImageFile(this)) return;
    const img = document.getElementById('photoPreview');
    img.src = URL.createObjectURL(this.files[0]);
    img.classList.remove('d-none');
});
document.getElementById('editPhoto').addEventListener('change', function(){
    if(!validateImageFile(this)) return;
    const img = document.getElementById('editPreview');
    img.src = URL.createObjectURL(this.files[0]);
});

// ---------- Create ----------
document.getElementById('pstForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    const selectedPost = formData.get('post');
    if(usedPosts.has(selectedPost)){
        toasty('इस पद की एंट्री पहले से मौजूद है','error');
        return;
    }
    const photoInput = document.getElementById('photoInput');
    if(!validateImageFile(photoInput)) return;

    fetch("/api/yuva-pst", { method: "POST", body: formData, headers: csrfHeaders })
    .then(res => res.json())
    .then(response => {
        if(response.errors){
            const firstErr = Object.values(response.errors)[0]?.[0] || 'Validation error';
            toasty(firstErr, 'error');
        } else {
            toasty(response.message || 'Saved');
            fetchPst();
            e.target.reset();
            document.getElementById('photoPreview').classList.add('d-none');
        }
    }).catch(()=>toasty('Server error','error'));
});

// ---------- Fetch & UI ----------
function disableTakenPosts() {
    const select = document.getElementById('postSelect');
    [...select.options].forEach(opt => {
        if(!opt.value) return;
        opt.disabled = usedPosts.has(opt.value);
    });
}
function badgeFor(post){
    const map = {
        'अध्यक्ष':'danger',
        'महामंत्री':'primary',
        'कोषाध्यक्ष':'success',
        'सह कोषाध्यक्ष':'warning'
    };
    return `<span class="badge bg-${map[post] || 'secondary'}">${post}</span>`;
}

function fetchPst(){
    fetch("/api/yuva-pst")
    .then(res => res.json())
    .then(data => {
        usedPosts = new Set(data.map(x => x.post));
        disableTakenPosts();

        let html = "";
        if(!data.length){
            html = `<div class="col-12"><div class="alert alert-light border">कोई एंट्री नहीं मिली।</div></div>`;
        } else {
            data.forEach(item => {
                html += `
                <div class="col-md-6 col-xl-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <img src="${item.photo}" class="card-img-top pst-img" alt="${item.name}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">${item.name}</h6>
                                    ${badgeFor(item.post)}
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick='openEdit(${JSON.stringify(item)})'>Edit</button>
                                    <button class="btn btn-outline-danger" onclick="deletePst(${item.id})">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
        }
        document.getElementById('pstList').innerHTML = html;
    });
}

// ---------- Delete ----------
function deletePst(id){
    Swal.fire({
        title: 'पक्का डिलीट करें?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'हाँ, डिलीट',
        cancelButtonText: 'रद्द'
    }).then(result => {
        if(!result.isConfirmed) return;
        fetch(`/api/yuva-pst/${id}`, { method: "DELETE", headers: csrfHeaders })
        .then(res => res.json())
        .then(response => {
            toasty(response.message || 'Deleted');
            fetchPst();
        }).catch(()=>toasty('Server error','error'));
    });
}

// ---------- Edit (Modal) ----------
const editModalEl = document.getElementById('editModal');
const editModal = new bootstrap.Modal(editModalEl);

function openEdit(item){
    document.getElementById('editId').value = item.id;
    document.getElementById('editName').value = item.name;
    document.getElementById('editPost').value = item.post;
    document.getElementById('editPreview').src = item.photo;
    editModal.show();

    // Edit पोस्ट ड्रॉपडाउन: अन्य भरे हुए पद disable, लेकिन current post enable
    const editSelect = document.getElementById('editPost');
    [...editSelect.options].forEach(opt => {
        if(!opt.value) return;
        if(opt.value === item.post) { opt.disabled = false; return; }
        opt.disabled = usedPosts.has(opt.value);
    });
}

document.getElementById('editForm').addEventListener('submit', function(e){
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const formData = new FormData(this);
    const newPost = formData.get('post');
    const currentPost = [...usedPosts].find(p => p === newPost);

    // यदि नया post किसी और के पास है और ये current का नहीं है, तो रोकें
    // (Backend भी unique check करता है, यह UX guard है)
    if(usedPosts.has(newPost)) {
        // allow if post unchanged for same record (we can't know owner easily, rely on backend too)
        // UX: proceed, backend will reject if conflict
    }

    fetch(`/api/yuva-pst/${id}`, { method: "POST", body: formData, headers: { ...csrfHeaders, 'Accept':'application/json' } })
    .then(res => res.json())
    .then(response => {
        if(response.errors){
            const firstErr = Object.values(response.errors)[0]?.[0] || 'Validation error';
            toasty(firstErr, 'error');
        } else {
            toasty(response.message || 'Updated');
            editModal.hide();
            document.getElementById('editPhoto').value = '';
            fetchPst();
        }
    }).catch(()=>toasty('Server error','error'));
});

// Laravel expects PUT/PATCH for update in resource, so spoof it:
document.getElementById('editForm').addEventListener('formdata', (e) => {
    e.formData.append('_method','PUT');
});

// Init
fetchPst();
</script>
@endsection
