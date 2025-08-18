@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container py-4">
    <h3 class="mb-4 text-center fw-bold">महिला समिति इवेंट्स</h3>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3"></div>

    <!-- Form -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body">
            <form id="eventForm" enctype="multipart/form-data">
                <input type="hidden" id="event_id" name="event_id">

                <div class="row">
                    <!-- Title -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">शीर्षक</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>

                    <!-- Content -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">सामग्री</label>
                        <textarea class="form-control" name="content" id="content" rows="1" required></textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Photo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">फोटो (200kb तक)</label>
                        <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> सेव करें
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle" id="eventsTable">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>शीर्षक</th>
                        <th>सामग्री</th>
                        <th>फोटो</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody class="text-center"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
// ✅ Toast Function
const showToast = (msg, type = 'success') => {
    let toastId = 'toast-' + Date.now();
    let toastHtml = `
    <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 fade" role="alert" data-bs-delay="3000">
      <div class="d-flex">
        <div class="toast-body">${msg}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>`;
    let container = document.querySelector('.toast-container');
    container.insertAdjacentHTML('beforeend', toastHtml);

    let toastEl = document.getElementById(toastId);
    let bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();

    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
};

// ✅ Fetch Events
const fetchEvents = async () => {
    const res = await fetch('/api/mahila-events');
    const data = await res.json();
    let rows = '';
    data.forEach(e => {
        rows += `
        <tr>
            <td>${e.id}</td>
            <td>${e.title}</td>
            <td>${e.content}</td>
            <td>${e.photo ? `<img src="/storage/${e.photo}" width="80" class="rounded shadow-sm">` : ''}</td>
            <td>
                <button class="btn btn-sm btn-warning me-1" onclick="editEvent(${e.id},'${e.title}','${e.content}')">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteEvent(${e.id})">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </td>
        </tr>`;
    });
    document.querySelector('#eventsTable tbody').innerHTML = rows;
};

// ✅ Submit Form
document.getElementById('eventForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const formData = new FormData(this);
    let id = document.getElementById('event_id').value;
    let url = id ? `/api/mahila-events/${id}?_method=PUT` : `/api/mahila-events`;

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        });
        const data = await res.json();
        if(res.ok){
            showToast(data.success,'success');
            if(!id) { this.reset(); } 
            document.getElementById('event_id').value = '';
            fetchEvents();
        } else {
            let errors = data.errors;
            for (let field in errors) {
                showToast(errors[field][0],'danger');
            }
        }
    } catch (err) {
        showToast('Error Occurred','danger');
    }
});

// ✅ Edit
const editEvent = (id, title, content) => {
    document.getElementById('event_id').value = id;
    document.getElementById('title').value = title;
    document.getElementById('content').value = content;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ✅ Delete
const deleteEvent = async (id) => {
    if(!confirm("Are you sure?")) return;
    const res = await fetch(`/api/mahila-events/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        }
    });
    const data = await res.json();
    if(res.ok){
        showToast(data.success,'success');
        fetchEvents();
    }
};

fetchEvents();
</script>
@endsection
