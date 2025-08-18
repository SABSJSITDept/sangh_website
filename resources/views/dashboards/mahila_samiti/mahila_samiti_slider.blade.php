@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ✅ Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.toast-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
}
</style>

<div class="container py-4">

    <!-- 🔹 Top Info Message -->
    <div class="alert alert-info text-center fw-bold">
        ⚠️ आप कुल 5 Photos ही Upload कर सकते हैं और हर Photo का Size अधिकतम 200KB होना चाहिए।<br>
        ⚠️ कृपया landscape photo  Upload करें ताकि सभी Photos सही से दिखें।
    </div>

    <h3 class="mb-4">महिला समिति स्लाइडर</h3>

    <!-- Upload Form (Initially hidden if 5 photos exist) -->
    <form id="uploadForm" enctype="multipart/form-data" class="mb-4" style="display:none;">
        <div class="mb-3">
            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required>
        </div>
        <button type="submit" class="btn btn-success">Upload</button>
    </form>

    <!-- Info message when max limit reached -->
    <div id="limitMsg" class="alert alert-warning" style="display:none;">
        Maximum 5 photos are allowed in the slider. Please delete an existing photo to upload a new one.
    </div>

    <hr>

    <!-- Display Slider Photos -->
    <div class="row" id="sliderList"></div>
</div>

<!-- Toast Alerts -->
<div class="toast-container position-fixed"></div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
const toastContainer = document.querySelector('.toast-container');

function showToast(message, type='success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show mb-2`;
    toast.role = 'alert';
    toast.innerHTML = `<div class="d-flex p-2">${message}
        <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
    </div>`;
    toastContainer.appendChild(toast);
    setTimeout(()=>toast.remove(), 3000);
}

async function fetchSlider() {
    let res = await axios.get('/api/mahila-slider');
    let data = res.data;
    let html = '';

    // ✅ Upload form visibility based on count
    if(data.length >= 5){
        document.getElementById('uploadForm').style.display = "none";
        document.getElementById('limitMsg').style.display = "block";
    } else {
        document.getElementById('uploadForm').style.display = "block";
        document.getElementById('limitMsg').style.display = "none";
    }

    data.forEach(item => {
        html += `
        <div class="col-md-3 mb-3">
            <div class="card shadow">
                <img src="${item.photo}" class="card-img-top" style="height:180px;object-fit:cover;">
                <div class="card-body text-center">
                    <button class="btn btn-danger btn-sm" onclick="deletePhoto(${item.id})">Delete</button>
                </div>
            </div>
        </div>`;
    });
    document.getElementById('sliderList').innerHTML = html;
}

document.getElementById('uploadForm').addEventListener('submit', async(e)=>{
    e.preventDefault();
    let formData = new FormData(e.target);

    let files = e.target.querySelector('input[name="photos[]"]').files;
    if (files.length > 5) {
        showToast("You can upload maximum 5 photos at once!", 'danger');
        return;
    }

    try {
        let res = await axios.post('/api/mahila-slider', formData, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'multipart/form-data'
            }
        });

        if(res.data.warning){
            showToast(res.data.warning, 'danger'); // extra ignored
        }

        showToast(res.data.success, 'success');
        fetchSlider();
        e.target.reset();
    } catch (err) {
        showToast(err.response?.data?.error || 'Something went wrong', 'danger');
    }
});

async function deletePhoto(id) {
    if(!confirm("Are you sure?")) return;
    try {
        let res = await axios.delete(`/api/mahila-slider/${id}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        showToast(res.data.success, 'success');
        fetchSlider();
    } catch (err) {
        showToast('Delete failed', 'danger');
    }
}

fetchSlider();
</script>
@endsection
