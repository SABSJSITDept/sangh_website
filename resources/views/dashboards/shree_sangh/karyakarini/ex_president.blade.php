@extends('includes.layouts.shree_sangh')

@section('content')

<style>
    .card-body {
        font-family: 'Segoe UI', sans-serif;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container my-4">
    <h4 class="mb-4">पूर्व अध्यक्ष (Ex Presidents)</h4>

    <!-- Form Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white">
            <span id="formTitle">➕ Add Ex President</span>
        </div>
        <div class="card-body">
            <form id="exPresidentForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="president_id">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Place</label>
                        <input type="text" name="place" id="place" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">कार्यकाल </label>
                        <input type="text" name="karaykal" id="karaykal" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label">Photo (image, max 200kB)</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                        <img id="previewPhoto" class="mt-2 rounded shadow-sm" width="100" style="display:none;">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">💾 Save</button>
                    <button type="reset" onclick="resetForm()" class="btn btn-secondary">↩️ Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card List -->
    <div class="row" id="presidentList">
        <div class="text-center py-4" id="loadingMsg">🔄 Loading...</div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetchPresidents();

document.getElementById('exPresidentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = document.getElementById('exPresidentForm');
    const formData = new FormData(form);
    const id = document.getElementById('president_id').value;
    const url = id ? `/api/ex-president/${id}` : '/api/ex-president';

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        form.reset();
        resetForm();
        fetchPresidents();
    })
    .catch(err => alert('Error: ' + err));
});

function fetchPresidents() {
    document.getElementById('presidentList').innerHTML = '<div class="text-center py-4" id="loadingMsg">🔄 Loading...</div>';
    fetch('/api/ex-president')
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('presidentList');
            list.innerHTML = '';
            data.forEach(p => {
 list.innerHTML += `
    <div class="col-lg-2 col-md-3 col-sm-4 col-6 text-center mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-2">
                <img src="/storage/${p.photo}" class="rounded-circle mb-2" style="width: 90px; height: 90px; object-fit: cover; border: 2px solid #e1e1e1;">
                <div style="font-size: 0.9rem; font-weight: 600;">श्री ${p.name}</div>
                <div style="font-size: 0.75rem;" class="text-muted">${p.place}</div>
                <div style="font-size: 0.75rem;" class="text-muted">${p.karaykal}</div>
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <button onclick="editPresident(${p.id})" class="btn btn-sm btn-outline-primary px-2 py-1">✏️</button>
                    <button onclick="deletePresident(${p.id})" class="btn btn-sm btn-outline-danger px-2 py-1">🗑️</button>
                </div>
            </div>
        </div>
    </div>
`;


            });
        });
}

function editPresident(id) {
    fetch('/api/ex-president')
        .then(res => res.json())
        .then(data => {
            const p = data.find(item => item.id === id);
            if (!p) return;

            // Populate form
            document.getElementById('president_id').value = p.id;
            document.getElementById('name').value = p.name;
            document.getElementById('place').value = p.place;
            document.getElementById('karaykal').value = p.karaykal;
            document.getElementById('formTitle').textContent = '✏️ Edit Ex President';
            document.getElementById('previewPhoto').src = '/storage/' + p.photo;
            document.getElementById('previewPhoto').style.display = 'block';

            // Scroll to form and focus
            document.getElementById('exPresidentForm').scrollIntoView({ behavior: 'smooth' });
            setTimeout(() => {
                document.getElementById('name').focus();
            }, 500); // Wait a bit to ensure scroll completes
        });
}


function deletePresident(id) {
    if (!confirm('Are you sure you want to delete this entry?')) return;
    fetch(`/api/ex-president/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        fetchPresidents();
    });
}

function resetForm() {
    document.getElementById('exPresidentForm').reset();
    document.getElementById('president_id').value = '';
    document.getElementById('formTitle').textContent = '➕ Add Ex President';
    document.getElementById('previewPhoto').style.display = 'none';
}
</script>
@endsection
