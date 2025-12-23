@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Toast container */
    .toast-container {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 1100;
    }

    /* Form card */
    .form-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 30px;
    }

    /* Table hover effect */
    table tbody tr:hover {
        background-color: #f1f7ff;
    }

    /* Action buttons */
    .btn-action {
        min-width: 40px;
    }

    /* Floating label spacing */
    .form-floating>.form-control {
        height: 50px;
    }

    /* Small helper text */
    .form-text-small {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4 text-center display-6 fw-bold">संचोजन मंडल - अंतरराष्ट्रीय सदस्यता</h2>

    {{-- 🔹 INFO ALERT --}}
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>कृपया सभी फ़ील्ड्स भरें। फ़ोटो का आकार 200 KB से अधिक नहीं होना चाहिए।</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    {{-- 🔹 FORM --}}
    <div class="form-card">
        <form id="addForm" enctype="multipart/form-data" class="row g-3">
            <input type="hidden" name="edit_id" id="edit_id">

            <div class="col-md-6 form-floating">
                <input type="text" name="name" id="name" class="form-control" placeholder="नाम" required>
                <label for="name">नाम</label>
            </div>
            <div class="col-md-6 form-floating">
                <input type="text" name="city" id="city" class="form-control" placeholder="शहर" required>
                <label for="city">शहर</label>
            </div>
            <div class="col-md-6 form-floating">
                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="मोबाइल" required>
                <label for="mobile">मोबाइल</label>
            </div>
            <div class="col-md-6">
                <label for="photo" class="form-label">फ़ोटो (200 KB Max)</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                <div class="form-text form-text-small">* नई फ़ोटो चुनें यदि अपडेट करना हो</div>
            </div>
            <div class="col-md-6 form-floating">
                <select name="session" id="session" class="form-select" required>
                    <option value="">सत्र चुनें</option>
                    <option value="2025-27" selected>2025-27</option>
                </select>
                <label for="session">सत्र</label>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">जमा करें</button>
            </div>
        </form>
    </div>

    <hr>

    {{-- 🔹 DATA TABLE --}}
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-primary text-center">
                <tr>
                    <th>📸 फोटो</th>
                    <th>🙍 नाम</th>
                    <th>🏙 शहर</th>
                    <th>📞 मोबाइल</th>
                    <th>📅 सत्र</th>
                    <th>🛠 Actions</th>
                </tr>
            </thead>
            <tbody id="cardContainer"></tbody>
        </table>
    </div>
</div>

{{-- 🔹 TOASTS --}}
<div class="toast-container">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="successMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="errorMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

{{-- Bootstrap JS & Icons --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

{{-- 🔹 JS Logic (existing) --}}

<script>
    const apiUrl = "/api/sanyojan-mandal-antrastriya-sadasyata";
    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    };

    const showToast = (type, message) => {
        const toastEl = document.getElementById(type + 'Toast');
        const toastBody = document.getElementById(type + 'Message');
        toastBody.innerText = message;
        new bootstrap.Toast(toastEl).show();
    };

    function fetchAll() {
        fetch(apiUrl)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.forEach(item => {
                    html += `
                        <tr>
                            <td><img src="/storage/${item.photo}" height="60" class="rounded-circle"></td>
                            <td>${item.name}</td>
                            <td>${item.city}</td>
                            <td>${item.mobile}</td>
                            <td>${item.session || '2025-27'}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="del(${item.id})">हटाएं</button>
                                <button class="btn btn-warning btn-sm ms-2" onclick='edit(${JSON.stringify(item)})'>संपादित करें</button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('cardContainer').innerHTML = html;
            });
    }

    fetchAll();

    document.getElementById('addForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const city = document.getElementById('city').value.trim();
    const mobile = document.getElementById('mobile').value.trim();
    const photoInput = document.getElementById('photo');
    const photoFile = photoInput.files[0];

    // Client-side validation
    if (!name || !city || !mobile) {
        showToast('error', 'कृपया सभी आवश्यक फ़ील्ड्स भरें।');
        return;
    }

    if (photoFile && photoFile.size > 200 * 1024) { // 200 KB
        showToast('error', 'फ़ोटो का आकार 200 KB से अधिक नहीं होना चाहिए।');
        return;
    }

    const form = new FormData(this);
    const id = document.getElementById('edit_id').value;
    const method = 'POST';
    const url = id ? `${apiUrl}/${id}` : apiUrl;

    if (id) {
        form.append('_method', 'PUT');
    }

    fetch(url, {
        method,
        headers,
        body: form
    }).then(async res => {
        const data = await res.json();
        if (res.ok) {
            this.reset();
            document.getElementById('submitBtn').innerText = 'जमा करें';
            document.getElementById('edit_id').value = '';
            fetchAll();
            showToast('success', id ? 'डेटा सफलतापूर्वक अपडेट हुआ!' : 'डेटा सफलतापूर्वक जोड़ा गया!');
        } else {
            let message = data.message || 'कुछ गलत हुआ।';
            if (data.errors) {
                message = Object.values(data.errors).flat().join(', ');
            }
            showToast('error', message);
        }
    }).catch(() => {
        showToast('error', 'नेटवर्क त्रुटि। कृपया पुनः प्रयास करें।');
    });
});


    function del(id) {
        if (confirm("क्या आप वाकई इसे हटाना चाहते हैं?")) {
            fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers
            })
            .then(res => {
                if (res.ok) {
                    fetchAll();
                    showToast('success', 'डेटा सफलतापूर्वक हटाया गया!');
                } else {
                    showToast('error', 'हटाने में विफल।');
                }
            })
            .catch(() => showToast('error', 'नेटवर्क त्रुटि।'));
        }
    }

    function edit(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('city').value = data.city;
        document.getElementById('mobile').value = data.mobile;
        document.getElementById('session').value = data.session || '2025-27';
        document.getElementById('submitBtn').innerText = 'अपडेट करें';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
