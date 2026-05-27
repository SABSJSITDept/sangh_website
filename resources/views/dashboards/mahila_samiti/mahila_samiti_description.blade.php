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
        box-shadow: 0 2px 10px rgba(0,0,0,0.12);
    }
    .desc-preview {
        background: #fff;
        border-left: 4px solid #ee0979;
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 1rem;
        color: #333;
        white-space: pre-wrap;
        word-break: break-word;
        min-height: 80px;
        line-height: 1.7;
    }
    .char-count {
        font-size: 0.78rem;
        color: #999;
        text-align: right;
        margin-top: 4px;
    }
    #descriptionInput {
        resize: vertical;
        min-height: 160px;
        font-size: 1rem;
        line-height: 1.7;
    }
</style>

<div class="container py-4">
    <h3 class="mb-4 text-center text-primary">
        <i class="bi bi-card-text me-2"></i>महिला समिति — विवरण (Description)
    </h3>

    <div class="row g-4 justify-content-center">

        <!-- ✅ Form Card -->
        <div class="col-lg-7">
            <div class="card card-custom p-4 bg-light">
                <h5 class="mb-3 text-success"><i class="bi bi-pencil-fill me-2"></i>विवरण दर्ज करें</h5>

                <div class="alert alert-info small d-flex align-items-start mb-3" role="alert">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong>निर्देश:</strong><br>
                        • यहाँ महिला समिति का <b>परिचय / विवरण</b> लिखें।<br>
                        • केवल <b>एक ही</b> विवरण सहेजा जाएगा। पुराना विवरण बदल दिया जाएगा।
                    </div>
                </div>

                <form id="descForm">
                    <div class="mb-2">
                        <label class="form-label fw-bold">
                            <i class="bi bi-card-text me-1 text-primary"></i> विवरण (Description)
                        </label>
                        <textarea
                            id="descriptionInput"
                            name="description"
                            class="form-control"
                            placeholder="महिला समिति का परिचय / विवरण यहाँ लिखें..."
                            maxlength="5000"
                        ></textarea>
                        <div class="char-count"><span id="charCount">0</span> / 5000 अक्षर</div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-2" id="saveBtn">
                        <i class="bi bi-save-fill me-1"></i> सहेजें
                    </button>
                </form>
            </div>
        </div>

        <!-- ✅ Preview Card -->
        <div class="col-lg-7">
            <div class="card card-custom p-4">
                <h5 class="mb-3 text-secondary"><i class="bi bi-eye-fill me-2"></i>वर्तमान विवरण (Preview)</h5>
                <div class="desc-preview" id="descPreview">
                    <span class="text-muted fst-italic">कोई विवरण अभी तक सहेजा नहीं गया है।</span>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ✅ Toast Container -->
<div class="toast-container position-fixed"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const apiUrl = "/api/mahila-description";

    // ✅ Toast notification
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

    // ✅ Character counter
    const textarea = document.getElementById("descriptionInput");
    const charCount = document.getElementById("charCount");
    textarea.addEventListener("input", () => {
        charCount.textContent = textarea.value.length;
    });

    // ✅ Fetch & show current description
    async function fetchDescription() {
        try {
            const res = await fetch(apiUrl);
            const data = await res.json();
            const preview = document.getElementById("descPreview");

            if (data && data.description) {
                textarea.value = data.description;
                charCount.textContent = data.description.length;
                preview.textContent = data.description;
            } else {
                preview.innerHTML = '<span class="text-muted fst-italic">कोई विवरण अभी तक सहेजा नहीं गया है।</span>';
            }
        } catch (err) {
            console.error("Error fetching description:", err);
        }
    }

    // ✅ Save description
    document.getElementById("descForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const description = textarea.value.trim();
        if (!description) {
            showToast("कृपया विवरण दर्ज करें।", "danger");
            return;
        }

        const saveBtn = document.getElementById("saveBtn");
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> सहेज रहे हैं...';

        const res = await fetch(apiUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ description })
        });

        const result = await res.json();

        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="bi bi-save-fill me-1"></i> सहेजें';

        if (result.success) {
            showToast("विवरण सफलतापूर्वक सहेजा गया!", "success");
            document.getElementById("descPreview").textContent = description;
        } else if (result.errors) {
            const msg = Object.values(result.errors).join("<br>");
            showToast(msg, "danger");
        } else {
            showToast("कुछ गलती हुई, दोबारा कोशिश करें।", "danger");
        }
    });

    // Load on page start
    fetchDescription();
</script>
@endsection
