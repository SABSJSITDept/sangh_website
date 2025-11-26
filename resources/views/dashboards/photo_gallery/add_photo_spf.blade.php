@extends('includes.layouts.spf')

@section('content')
<div class="container mt-5">
    <h4 class="mb-4 text-center fw-bold">📸 संघ फोटो गैलरी</h4>

    <!-- ℹ️ Info Message -->
    <div class="alert alert-info text-center fw-bold rounded-4 shadow-sm">
        ⚠️ कृपया ध्यान दें: प्रत्येक फोटो का साइज़ <strong>200 KB</strong> से अधिक नहीं होना चाहिए 
        और आप अधिकतम <strong>10 फोटो</strong> ही अपलोड कर सकते हैं।
    </div>

    <div class="row">
        <!-- Upload form -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-4 p-3">
                <h5 class="mb-3 text-primary fw-bold">Upload Photos</h5>
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-control" name="category" required>
                            <!-- <option value="">Select Category</option> -->
                            <!-- <option value="sangh">Sangh</option>
                            <option value="yuva">Yuva</option> -->
                            <option value="spf">Spf</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Name</label>
                        <input type="text" class="form-control" name="event_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photos (Max 10)</label>
                        <input type="file" class="form-control" id="photosInput" name="photos[]" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Upload</button>
                </form>
            </div>
        </div>

        <!-- Category Cards -->
        <div class="col-md-8">
            <div class="row">
                <!-- Sangh Card -->
                <!-- <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 gallery-card h-100" onclick="window.location='/sangh_photo_gallery'">
                        <img src="{{ asset('images/logo.jpeg') }}" class="card-img-top rounded-top-4" alt="Sangh Gallery">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-primary">🚩 संघ</h5>
                            <p class="card-text text-muted">संघ के अद्भुत पलों की झलकियां</p>
                            <button class="btn btn-outline-primary px-4 rounded-pill">View Gallery</button>
                        </div>
                    </div>
                </div> -->

                <!-- Yuva Card -->
                <!-- <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 gallery-card h-100" onclick="window.location='#'">
                        <img src="{{ asset('images/yuva.png') }}" class="card-img-top rounded-top-4" alt="Yuva Gallery">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-success">💪 युवा</h5>
                            <p class="card-text text-muted">युवा संघ के अद्भुत पलों की झलकियां</p>
                            <button class="btn btn-outline-success px-4 rounded-pill">View Gallery</button>
                        </div>
                    </div>
                </div> -->

                <!-- Mahila Card -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 gallery-card h-100" onclick="window.location='/spf_photo_gallery'">
                        <img src="{{ asset('images/mslogo.png') }}" class="card-img-top rounded-top-4" alt="Mahila Gallery">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-danger">🌸 महिला</h5>
                            <p class="card-text text-muted">महिला समिति के अद्भुत पलों की झलकियां</p>
                            <button class="btn btn-outline-danger px-4 rounded-pill">View Gallery</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div> 
</div>

<style>
.gallery-card {
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.gallery-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.card-img-top {
    height: 220px;
    object-fit: cover;
}
</style>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e){
    e.preventDefault();

    let photosInput = document.getElementById('photosInput');
    let files = photosInput.files;

    // Max 10 photos check
    if(files.length > 10){
        showToast('आप अधिकतम 10 फोटो ही अपलोड कर सकते हैं!', 'error');
        return;
    }

    // Size check (200 KB)
    for(let file of files){
        if(file.size > 200 * 1024){
            showToast(`"${file.name}" 200 KB से अधिक है!`, 'error');
            return;
        }
    }

    let formData = new FormData(this);

    fetch('/api/photo-gallery/store', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.error){
            showToast(data.error, 'error');
        } else {
            showToast(data.message, 'success');
            this.reset();
        }
    });
});

function showToast(message, icon){
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}
</script>
@endsection
