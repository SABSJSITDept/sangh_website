@extends('includes.layouts.mahila_Samiti')

@section('title', 'महिला प्रवर्तिया')

@section('content')

<style>
    .toast-container { position: fixed; top: 76px; right: 20px; z-index: 9999; }

    /* ---- Page header ---- */
    .pv-page-header {
        background: linear-gradient(135deg, #c94b4b 0%, #ee0979 55%, #ff6a00 100%);
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        box-shadow: 0 6px 24px rgba(238,9,121,0.22);
    }
    .pv-page-header h3 { color: #fff; font-weight: 700; margin: 0; font-size: 1.3rem; }
    .pv-page-header p  { color: rgba(255,255,255,0.82); font-size: 0.85rem; margin: 4px 0 0; }

    /* ---- Form card ---- */
    .pv-form-card {
        background: #fff;
        border-radius: 16px;
        padding: 26px 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 24px;
    }
    .pv-form-card h5 {
        font-weight: 700; font-size: 1rem; color: #1a1f36;
        margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }
    .pv-form-card h5 i { color: #ee0979; }

    .pv-form-card .form-label {
        font-weight: 600; font-size: 0.85rem; color: #3a3f58;
    }
    .pv-form-card .form-control,
    .pv-form-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e5ee;
        font-size: 0.88rem;
        padding: 9px 14px;
        transition: border-color 0.18s;
    }
    .pv-form-card .form-control:focus {
        border-color: #ee0979;
        box-shadow: 0 0 0 3px rgba(238,9,121,0.12);
    }
    .pv-form-card textarea.form-control { resize: vertical; min-height: 110px; }

    /* Logo preview in form */
    #formLogoPreview {
        width: 80px; height: 80px;
        border-radius: 12px;
        object-fit: contain;
        border: 2px dashed #e2e5ee;
        background: #f8f9fc;
        padding: 4px;
        display: none;
        margin-top: 8px;
    }

    /* ---- Save button ---- */
    .btn-pv-save {
        background: linear-gradient(135deg,#ee0979,#ff6a00);
        color: #fff; border: none;
        border-radius: 10px; padding: 10px 28px;
        font-weight: 700; font-size: 0.88rem;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(238,9,121,0.3);
        transition: all 0.2s;
        width: 100%;
    }
    .btn-pv-save:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(238,9,121,0.4); }

    /* ---- Reset button ---- */
    .btn-pv-reset {
        background: #f3f4f8; color: #5b6178;
        border: none; border-radius: 10px;
        padding: 10px 16px; font-weight: 600; font-size: 0.85rem;
        cursor: pointer; transition: all 0.18s;
    }
    .btn-pv-reset:hover { background: #e8eaf0; }

    /* ---- Cards grid ---- */
    .pv-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 18px;
    }

    /* ---- Single pravartiya card ---- */
    .pv-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        overflow: hidden;
        transition: transform 0.22s, box-shadow 0.22s;
        display: flex; flex-direction: column;
    }
    .pv-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(238,9,121,0.14);
    }

    /* Logo area */
    .pv-card-logo {
        height: 130px;
        background: linear-gradient(135deg, #fdf2f8, #fce7f3);
        display: flex; align-items: center; justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .pv-card-logo img {
        max-height: 110px; max-width: 90%;
        object-fit: contain;
    }
    .pv-card-logo .pv-no-logo {
        font-size: 3rem; color: rgba(238,9,121,0.25);
    }

    /* Body */
    .pv-card-body {
        padding: 16px 18px;
        flex: 1; display: flex; flex-direction: column;
    }
    .pv-card-name {
        font-weight: 700; font-size: 1rem; color: #1a1f36;
        margin-bottom: 6px;
    }
    .pv-card-desc {
        font-size: 0.83rem; color: #6b7280;
        line-height: 1.55;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Footer action */
    .pv-card-actions {
        display: flex; gap: 8px;
        padding: 12px 18px;
        border-top: 1px solid #f0f1f5;
        background: #fafbfc;
    }
    .pv-card-actions button {
        flex: 1; padding: 7px 0;
        border-radius: 8px; border: none;
        font-size: 0.8rem; font-weight: 600;
        cursor: pointer; transition: all 0.18s;
    }
    .btn-pv-edit   { background: rgba(238,9,121,0.1); color: #ee0979; }
    .btn-pv-del    { background: rgba(220,38,38,0.08); color: #dc2626; }
    .btn-pv-edit:hover { background: #ee0979; color: #fff; }
    .btn-pv-del:hover  { background: #dc2626; color: #fff; }

    /* ---- Empty state ---- */
    .pv-empty {
        text-align: center; padding: 50px 20px; color: #9ca3af;
    }
    .pv-empty i { font-size: 3rem; color: #e2e5ee; display: block; margin-bottom: 12px; }

    /* ---- Info notice ---- */
    .pv-notice {
        background: #fdf2f8; border-left: 4px solid #ee0979;
        border-radius: 10px; padding: 12px 16px;
        font-size: 0.82rem; color: #6b2d5e; margin-bottom: 18px;
        display: flex; gap: 10px; align-items: flex-start;
    }
    .pv-notice i { color: #ee0979; font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
</style>

<!-- Page Header -->
<div class="pv-page-header">
    <div>
        <h3><i class="bi bi-journal-richtext me-2"></i>महिला प्रवर्तिया प्रबंधन</h3>
        <p>यहाँ प्रवर्तिया जोड़ें, संपादित करें और हटाएं</p>
    </div>
    <span style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:30px;padding:6px 16px;color:#fff;font-size:0.82rem;font-weight:600;">
        <i class="bi bi-plus-circle-fill me-1"></i> नया जोड़ने के लिए नीचे Form भरें
    </span>
</div>

<div class="row g-4">

    <!-- ===================== FORM ===================== -->
    <div class="col-lg-4">
        <div class="pv-form-card">
            <h5><i class="bi bi-pencil-fill"></i> <span id="formTitle">नया प्रवर्तिया जोड़ें</span></h5>

            <div class="pv-notice">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    • <b>नाम</b> और <b>विवरण</b> अनिवार्य है।<br>
                    • <b>Logo</b> optional है, 300KB से कम रखें।
                </div>
            </div>

            <form id="pvForm" enctype="multipart/form-data">
                <input type="hidden" id="pvId" name="id">

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-person-fill me-1 text-danger"></i> नाम <span class="text-danger">*</span></label>
                    <input type="text" id="pvName" name="name" class="form-control" placeholder="प्रवर्तिया का नाम" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-card-text me-1 text-primary"></i> विवरण (Description) <span class="text-danger">*</span></label>
                    <textarea id="pvDesc" name="description" class="form-control" placeholder="प्रवर्तिया का विवरण यहाँ लिखें..." required></textarea>
                </div>

                <!-- Logo (optional) -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-image-fill me-1 text-success"></i> Logo <span class="text-muted" style="font-weight:400;">(वैकल्पिक)</span></label>
                    <input type="file" id="pvLogo" name="logo" class="form-control" accept="image/*">
                    <div style="font-size:0.75rem;color:#9ca3af;margin-top:4px;">300KB से कम, image file (.jpg/.png/.webp)</div>
                    <img id="formLogoPreview" src="" alt="Logo Preview">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-pv-save" id="pvSaveBtn">
                        <i class="bi bi-save-fill me-1"></i> सहेजें
                    </button>
                    <button type="button" class="btn-pv-reset" onclick="resetForm()">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================== CARDS LIST ===================== -->
    <div class="col-lg-8">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="font-weight:700;font-size:1rem;color:#1a1f36;">
                <i class="bi bi-grid-3x3-gap-fill me-2" style="color:#ee0979;"></i>
                सभी प्रवर्तिया
                <span id="pvCount" style="background:rgba(238,9,121,0.1);color:#ee0979;border-radius:20px;padding:2px 10px;font-size:0.78rem;font-weight:700;margin-left:8px;">0</span>
            </div>
        </div>
        <div class="pv-grid" id="pvGrid">
            <!-- Cards loaded here -->
        </div>
    </div>

</div>

<!-- Toast container -->
<div class="toast-container position-fixed"></div>

<script>
const API = '/api/mahila-pravartiya';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

/* ---- Toast ---- */
function showToast(msg, type = 'success') {
    const id = 't' + Date.now();
    const html = `<div id="${id}" class="toast align-items-center text-white bg-${type} border-0 mb-2" role="alert">
        <div class="d-flex">
            <div class="toast-body">${msg}</div>
            <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>`;
    document.querySelector('.toast-container').insertAdjacentHTML('beforeend', html);
    new bootstrap.Toast(document.getElementById(id), { delay: 3000 }).show();
}

/* ---- Logo preview in form ---- */
document.getElementById('pvLogo').addEventListener('change', function () {
    const file = this.files[0];
    const preview = document.getElementById('formLogoPreview');
    if (file) {
        if (file.size > 300 * 1024) {
            showToast('Logo 300KB से बड़ा है!', 'danger');
            this.value = '';
            preview.style.display = 'none';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

/* ---- Fetch & render all ---- */
async function fetchAll() {
    try {
        const res  = await fetch(API);
        const data = await res.json();
        renderCards(data);
    } catch (e) {
        document.getElementById('pvGrid').innerHTML = '<p class="text-danger">Data load नहीं हुआ।</p>';
    }
}

function renderCards(data) {
    const grid = document.getElementById('pvGrid');
    document.getElementById('pvCount').textContent = data.length;

    if (!data.length) {
        grid.innerHTML = `<div class="pv-empty" style="grid-column:1/-1;">
            <i class="bi bi-journal-x"></i>
            कोई प्रवर्तिया नहीं मिली। ऊपर Form से जोड़ें।
        </div>`;
        return;
    }

    grid.innerHTML = data.map(item => `
        <div class="pv-card">
            <div class="pv-card-logo">
                ${item.logo
                    ? `<img src="${item.logo}" alt="${item.name}">`
                    : `<i class="bi bi-journal-richtext pv-no-logo"></i>`
                }
            </div>
            <div class="pv-card-body">
                <div class="pv-card-name">${item.name}</div>
                <div class="pv-card-desc">${item.description}</div>
            </div>
            <div class="pv-card-actions">
                <button class="btn-pv-edit" onclick="editItem(${item.id}, '${escJs(item.name)}', '${escJs(item.description)}', '${item.logo || ''}')">
                    <i class="bi bi-pencil-fill me-1"></i> Edit
                </button>
                <button class="btn-pv-del" onclick="deleteItem(${item.id})">
                    <i class="bi bi-trash-fill me-1"></i> Delete
                </button>
            </div>
        </div>
    `).join('');
}

function escJs(str) {
    return (str || '').replace(/'/g, "\\'").replace(/\n/g, '\\n');
}

/* ---- Save / Update ---- */
document.getElementById('pvForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const id   = document.getElementById('pvId').value;
    const form = new FormData(this);
    const btn  = document.getElementById('pvSaveBtn');

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> सहेज रहे हैं...';

    const url    = id ? `${API}/${id}` : `${API}/`;
    // For update with file, use POST with _method=PUT override
    if (id) form.append('_method', 'PUT');

    const res    = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        body: form
    });

    const result = await res.json();
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-save-fill me-1"></i> सहेजें';

    if (result.success) {
        showToast(id ? 'अपडेट हो गया!' : 'सफलतापूर्वक जोड़ा गया!', 'success');
        resetForm();
        fetchAll();
    } else if (result.errors) {
        showToast(Object.values(result.errors).join('<br>'), 'danger');
    } else {
        showToast('कुछ गलती हुई।', 'danger');
    }
});

/* ---- Edit ---- */
function editItem(id, name, desc, logo) {
    document.getElementById('pvId').value   = id;
    document.getElementById('pvName').value = name;
    document.getElementById('pvDesc').value = desc.replace(/\\n/g, '\n');
    document.getElementById('formTitle').textContent = 'प्रवर्तिया संपादित करें';

    const preview = document.getElementById('formLogoPreview');
    if (logo) { preview.src = logo; preview.style.display = 'block'; }
    else       { preview.style.display = 'none'; }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ---- Delete ---- */
async function deleteItem(id) {
    if (!confirm('क्या आप इस प्रवर्तिया को हटाना चाहते हैं?')) return;

    const res    = await fetch(`${API}/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF }
    });
    const result = await res.json();

    if (result.success) {
        showToast('डिलीट हो गया!', 'success');
        fetchAll();
    } else {
        showToast('डिलीट नहीं हो सका।', 'danger');
    }
}

/* ---- Reset form ---- */
function resetForm() {
    document.getElementById('pvForm').reset();
    document.getElementById('pvId').value = '';
    document.getElementById('formTitle').textContent = 'नया प्रवर्तिया जोड़ें';
    document.getElementById('formLogoPreview').style.display = 'none';
}

/* ---- Init ---- */
fetchAll();
</script>

@endsection
