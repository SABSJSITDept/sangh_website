@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <h3 class="mb-4" id="formTitle">📚 साहित्य जोड़ें</h3>

    <form id="sahityaForm" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" required>
                    <option value="">चुनें</option>
                    <option value="नानेशवाणी साहित्य">📖 नानेशवाणी साहित्य</option>
                    <option value="राम उवाच साहित्य">🙏 श्री राम उवाच साहित्य</option>
                    <option value="श्री राम ध्वनि">📚 श्री राम ध्वनि</option>
                    <option value="राम दर्शन">🧠 राम दर्शन</option>
                    <option value="समता कथा माला">🏳️ समता कथा माला</option>
                    <option value="अन्य प्रकाशित साहित्य">📌 अन्य प्रकाशित साहित्य</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required />
            </div>
            <div class="col-md-6">
                <label class="form-label">Cover Photo (max 200KB)</label>
                <input type="file" name="cover_photo" accept="image/*" class="form-control" />
            </div>
            <div class="col-md-6">
                <label class="form-label">PDF File (max 2MB)</label>
                <input type="file" name="pdf" accept="application/pdf" class="form-control" />
            </div>
            <div class="col-md-6">
                <label class="form-label">Preference (1 = सबसे ऊपर)</label>
                <input type="number" name="preference" class="form-control" min="0" value="0" />
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success">💾 Save</button>
            <button type="button" class="btn btn-secondary d-none" id="cancelEditBtn" onclick="cancelEdit()">🚫 Cancel Edit</button>
        </div>
    </form>

<div id="toast-container" class="position-fixed top-0 end-0 p-3 mt-3 me-3" style="z-index: 9999;"></div>

    <hr class="my-5">

    <h4 class="mb-3">📜 Saved Sahitya</h4>
    <div id="sahityaList"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .book-card {
        width: 100%;
        max-width: 160px;
        min-height: 260px;
        font-size: 0.85rem;
    }

    .book-img {
        height: 120px;
        object-fit: cover;
    }
</style>

<script>
let editId = null;

document.getElementById('sahityaForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = new FormData(this);

    const coverPhoto = form.get('cover_photo');
    if (coverPhoto && coverPhoto.size > 200 * 1024) {
        showToast('❌ Cover photo must be under 200KB!');
        return;
    }

    const pdf = form.get('pdf');
    if (pdf && pdf.size > 2 * 1024 * 1024) {
        showToast('❌ PDF must be under 2MB!');
        return;
    }

    const method = editId ? 'POST' : 'POST';
    const url = editId ? `/api/sahitya/${editId}?_method=PUT` : '/api/sahitya';

    const res = await fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: form
    });

    const data = await res.json();
    showToast(data.message || '✔️ Operation successful');
    loadSahitya();
    this.reset();
    resetFormState();
});

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-info shadow';
    toast.innerText = message;
    const container = document.getElementById('toast-container');
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

async function loadSahitya() {
    const res = await fetch('/api/sahitya');
    const data = await res.json();
    const list = document.getElementById('sahityaList');
    list.innerHTML = '';

    Object.keys(data).forEach(category => {
        const items = data[category];
        if (!items.length) return;

        const header = document.createElement('h5');
        header.className = 'mt-4 mb-2 text-primary border-bottom pb-1';
        header.innerText = category;
        list.appendChild(header);

        const row = document.createElement('div');
        row.className = 'row row-cols-2 row-cols-sm-3 row-cols-md-6 g-3';

        items.forEach(item => {
            const col = document.createElement('div');
            col.className = 'col';

            const card = document.createElement('div');
            card.className = 'card book-card shadow-sm';

            const img = document.createElement('img');
            img.src = `/storage/${item.cover_photo}`;
            img.className = 'card-img-top book-img';
            img.alt = item.name;

            const body = document.createElement('div');
            body.className = 'card-body p-2';
            body.innerHTML = `
                <p class="card-title fw-bold mb-1">${item.name}</p>
${item.pdf
    ? `<a href="/storage/${item.pdf}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-1">📄 View PDF</a>`
    : `<button class="btn btn-sm btn-outline-secondary w-100 mb-1" disabled>📄 No PDF</button>`}

                <div class="d-flex justify-content-between">
                    <button class="btn btn-sm btn-warning" onclick="editSahitya(${item.id})">✏️</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteSahitya(${item.id})">🗑️</button>
                </div>
            `;

            card.appendChild(img);
            card.appendChild(body);
            col.appendChild(card);
            row.appendChild(col);
        });

        list.appendChild(row);
    });
}

async function deleteSahitya(id) {
    if (confirm('❗ Delete this entry?')) {
        const res = await fetch(`/api/sahitya/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await res.json();
        showToast(data.message);
        loadSahitya();
    }
}

async function editSahitya(id) {
    const res = await fetch(`/api/sahitya/${id}`);
    const data = await res.json();

    document.querySelector('[name="category"]').value = data.category;
    document.querySelector('[name="name"]').value = data.name;
    document.querySelector('[name="preference"]').value = data.preference;

    editId = data.id;
    document.getElementById('formTitle').innerText = "✏️ साहित्य अपडेट करें";
    document.getElementById('cancelEditBtn').classList.remove('d-none');

    showToast("✏️ Edit Mode: " + data.name);

    // Auto scroll to form
    document.getElementById('sahityaForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
}


loadSahitya();
</script>
@endsection
