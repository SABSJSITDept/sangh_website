@extends('includes.layouts.sahitya')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <h3>📸 फोटो गैलरी</h3>

    <div class="card shadow-sm p-4">
        <form id="photoForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category" required>
                    <option value="">-- Select Category --</option>
                    <option value="sangh">Sangh</option>
                    <option value="yuva">Yuva</option>
                    <option value="mahila">Mahila</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Event Name</label>
                <input type="text" class="form-control" name="event_name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Photos (max 10)</label>
                <input type="file" class="form-control" name="photos[]" multiple accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success">Upload</button>
        </form>
    </div>

    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
</div>
@endsection

@section('scripts')
<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('photoForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('/api/photos', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, 'success');
        form.reset();
    })
    .catch(() => showToast('Error uploading photos.', 'danger'));
});

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
@endsection
