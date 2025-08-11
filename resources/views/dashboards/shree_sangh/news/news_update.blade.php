@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4">
    <h2 class="mb-4">📰 न्यूज़ अपडेट</h2>

    <!-- News Form -->
    <form id="newsForm" enctype="multipart/form-data" class="row g-3">
        <input type="hidden" name="news_id" id="news_id">

        <div class="col-md-6">
            <input type="text" class="form-control" name="title" id="title" placeholder="Title">
        </div>

        <div class="col-md-3">
            <input type="date" class="form-control" name="date" id="date">
        </div>

        <div class="col-md-3">
            <input type="text" class="form-control" name="time" id="time" placeholder="10 am से 11 am">
        </div>

        <div class="col-md-6">
            <input type="text" class="form-control" name="location" id="location" placeholder="Location">
        </div>

        <div class="col-md-6">
            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
            <small class="text-muted">Only image under 200KB</small>
        </div>

        <div class="col-12">
            <textarea class="form-control" name="description" id="description" placeholder="Description" rows="3"></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary" id="submitBtn">Add News</button>
        </div>
    </form>

    <!-- News List -->
    <div class="mt-5" id="newsList"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('newsForm');
    const newsList = document.getElementById('newsList');
    const submitBtn = document.getElementById('submitBtn');

    // Fetch News
    function fetchNews() {
        fetch('/api/news')
            .then(res => res.json())
            .then(data => {
                newsList.innerHTML = '';
                if (!data.length) {
                    newsList.innerHTML = `<div class="text-center text-muted">No news available</div>`;
                    return;
                }

                data.forEach(item => {
                    newsList.innerHTML += `
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="row g-0 align-items-center">
                                <!-- Photo -->
                                <div class="col-md-2">
                                    <img src="/storage/${item.photo}" class="img-fluid rounded-start" 
                                         style="height:120px; width:100%; object-fit:cover;">
                                </div>

                                <!-- Details -->
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <!-- Title -->
                                        <h5 class="card-title fw-bold text-primary mb-1">${item.title}</h5>
                                        
                                        <!-- Date, Time, Location -->
                                        <p class="mb-1 text-muted">
                                            <i class="bi bi-calendar-event"></i> ${item.date ?? '-'}  
                                            ${item.time ? ` | <i class="bi bi-clock"></i> ${item.time}` : ''}
                                            ${item.location ? ` | <i class="bi bi-geo-alt"></i> ${item.location}` : ''}
                                        </p>

                                        <!-- Description -->
                                        <p class="card-text">
                                            ${item.description ?? ''}
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="col-md-2 text-end pe-3">
                                    <button class="btn btn-sm btn-warning mb-2 w-100" onclick='editNews(${JSON.stringify(item)})'>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger w-100" onclick="deleteNews(${item.id})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
    }

    // Submit Form
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const photo = formData.get('photo');
        const newsId = document.getElementById('news_id').value;

        if (photo && photo.size > 204800) {
            Swal.fire('Error', 'Image must be under 200KB!', 'error');
            return;
        }

        const url = newsId ? `/api/news/${newsId}` : '/api/news';
        const method = 'POST';
        if (newsId) {
            formData.append('_method', 'PUT');
        }

        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(async res => {
            if (res.status === 422) {
                const errorData = await res.json();
                const errors = errorData.errors;

                for (const field in errors) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = document.createElement('div');
                        feedback.classList.add('invalid-feedback');
                        feedback.textContent = errors[field][0];
                        input.insertAdjacentElement('afterend', feedback);
                    }
                }

                Swal.fire('Validation Error', 'Please fix the highlighted fields.', 'error');
                throw new Error('Validation failed');
            }
            return res.json();
        })
        .then(data => {
            Swal.fire('Success', newsId ? 'News updated successfully!' : 'News added successfully!', 'success');
            form.reset();
            document.getElementById('news_id').value = '';
            submitBtn.innerText = 'Add News';
            fetchNews();
        })
        .catch(err => console.error(err));
    });

    // Edit News
    window.editNews = function(data) {
        document.getElementById('news_id').value = data.id;
        document.getElementById('title').value = data.title;
        document.getElementById('date').value = data.date;
        document.getElementById('time').value = data.time;
        document.getElementById('location').value = data.location;
        document.getElementById('description').value = data.description;
        submitBtn.innerText = 'Update News';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Delete News
    window.deleteNews = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This news will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/api/news/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    fetchNews();
                    Swal.fire('Deleted!', 'News has been deleted.', 'success');
                });
            }
        });
    };

    fetchNews();
});
</script>
@endsection
