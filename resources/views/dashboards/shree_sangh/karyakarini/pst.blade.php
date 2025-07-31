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
                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm" id="photoInput">
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

{{-- 🔻 TOAST ALERT --}}
<div class="position-fixed top-0 end-0 p-3 mt-5" style="z-index: 9999;">
    <div id="toastBox" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMsg">Toast message</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchData();

    // ✅ PHOTO size check
    document.getElementById("photoInput").addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 200 * 1024) {
            showToast("⚠️ फ़ोटो का SIZE 200KB से अधिक है!", "danger");
            this.value = "";
        }
    });
document.getElementById('pstForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const id = document.getElementById('editId').value;
    const method = document.getElementById('formMethod').value;
    const url = id ? `/api/pst/${id}` : '/api/pst';
    if (id) formData.append('_method', 'PUT');

    try {
        const res = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const text = await res.text();
        let data;

        try {
            data = JSON.parse(text);
        } catch {
            showToast("⚠️ Unexpected server response", "danger");
            return;
        }

        if (!res.ok) {
            // 🔹 Custom business rules (403 errors)
            if (data.error) {
                showToast(data.error, "danger");
                return;
            }

            // 🔹 Laravel validation errors (422)
            if (data.errors) {
                const errors = Object.values(data.errors).flat().join(" | ");
                showToast("⚠️ " + errors, "danger");
                return;
            }

            // 🔹 Unknown error
            showToast("❌ कोई त्रुटि हुई", "danger");
            return;
        }

        // ✅ Success
        showToast("✅ सफलतापूर्वक सहेजा गया!", "success");
        form.reset();
        document.getElementById('editId').value = '';
        document.getElementById('formMethod').value = 'POST';
        fetchData();

    } catch (err) {
        console.error(err);
        showToast("❌ सर्वर से संपर्क नहीं हो सका", "danger");
    }
});
});

// ✅ Toast Alert Function
function showToast(message, type = "primary") {
    const toastEl = document.getElementById("toastBox");
    const toastMsg = document.getElementById("toastMsg");
    toastMsg.textContent = message;
    toastEl.className = `toast align-items-center text-bg-${type} border-0`;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

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
        })
        .then(res => {
            if (res.ok) {
                showToast("🗑️ हटाया गया!", "success");
                fetchData();
            } else {
                showToast("❌ डिलीट में समस्या आई", "danger");
            }
        });
    }
}
</script>
@endsection
