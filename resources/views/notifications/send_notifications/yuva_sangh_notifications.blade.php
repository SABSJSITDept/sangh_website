@extends('includes.layouts.super_admin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .preview-image { max-height: 150px; border-radius: 8px; margin-top: 10px; }
    </style>

<div class="container my-5">
    <h2 class="mb-4 text-center">📢 Send Notification</h2>

    <div class="card p-4">
        <form id="notificationForm" enctype="multipart/form-data">
            @csrf
            <!-- Group -->
            <div class="mb-3">
                <label class="form-label">Select Group</label>
                <select name="group" class="form-select" required>
                    <option value="Yuva Sangh">Yuva Sangh</option>
                </select>
            </div>

            <!-- Title -->
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter notification title" required>
            </div>

            <!-- Body (Plain Text) -->
            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="body" class="form-control" rows="5" placeholder="Enter notification message" required></textarea>
            </div>

            <!-- Image -->
            <div class="mb-3">
                <label class="form-label">Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <img id="previewImg" class="preview-image d-none"/>
            </div>

            <button type="submit" class="btn btn-primary w-100">🚀 Send Notification</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("notificationForm");
    const previewImg = document.getElementById("previewImg");

    // Preview image
    form.image.addEventListener("change", e => {
        const file = e.target.files[0];
        if (file) {
            previewImg.src = URL.createObjectURL(file);
            previewImg.classList.remove("d-none");
        } else {
            previewImg.classList.add("d-none");
        }
    });

    // Submit with confirmation + preview
    form.addEventListener("submit", async e => {
        e.preventDefault();

        const formData = new FormData(form);

        const group = formData.get("group");
        const title = formData.get("title");
        const body = formData.get("body");

        // Preview
        let htmlPreview = `
            <p><strong>Group:</strong> ${group}</p>
            <p><strong>Title:</strong> ${title}</p>
            <p><strong>Message:</strong></p>
            <div style="border:1px solid #ccc;padding:10px;border-radius:6px;">
                ${body}
            </div>`;
        if (previewImg.src && !previewImg.classList.contains("d-none")) {
            htmlPreview += `<img src="${previewImg.src}" style="max-width:100%;border-radius:8px;"/>`;
        }

        const result = await Swal.fire({
            title: 'Preview Notification',
            html: htmlPreview,
            width: 600,
            showCancelButton: true,
            confirmButtonText: 'Looks Good',
        });

        if (!result.isConfirmed) return;

        // Final confirmation
        const confirmSend = await Swal.fire({
            title: 'Are you sure?',
            text: "This will send notification to all users!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Send it!',
        });

        if (!confirmSend.isConfirmed) return;

        // Send via API
        fetch("/send-notification", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire("✅ Sent!", "Notification Sent Successfully!", "success");
            form.reset();
            previewImg.classList.add("d-none");
        })
        .catch(err => Swal.fire("❌ Error", "Something went wrong!", "error"));
    });
});
</script>
@endsection
