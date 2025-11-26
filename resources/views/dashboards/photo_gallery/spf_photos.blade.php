@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .photos-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            animation: fadeInDown 0.6s ease-out;
            gap: 2rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff !important;
            margin: 0;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3), 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Event Card */
        .event-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Event Header */
        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .event-title {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .event-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-edit-event {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-edit-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 172, 254, 0.4);
        }

        .btn-delete-event {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(250, 112, 154, 0.3);
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-delete-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(250, 112, 154, 0.4);
        }

        /* Photo Grid */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .photo-item {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: #fff;
        }

        .photo-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .event-photo {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .photo-item:hover .event-photo {
            transform: scale(1.05);
        }

        .photo-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 1.5rem 1rem 1rem;
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .photo-item:hover .photo-actions {
            opacity: 1;
        }

        .btn-photo-action {
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-photo-edit {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            box-shadow: 0 2px 8px rgba(79, 172, 254, 0.3);
        }

        .btn-photo-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.4);
        }

        .btn-photo-delete {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #fff;
            box-shadow: 0 2px 8px rgba(250, 112, 154, 0.3);
        }

        .btn-photo-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(250, 112, 154, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #fff;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem 2rem;
            border: none;
        }

        .modal-header h5 {
            font-weight: 700;
            margin: 0;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn-modal-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-modal-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .page-header {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .btn-add-photos {
                width: 100%;
                justify-content: center;
            }

            .event-card {
                padding: 1.5rem;
            }

            .event-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .photo-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
            }

            .event-photo {
                height: 180px;
            }
        }

        /* Add Photos Button */
        .btn-add-photos {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.875rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 8px 24px rgba(245, 87, 108, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            white-space: nowrap;
            animation: fadeInRight 0.6s ease-out 0.3s both;
        }

        .btn-add-photos:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(245, 87, 108, 0.5);
        }

        .btn-add-photos:active {
            transform: translateY(-2px);
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 768px) {
            .btn-add-photos {
                padding: 0.875rem 1.5rem;
                font-size: 0.95rem;
            }
        }
    </style>

    <div class="photos-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>📸 SPF फोटो गैलरी</h1>
            <button class="btn-add-photos" onclick="window.location='/spf_photo_gallery'">
                <i class="bi bi-plus-circle"></i>
                Add Photos
            </button>
        </div>

        <!-- Photo Gallery -->
        <div id="photoGallery"></div>
    </div>

    <!-- Photo Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="photo_id" id="photo_id">
                <input type="hidden" name="old_photo" id="old_photo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>🖼️ Replace Photo</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label fw-bold">Select New Photo</label>
                        <input type="file" class="form-control" name="new_photo" accept="image/*" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-save">
                            <i class="bi bi-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const category = "spf";
        window.photoMap = {};

        function loadGallery() {
            window.photoMap = {};

            // Show loading
            document.getElementById('photoGallery').innerHTML = `
                                                <div class="empty-state">
                                                    <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <h3 class="mt-3">Loading photos...</h3>
                                                </div>
                                            `;

            fetch(`/api/photo-gallery/fetch/${category}`)
                .then(res => res.json())
                .then(events => {
                    if (!events || events.length === 0) {
                        document.getElementById('photoGallery').innerHTML = `
                                                            <div class="empty-state">
                                                                <i class="bi bi-images"></i>
                                                                <h3>No photos yet</h3>
                                                                <p>Upload some photos to get started!</p>
                                                            </div>
                                                        `;
                        return;
                    }

                    let html = '';
                    events.forEach((event, index) => {
                        html += `
                                                            <div class="event-card" style="animation-delay: ${index * 0.1}s">
                                                                <div class="event-header">
                                                                    <h2 class="event-title">${event.event_name}</h2>
                                                                    <div class="event-actions">
                                                                        <button class="btn-edit-event" onclick="enableEventEdit('${event.event_name}')">
                                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                                        </button>
                                                                        <button class="btn-delete-event" onclick="deleteEvent('${event.event_name}')">
                                                                            <i class="bi bi-trash me-1"></i>Delete
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="photo-grid">`;

                        event.photos.forEach(photoObj => {
                            window.photoMap[photoObj.url] = photoObj.id;
                            html += `
                                                                <div class="photo-item">
                                                                    <img src="${photoObj.url}" class="event-photo" alt="Event Photo">
                                                                    <div class="photo-actions">
                                                                        <button class="btn-photo-action btn-photo-edit" onclick="openEdit('${photoObj.url}')">
                                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                                        </button>
                                                                        <button class="btn-photo-action btn-photo-delete" onclick="deletePhoto('${photoObj.url}')">
                                                                            <i class="bi bi-trash me-1"></i>Delete
                                                                        </button>
                                                                    </div>
                                                                </div>`;
                        });

                        html += `</div></div>`;
                    });
                    document.getElementById('photoGallery').innerHTML = html;
                })
                .catch(err => {
                    document.getElementById('photoGallery').innerHTML = `
                                                        <div class="empty-state">
                                                            <i class="bi bi-exclamation-triangle"></i>
                                                            <h3>Error loading photos</h3>
                                                            <p>Please try again later</p>
                                                        </div>
                                                    `;
                });
        }

        function enableEventEdit(oldName) {
            Swal.fire({
                title: 'Edit Event Name',
                input: 'text',
                inputValue: oldName,
                inputPlaceholder: 'Enter new event name',
                showCancelButton: true,
                confirmButtonText: 'Save',
                confirmButtonColor: '#667eea',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Event name is required!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value !== oldName) {
                    fetch(`/api/photo-gallery/update-event/${oldName}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ event_name: result.value })
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonColor: '#667eea'
                            });
                            loadGallery();
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update event name',
                                confirmButtonColor: '#667eea'
                            });
                        });
                }
            });
        }

        function deleteEvent(eventName) {
            Swal.fire({
                title: 'Are you sure?',
                text: `This will delete the entire "${eventName}" event and all its photos permanently!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fa709a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/photo-gallery/delete-event/${encodeURIComponent(eventName)}/${category}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                confirmButtonColor: '#667eea'
                            });
                            loadGallery();
                        });
                }
            });
        }

        function openEdit(photoUrl) {
            document.getElementById('old_photo').value = photoUrl;
            document.getElementById('photo_id').value = window.photoMap[photoUrl];
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let id = formData.get('photo_id');

            Swal.fire({
                title: 'Updating...',
                html: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/api/photo-gallery/update/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#667eea'
                    });
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    loadGallery();
                })
                .catch(err => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update photo',
                        confirmButtonColor: '#667eea'
                    });
                });
        });

        function deletePhoto(photoUrl) {
            Swal.fire({
                title: 'Delete Photo?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fa709a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = window.photoMap[photoUrl];

                    fetch(`/api/photo-gallery/delete-single/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ photo_url: photoUrl })
                    })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message,
                                confirmButtonColor: '#667eea'
                            });
                            loadGallery();
                        });
                }
            });
        }

        // Load gallery on page load
        loadGallery();
    </script>
@endsection