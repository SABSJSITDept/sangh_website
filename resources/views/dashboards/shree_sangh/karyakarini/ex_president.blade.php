@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">पूर्व अध्यक्ष (Ex Presidents)</h4>

    {{-- 🔸 Form to Add / Edit --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <span id="formTitle">➕ Add Ex President</span>
        </div>
        <div class="card-body">
            <form id="exPresidentForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="president_id">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Place</label>
                        <input type="text" name="place" id="place" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Karaykal</label>
                        <input type="text" name="karaykal" id="karaykal" class="form-control" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Photo (only image, max 2MB)</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                        <img id="previewPhoto" class="mt-2" width="100" style="display:none;">
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-3">Save</button>
                <button type="reset" onclick="resetForm()" class="btn btn-secondary mt-3">Cancel</button>
            </form>
        </div>
    </div>

    {{-- 🔽 List --}}
    <div class="row" id="presidentList"></div>
</div>

<script>
    // Initial fetch
    fetchPresidents();

    // Submit form handler
    document.getElementById('exPresidentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = document.getElementById('exPresidentForm');
        const formData = new FormData(form);
        const id = document.getElementById('president_id').value;
        const url = id ? `/api/ex-president/${id}` : '/api/ex-president';

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            form.reset();
            document.getElementById('president_id').value = '';
            document.getElementById('formTitle').textContent = '➕ Add Ex President';
            document.getElementById('previewPhoto').style.display = 'none';
            fetchPresidents();
        })
        .catch(err => alert('Error: ' + err));
    });

    function fetchPresidents() {
        fetch('/api/ex-president')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('presidentList');
                list.innerHTML = '';
                data.forEach(p => {
                    list.innerHTML += `
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <img src="/storage/${p.photo}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${p.name}</h5>
                                    <p class="card-text">
                                        📍 ${p.place}<br>
                                        🗓️ ${p.karaykal}
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <button onclick="editPresident(${p.id})" class="btn btn-sm btn-primary">✏️ Edit</button>
                                        <button onclick="deletePresident(${p.id})" class="btn btn-sm btn-danger">🗑️ Delete</button>
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

                document.getElementById('president_id').value = p.id;
                document.getElementById('name').value = p.name;
                document.getElementById('place').value = p.place;
                document.getElementById('karaykal').value = p.karaykal;
                document.getElementById('formTitle').textContent = '✏️ Edit Ex President';
                document.getElementById('previewPhoto').src = '/storage/' + p.photo;
                document.getElementById('previewPhoto').style.display = 'block';
            });
    }

    function deletePresident(id) {
        if (!confirm('Are you sure you want to delete this entry?')) return;

        fetch(`/api/ex-president/${id}`, {
            method: 'DELETE',
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
