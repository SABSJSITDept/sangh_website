@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- 🔹 Form Column -->
        <div class="col-md-5 mb-4">
<div class="card shadow-sm border border-success border-2 rounded-4">
    <div class="card-header bg-success text-white fw-bold">
        📋 पोस्ट जोड़ें
    </div>
                <div class="card-body">
                    <form id="pstForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" id="editId">

                        <div class="mb-3">
                            <label class="form-label">नाम:</label>
                            <input type="text" name="name" class="form-control form-control-sm" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">पद:</label>
                            <select name="post" class="form-select form-select-sm" required>
                                <option value="">-- पद चुनें --</option>
                                <option value="अध्यक्ष">अध्यक्ष</option>
                                <option value="महामंत्री">महामंत्री</option>
                                <option value="कोषाध्यक्ष">कोषाध्यक्ष</option>
                                <option value="सह कोषाध्यक्ष">सह कोषाध्यक्ष</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">फोटो (200KB तक, केवल छवि):</label>
                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-sm">💾 सबमिट करें</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 🔸 Cards Column -->
        <div class="col-md-7">
            <div class="row" id="pstCards"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchData();

    document.getElementById('pstForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const id = document.getElementById('editId').value;
        const method = document.getElementById('formMethod').value;

        const url = id ? `/api/pst/${id}` : '/api/pst';
        if (id) formData.append('_method', 'PUT');

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(async res => {
            if (res.status === 403) {
                const data = await res.json();
                alert(data.error);
                return;
            }
            if (res.status === 422) {
                const data = await res.json();
                alert("⚠️ Validation Error:\n" + Object.values(data.errors).join('\n'));
                return;
            }

            return res.json();
        })
        .then(data => {
            if (!data) return;
            fetchData();
            form.reset();
            document.getElementById('editId').value = '';
            document.getElementById('formMethod').value = 'POST';
        });
    });
});

function fetchData() {
    fetch('/api/pst')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('pstCards');
            container.innerHTML = '';
            if (data.length === 0) {
                container.innerHTML = `<p class="text-muted text-center">कोई पोस्ट उपलब्ध नहीं है।</p>`;
                return;
            }

            data.forEach(item => {
                container.innerHTML += `
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <img src="${item.photo ? '/storage/' + item.photo : 'https://via.placeholder.com/80'}" class="rounded mb-2" width="80" height="80" style="object-fit: cover;">
                                <h6 class="fw-bold mb-1">${item.name}</h6>
                                <p class="text-muted small mb-2">${item.post}</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button onclick="editPst(${item.id})" class="btn btn-sm btn-warning">✏️ Edit</button>
                                    <button onclick="deletePst(${item.id})" class="btn btn-sm btn-danger">🗑️ Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        });
}

function editPst(id) {
    fetch(`/api/pst/${id}`)
        .then(res => res.json())
        .then(data => {
            document.querySelector('[name="name"]').value = data.name;
            document.querySelector('[name="post"]').value = data.post;
            document.getElementById('editId').value = data.id;
            document.getElementById('formMethod').value = 'PUT';
        });
}

function deletePst(id) {
    if (confirm('क्या आप वाकई हटाना चाहते हैं?')) {
        fetch(`/api/pst/${id}`, {
            method: 'DELETE'
        }).then(() => fetchData());
    }
}
</script>
@endsection
