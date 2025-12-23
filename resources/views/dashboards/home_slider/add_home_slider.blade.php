@extends('includes.layouts.shree_sangh')

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
                        <label class="form-label">Slider Photo (1280×520, max 200KB)</label>
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
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Failed to fetch sliders');
                    }
                    return res.json();
                })
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
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load slider photos. Please refresh the page.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
        }

        document.getElementById('sliderForm').addEventListener('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            // Validate file size (max 200KB)
            let fileInput = this.querySelector('input[type="file"]');
            if (fileInput.files.length > 0) {
                let file = fileInput.files[0];
                if (file.size > 200 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Photo size must be less than 200KB. Current size: ' + (file.size / 1024).toFixed(2) + 'KB',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }
            }

            // Show loading
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/api/home_slider', {
                method: 'POST',
                headers,
                body: formData
            })
                .then(res => {
                    if (res.status === 422) {
                        return res.json().then(err => {
                            Swal.close();
                            // Custom max 5 entries error message handling
                            if (err.message && err.message.includes('Maximum 5 slider photos allowed')) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Limit Reached',
                                    text: err.message,
                                    confirmButtonColor: '#d33'
                                });
                            } else if (err.errors) {
                                let errorMessages = Object.values(err.errors).flat().join('\n');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    html: errorMessages.replace(/\n/g, '<br>'),
                                    confirmButtonColor: '#d33'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: err.message || 'Validation failed',
                                    confirmButtonColor: '#d33'
                                });
                            }
                            throw new Error('Validation failed');
                        });
                    }
                    if (!res.ok) {
                        throw new Error('Upload failed');
                    }
                    return res.json();
                })
                .then(data => {
                    Swal.close();
                    if (data?.message) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        fetchSliders();
                        this.reset();
                    }
                })
                .catch(err => {
                    Swal.close();
                    if (err.message !== 'Validation failed') {
                        console.error('Upload error:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: 'Failed to upload photo. Please check your internet connection and try again.',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
        });

        function deletePhoto(id) {
            Swal.fire({
                title: 'Delete Photo?',
                text: 'Are you sure you want to delete this photo? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(res => {
                if (res.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/api/home_slider/${id}`, { method: 'DELETE', headers })
                        .then(res => {
                            if (!res.ok) {
                                throw new Error('Delete failed');
                            }
                            return res.json();
                        })
                        .then(data => {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message || 'Photo deleted successfully',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            fetchSliders();
                        })
                        .catch(err => {
                            Swal.close();
                            console.error('Delete error:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Delete Failed',
                                text: 'Failed to delete photo. Please try again.',
                                confirmButtonColor: '#d33'
                            });
                        });
                }
            });
        }

        function updatePhoto(id) {
            let input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = function () {
                let file = this.files[0];

                if (!file) {
                    return;
                }

                // Validate file size (max 200KB)
                if (file.size > 200 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Photo size must be less than 200KB. Current size: ' + (file.size / 1024).toFixed(2) + 'KB',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                let formData = new FormData();
                formData.append('photo', file);

                // Show loading
                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/api/home_slider/${id}`, {
                    method: 'POST',
                    headers: { ...headers, 'X-HTTP-Method-Override': 'PUT' },
                    body: formData
                })
                    .then(res => {
                        if (res.status === 422) {
                            return res.json().then(err => {
                                Swal.close();
                                let errorMessages = err.errors ? Object.values(err.errors).flat().join('\n') : err.message;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    html: errorMessages.replace(/\n/g, '<br>'),
                                    confirmButtonColor: '#d33'
                                });
                                throw new Error('Validation failed');
                            });
                        }
                        if (!res.ok) {
                            throw new Error('Update failed');
                        }
                        return res.json();
                    })
                    .then(data => {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: data.message || 'Photo updated successfully',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        fetchSliders();
                    })
                    .catch(err => {
                        Swal.close();
                        if (err.message !== 'Validation failed') {
                            console.error('Update error:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: 'Failed to update photo. Please try again.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
            };
            input.click();
        }

        // Initial load
        fetchSliders();
    </script>
@endsection