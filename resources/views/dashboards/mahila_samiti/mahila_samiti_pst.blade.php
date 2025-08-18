@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .toast-container {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
    }
    .card-custom {
        border-radius: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        height: 100%;
    }
   .card-custom img {
    width: 100%;
    height: 150px;       /* आप चाहो तो auto भी रख सकते हो */
    object-fit: contain; /* पूरा image दिखेगा (कटा नहीं रहेगा) */
    border-radius: 10px;
    background: #f8f9fa; /* extra background ताकि खाली जगह में gray रहे */
    padding: 5px;
}

</style>

<div class="container py-4">
    <h3 class="mb-4 text-center text-primary">
        <i class="bi bi-people-fill me-2"></i>महिला समिति पदाधिकारी प्रबंधन
    </h3>

    <div class="row g-4">
        <!-- ✅ Left Side Form -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 bg-light h-100">
                   <!-- ✅ Info Message -->
        <div class="alert alert-info small d-flex align-items-start mb-3" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>
                <strong>निर्देश:</strong><br>
                • प्रत्येक पद (Post) के लिए केवल <b>एक ही सदस्य</b> जोड़ा जा सकता है।<br>
                • फोटो का आकार <b>200KB से अधिक नहीं</b> होना चाहिए।
            </div>
        </div>
                <h5 class="mb-3 text-success"><i class="bi bi-person-plus-fill me-2"></i>नया पदाधिकारी जोड़ें</h5>
                <form id="pstForm" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-person-circle me-1 text-primary"></i> नाम</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="नाम दर्ज करें" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-award-fill me-1 text-warning"></i> पद</label>
                        <select id="post" name="post" class="form-select" required>
                            <option value="">-- पद चुनें --</option>
                            <option value="अध्यक्ष">अध्यक्ष</option>
                            <option value="महामंत्री">महामंत्री</option>
                            <option value="कोषाध्यक्ष">कोषाध्यक्ष</option>
                            <option value="सह कोषाध्यक्ष">सह कोषाध्यक्ष</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-image-fill me-1 text-danger"></i> फोटो</label>
                        <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-save-fill me-1"></i> सहेजें
                    </button>
                </form>
            </div>
        </div>

        <!-- ✅ Right Side Cards -->
        <div class="col-lg-8">
            <div class="row row-cols-1 row-cols-md-2 g-3" id="pstList">
                <!-- Cards will load here -->
            </div>
        </div>
    </div>
</div>

<!-- ✅ Toast Container -->
<div class="toast-container position-fixed"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const apiUrl = "/api/mahila-pst";

    function showToast(message, type = "success") {
        const toastId = "toast-" + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0 mb-2" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
        document.querySelector(".toast-container").insertAdjacentHTML("beforeend", toastHtml);
        new bootstrap.Toast(document.getElementById(toastId), { delay: 3000 }).show();
    }

    // ✅ Fetch All
    async function fetchPst() {
        const res = await fetch(apiUrl);
        const data = await res.json();
        let html = "";
        data.forEach(pst => {
            html += `
                <div class="col">
                    <div class="card card-custom text-center p-2">
                        <img src="${pst.photo}" alt="${pst.name}">
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1">${pst.name}</h6>
                            <p class="text-muted small mb-2"><i class="bi bi-award-fill text-warning"></i> ${pst.post}</p>
                            <button class="btn btn-sm btn-primary me-1" onclick="editPst(${pst.id}, '${pst.name}', '${pst.post}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deletePst(${pst.id})">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        });
        document.getElementById("pstList").innerHTML = html;
    }

    // ✅ Store / Update
document.getElementById("pstForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const id = document.getElementById("id").value;

    // ✅ Image size validation (200KB = 200 * 1024 bytes)
    const photoInput = document.getElementById("photo");
    if (photoInput.files.length > 0) {
        const fileSize = photoInput.files[0].size;
        if (fileSize > 200 * 1024) {
            showToast("कृपया 200KB से छोटी फोटो अपलोड करें", "danger");
            return; // Stop form submit
        }
    }

    let url = apiUrl;
    let method = "POST";

    if (id) {
        url = `${apiUrl}/${id}`;
        formData.append("_method", "PUT");
    }

    const res = await fetch(url, {
        method: method,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });

    const result = await res.json();

    if (result.errors) {
        let msg = Object.values(result.errors).join("<br>");
        showToast(msg, "danger");
    } else if (result.success) {
        showToast("सफलतापूर्वक सेव हुआ", "success");
        e.target.reset();
        document.getElementById("id").value = "";
        fetchPst();
    } else {
        showToast("कुछ गलती हुई", "danger");
    }
});


    // ✅ Edit
    function editPst(id, name, post) {
        document.getElementById("id").value = id;
        document.getElementById("name").value = name;
        document.getElementById("post").value = post;
        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    // ✅ Delete
    async function deletePst(id) {
        if (!confirm("क्या आप इस प्रविष्टि को हटाना चाहते हैं?")) return;
        const res = await fetch(`${apiUrl}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const result = await res.json();
        if (result.success) {
            showToast("डिलीट हुआ", "success");
            fetchPst();
        } else {
            showToast("कुछ गलती हुई", "danger");
        }
    }

    fetchPst();
</script>
@endsection
