@extends('includes.layouts.yuva_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4">
    <h3 class="mb-3">Yuva Slider Management</h3>

    <!-- Upload Form -->
    <form id="uploadForm" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Image</button>
    </form>

    <!-- Image List -->
    <div id="sliderList" class="row g-3"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", fetchSliders);

function fetchSliders() {
    fetch("/api/yuva-slider")
        .then(res => res.json())
        .then(data => {
            let container = document.getElementById("sliderList");
            container.innerHTML = "";
            data.forEach(item => {
                container.innerHTML += `
                    <div class="col-md-3 text-center">
                        <img src="${item.image}" class="img-fluid rounded mb-2" style="height:150px;object-fit:cover;">
                        <button class="btn btn-danger btn-sm" onclick="deleteSlider(${item.id})">Delete</button>
                    </div>
                `;
            });
        });
}

document.getElementById("uploadForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch("/api/yuva-slider", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        if (response.error) {
            Swal.fire("Error", response.error, "error");
        } else {
            Swal.fire("Success", "Image uploaded successfully", "success");
            fetchSliders();
            this.reset();
        }
    })
    .catch(() => Swal.fire("Error", "Something went wrong", "error"));
});

function deleteSlider(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This image will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/yuva-slider/${id}`, {
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(response => {
                Swal.fire("Deleted!", response.message, "success");
                fetchSliders();
            })
            .catch(() => Swal.fire("Error", "Something went wrong", "error"));
        }
    });
}

</script>
@endsection
