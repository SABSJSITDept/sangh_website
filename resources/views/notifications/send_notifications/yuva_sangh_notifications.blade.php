@extends('includes.layouts.yuva_sangh')

@section('title', 'Send Notification - Yuva Sangh')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Broadcast Notification</h2>
                <p class="text-muted small mb-0">Compose and send push notifications to all Yuva Sangh community members.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-megaphone text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Form Card -->
            <div class="card border-0 shadow-sm" style="border-radius: 1.5rem;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold outfit-font mb-0">Notification Composer</h5>
                </div>
                <div class="card-body p-4">
                    <form id="notificationForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Group (Hidden/Fixed for this dashboard) -->
                        <input type="hidden" name="group" value="Yuva Sangh">

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control rounded-3 py-2 border-light bg-light shadow-none" placeholder="Enter an eye-catching headline" required>
                        </div>

                        <!-- Body -->
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Message Content <span class="text-danger">*</span></label>
                            <textarea name="body" class="form-control rounded-3 py-2 border-light bg-light shadow-none" rows="6" placeholder="Type your notification message here..." required></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-600 small text-uppercase text-muted">Attachment (Optional)</label>
                            <div class="upload-drop-zone border rounded-4 p-4 text-center cursor-pointer transition-all bg-light" id="dropZone" onclick="document.getElementById('imageInput').click()">
                                <i class="bi bi-image display-4 text-muted mb-3 d-block"></i>
                                <p class="small text-muted mb-0" id="fileName">Drag & drop or click to select image</p>
                                <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">
                            </div>
                            <div id="previewContainer" class="mt-3 d-none text-center">
                                <img id="previewImg" class="img-fluid rounded-4 shadow border" style="max-height: 250px;">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow text-uppercase letter-spacing-1">
                            <i class="bi bi-send-fill me-2"></i> Prepare Broadcast
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("notificationForm");
    const imageInput = document.getElementById("imageInput");
    const previewImg = document.getElementById("previewImg");
    const previewContainer = document.getElementById("previewContainer");
    const fileNameText = document.getElementById("fileName");
    const dropZone = document.getElementById("dropZone");

    // Preview image
    imageInput.addEventListener("change", e => {
        const file = e.target.files[0];
        if (file) {
            fileNameText.textContent = file.name;
            previewImg.src = URL.createObjectURL(file);
            previewContainer.classList.remove("d-none");
            dropZone.classList.add('bg-white');
            dropZone.classList.remove('bg-light');
        } else {
            previewContainer.classList.add("d-none");
            fileNameText.textContent = "Drag & drop or click to select image";
            dropZone.classList.remove('bg-white');
            dropZone.classList.add('bg-light');
        }
    });

    // Submit with 2-step confirmation
    form.addEventListener("submit", async e => {
        e.preventDefault();
        const formData = new FormData(form);
        const title = formData.get("title");
        const body = formData.get("body");

        // Step 1: Preview Confirmation
        let htmlPreview = `
            <div class="text-start p-2" style="font-family: 'Inter', sans-serif;">
                <div class="mb-3">
                    <span class="badge bg-primary rounded-pill px-3 py-2 mb-2">Preview</span>
                    <h4 class="fw-bold text-dark mb-1">${title}</h4>
                    <p class="text-muted small">Group: Yuva Sangh</p>
                </div>
                <div class="bg-light p-3 rounded-4 mb-3 border">
                    <p class="mb-0 text-dark" style="white-space: pre-wrap;">${body}</p>
                </div>
                ${previewImg.src && !previewContainer.classList.contains("d-none") 
                    ? `<div class="text-center"><img src="${previewImg.src}" class="img-fluid rounded-4 shadow-sm border" style="max-height:200px;"/></div>` 
                    : ''}
            </div>`;

        const result = await Swal.fire({
            title: '<span class="outfit-font fw-bold">Review Notification</span>',
            html: htmlPreview,
            width: 550,
            showCancelButton: true,
            confirmButtonText: 'Confirm Preview',
            cancelButtonText: 'Edit More',
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#64748b',
            borderRadius: '1.5rem',
            reverseButtons: true
        });

        if (!result.isConfirmed) return;

        // Step 2: Final Destructive Confirmation
        const confirmSend = await Swal.fire({
            title: 'Ready to send?',
            text: "This notification will be delivered instantly to all registered users.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Broadcast Now',
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            borderRadius: '1rem',
            reverseButtons: true
        });

        if (!confirmSend.isConfirmed) return;

        // Show Loading
        Swal.fire({
            title: 'Sending Notification...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // Send via API
        fetch("/send-notification", {
            method: "POST",
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Broadcasted Successfully',
                text: 'All members have been notified.',
                timer: 2000,
                showConfirmButton: false,
                borderRadius: '1rem'
            });
            form.reset();
            previewContainer.classList.add("d-none");
            fileNameText.textContent = "Drag & drop or click to select image";
            dropZone.classList.remove('bg-white');
            dropZone.classList.add('bg-light');
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Broadcast Failed',
                text: 'Please check your connection and try again.',
                borderRadius: '1rem'
            });
        });
    });
});
</script>

<style>
    .upload-drop-zone { border: 2px dashed #e2e8f0; }
    .upload-drop-zone:hover { border-color: #6366f1; background-color: #f8fafc !important; }
    .fw-600 { font-weight: 600; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.2s ease; }
</style>
@endsection
