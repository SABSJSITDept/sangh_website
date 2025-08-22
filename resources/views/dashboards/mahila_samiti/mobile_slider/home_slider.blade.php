@extends('includes.layouts.mahila_Samiti')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h4 class="mb-4 text-center">🏠 Home Slider Management</h4>

    <div class="row">
        <!-- Upload Form -->
        <div class="col-md-4">
            <form id="sliderForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Slider Photo (1280×520, max 300KB)</label>
                    <input type="file" class="form-control" name="photo" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Add Photo</button>
            </form>
        </div>

        <!-- Slider List -->
        <div class="col-md-8">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sliderTable"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest'
};

function fetchSliders() {
    fetch('/api/home_slider')
        .then(res => res.json())
        .then(data => {
            let rows = '';
            data.forEach(slider => {
                rows += `
                    <tr>
                        <td><img src="/${slider.photo}" style="width:200px; height:auto; border:1px solid #ccc;"></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="updatePhoto(${slider.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deletePhoto(${slider.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('sliderTable').innerHTML = rows;
        });
}

document.getElementById('sliderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch('/api/home_slider', {
        method: 'POST',
        headers,
        body: formData
    })
    .then(res => {
        if (res.status === 422) {
            return res.json().then(err => {
                // Custom max 5 entries error message handling
                if (err.message && err.message.includes('Maximum 5 slider photos allowed')) {
                    Swal.fire('Error', err.message, 'error');
                } else {
                    Swal.fire('Validation Error', Object.values(err.errors).join('<br>'), 'error');
                }
                throw new Error('Validation failed'); // stop further then()
            });
        }
        return res.json();
    })
    .then(data => {
        if (data?.message) {
            Swal.fire('Success', data.message, 'success');
            fetchSliders();
            this.reset();
        }
    })
    .catch(err => {
        // Handle any thrown errors silently here
        console.log(err);
    });
});

function deletePhoto(id) {
    Swal.fire({
        title: 'Delete?',
        text: 'Are you sure to delete this photo?',
        icon: 'warning',
        showCancelButton: true
    }).then(res => {
        if (res.isConfirmed) {
            fetch(`/api/home_slider/${id}`, { method: 'DELETE', headers })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Deleted', data.message, 'success');
                    fetchSliders();
                });
        }
    });
}

function updatePhoto(id) {
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function() {
        let file = this.files[0];
        let formData = new FormData();
        formData.append('photo', file);

        fetch(`/api/home_slider/${id}`, { 
            method: 'POST', 
            headers: { ...headers, 'X-HTTP-Method-Override': 'PUT' }, 
            body: formData 
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire('Updated', data.message, 'success');
            fetchSliders();
        });
    };
    input.click();
}

fetchSliders();
</script>
@endsection
