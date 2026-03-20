@extends('includes.layouts.shree_sangh')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container py-4">

        <!-- Alert Message -->
        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <div>
                📸 IMAGE upload वैकल्पिक है। अगर upload करते हैं तो size 200 KB से ज़्यादा नहीं होना चाहिए। <br>
                📝 Title और Description डालना अनिवार्य है। <br>
                🔗 अगर Offline mode चुनते हैं तो Google Location Link अनिवार्य है।
            </div>
        </div>

        <h4 class="mb-3">📰 न्यूज़ अपडेट</h4>

        <!-- News Form -->
        <form id="newsForm" enctype="multipart/form-data" class="row g-2">
            <input type="hidden" name="news_id" id="news_id">

            <!-- Title -->
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Title (Required)">
            </div>

            <!-- Event Date -->
            <div class="col-md-3">
                <label class="form-label">Event Date</label>
                <input type="date" class="form-control" name="date" id="date">
            </div>

            <!-- Event Time -->
            <div class="col-md-3">
                <label class="form-label">Event Time</label>
                <input type="text" class="form-control" name="time" id="time" placeholder="10 am से 11 am">
            </div>

            <!-- Event Location -->
            <div class="col-md-6">
                <label class="form-label">Event Location</label>
                <input type="text" class="form-control" name="location" id="location" placeholder="Location">
            </div>

            <!-- Online/Offline Toggle -->
            <div class="col-md-6">
                <label class="form-label">Event Type</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="modeToggle" name="mode_toggle">
                    <label class="form-check-label" for="modeToggle">
                        <span id="modeLabel">Offline</span>
                    </label>
                    <input type="hidden" name="mode" id="mode" value="offline">
                </div>
            </div>

            <!-- Location Link (Google Maps) -->
            <div class="col-md-6" id="locationLinkContainer" style="display: block;">
                <label class="form-label">Google Location Link <span class="text-danger" id="locationLinkRequired">*</span></label>
                <input type="url" class="form-control" name="location_link" id="location_link" placeholder="https://maps.google.com/...">
                <small class="text-muted">Required for Offline events</small>
            </div>

            <!-- Event DTP (Photo Upload) -->
            <div class="col-md-6">
                <label class="form-label">Event DTP</label>
                <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
                <small class="text-muted">Optional: Only image under 200KB</small>
            </div>

            <!-- Event Description -->
            <div class="col-12">
                <label class="form-label">Event Description</label>
                <textarea class="form-control" name="description" id="description" placeholder="Description (Required)"
                    rows="2"></textarea>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-sm btn-primary" id="submitBtn">
                    <i class="bi bi-plus-circle"></i> Add News
                </button>
            </div>
        </form>


        <!-- News List -->
        <div class="mt-4" id="newsList"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('newsForm');
            const newsList = document.getElementById('newsList');
            const submitBtn = document.getElementById('submitBtn');
            const modeToggle = document.getElementById('modeToggle');
            const modeInput = document.getElementById('mode');
            const modeLabel = document.getElementById('modeLabel');
            const locationLinkInput = document.getElementById('location_link');
            const locationLinkRequired = document.getElementById('locationLinkRequired');

            // Toggle handler for online/offline
            modeToggle.addEventListener('change', function () {
                if (this.checked) {
                    modeInput.value = 'online';
                    modeLabel.textContent = 'Online';
                    locationLinkRequired.style.display = 'none';
                } else {
                    modeInput.value = 'offline';
                    modeLabel.textContent = 'Offline';
                    locationLinkRequired.style.display = 'inline';
                }
            });

            // Fetch News
            function fetchNews() {
                fetch('/api/news')
                    .then(res => res.json())
                    .then(data => {
                        newsList.innerHTML = '';
                        if (!data.length) {
                            newsList.innerHTML = `<div class="text-center text-muted">कोई न्यूज़ उपलब्ध नहीं है</div>`;
                            return;
                        }

                        data.forEach(item => {
                            const modeLabel = item.mode === 'online' ? '🌐 Online' : '📍 Offline';
                            const locationDisplay = item.mode === 'online' 
                                ? (item.location_link ? `<a href="${item.location_link}" target="_blank"><i class="bi bi-link-45deg"></i> Join Online</a>` : '')
                                : (item.location_link ? `<a href="${item.location_link}" target="_blank"><i class="bi bi-geo-alt"></i> Location</a> - ${item.location || ''}` : (item.location || ''));

                            newsList.innerHTML += `
                                                                            <div class="border rounded p-2 mb-2 d-flex align-items-center justify-content-between shadow-sm">
                                                                                <div class="d-flex align-items-center">
                                                                                    <!-- Photo -->
                                                                                    <img src="${item.photo ? '/storage/' + item.photo : 'https://via.placeholder.com/60x60?text=No+Image'}" 
                                                                                         class="rounded me-2" 
                                                                                         style="height:60px; width:60px; object-fit:cover;">

                                                                                    <!-- Details -->
                                                                                    <div>
                                                                                        <h6 class="mb-1 text-primary fw-bold">${item.title}</h6>
                                                                                        <small class="text-muted badge ${item.mode === 'online' ? 'bg-success' : 'bg-info'}">${modeLabel}</small>
                                                                                        <br>
                                                                                        <small class="text-muted">
                                                                                            <i class="bi bi-calendar-event"></i> ${item.date ?? '-'}  
                                                                                            ${item.time ? ` | <i class="bi bi-clock"></i> ${item.time}` : ''}
                                                                                        </small>
                                                                                        <br>
                                                                                        <small class="text-muted">
                                                                                            ${locationDisplay}
                                                                                        </small>
                                                                                        <p class="mb-0 small">${item.description ?? ''}</p>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Actions -->
                                                                                <div class="ms-2 text-end">
                                                                                    <button class="btn btn-sm btn-outline-warning me-1" onclick='editNews(${JSON.stringify(item)})'>
                                                                                        <i class="bi bi-pencil-square"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteNews(${item.id})">
                                                                                        <i class="bi bi-trash"></i>
                                                                                    </button>
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
                const mode = modeInput.value;
                const locationLink = locationLinkInput.value;

                if (!formData.get('title') || !formData.get('description')) {
                    Swal.fire('Error', 'Title और Description डालना ज़रूरी है!', 'error');
                    return;
                }

                if (mode === 'offline' && !locationLink) {
                    Swal.fire('Error', 'Offline event के लिए Google Location Link ज़रूरी है!', 'error');
                    return;
                }

                if (!locationLink.startsWith('http')) {
                    Swal.fire('Error', 'Location Link एक valid URL होना चाहिए!', 'error');
                    return;
                }

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
                        modeToggle.checked = false;
                        modeInput.value = 'offline';
                        modeLabel.textContent = 'Offline';
                        locationLinkRequired.style.display = 'inline';
                        submitBtn.innerHTML = `<i class="bi bi-plus-circle"></i> Add News`;
                        fetchNews();
                    })
                    .catch(err => console.error(err));
            });

            // Edit News
            window.editNews = function (data) {
                document.getElementById('news_id').value = data.id;
                document.getElementById('title').value = data.title;
                document.getElementById('date').value = data.date;
                document.getElementById('time').value = data.time;
                document.getElementById('location').value = data.location;
                document.getElementById('location_link').value = data.location_link || '';
                document.getElementById('description').value = data.description;
                
                // Set toggle based on mode
                if (data.mode === 'online') {
                    modeToggle.checked = true;
                    modeLabel.textContent = 'Online';
                    locationLinkRequired.style.display = 'none';
                } else {
                    modeToggle.checked = false;
                    modeLabel.textContent = 'Offline';
                    locationLinkRequired.style.display = 'inline';
                }
                modeInput.value = data.mode || 'offline';
                
                submitBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Update News`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // Delete News
            window.deleteNews = function (id) {
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