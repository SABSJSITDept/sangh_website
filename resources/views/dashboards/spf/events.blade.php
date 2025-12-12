@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .swal2-container {
            z-index: 9999 !important;
        }
    </style>

    <div class="container mt-4">
        <h3 class="mb-4">📅 SPF Events</h3>

        <!-- Event Form -->
        <form id="eventForm" enctype="multipart/form-data" class="mb-4 card p-4 shadow-sm">
            <input type="hidden" id="event_id">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Event Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Time <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="time" name="time" placeholder="e.g., 10:00 AM" required>
                    <div class="form-text text-muted">Enter time manually (e.g., 10:00 AM)</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Project <span class="text-danger">*</span></label>
                <select class="form-control" id="spf_project_id" name="spf_project_id" required>
                    <option value="">-- Select a Project --</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Registration Start Date</label>
                    <input type="date" class="form-control" id="event_reg_start" name="event_reg_start">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Registration Close Date</label>
                    <input type="date" class="form-control" id="event_reg_close" name="event_reg_close">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Event Photo <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                <div class="form-text text-muted">Max 2MB, JPG/PNG/GIF only</div>
            </div>

            <div id="previewContainer" class="mb-3"></div>

            <div>
                <button type="submit" class="btn btn-primary" id="submitBtn">Save Event</button>
                <button type="button" class="btn btn-secondary d-none" id="cancelBtn">Cancel</button>
            </div>
        </form>

        <!-- Events Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">All Events</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:100px;">Photo</th>
                                <th>Title</th>
                                <th style="width:120px;">Date</th>
                                <th style="width:100px;">Time</th>
                                <th>Location</th>
                                <th style="width:150px;">Show on Home</th>
                                <th style="width:200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="eventsTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiUrl = "/api/spf-events";
        const projectsApiUrl = "/api/spf-events-projects";

        // SweetAlert Wrapper
        function showAlert(message, type = "success") {
            Swal.fire({
                icon: type,
                text: message,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: "top-end"
            });
        }

        // Fetch and populate projects dropdown
        async function fetchProjects() {
            try {
                let res = await fetch(projectsApiUrl);
                let result = await res.json();

                if (result.success && result.data.length > 0) {
                    const selectElement = document.getElementById("spf_project_id");
                    result.data.forEach(project => {
                        const option = document.createElement("option");
                        option.value = project.id;
                        option.textContent = project.title;
                        selectElement.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error fetching projects:', error);
            }
        }

        // Preview Selected Image
        document.getElementById("photo").addEventListener("change", function () {
            const container = document.getElementById("previewContainer");
            container.innerHTML = "";
            const file = this.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                showAlert("Image must be less than 2MB!", "error");
                this.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.width = 150;
                img.className = "rounded border shadow-sm";
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        // Fetch Events
        async function fetchEvents() {
            try {
                let res = await fetch(apiUrl);
                let result = await res.json();
                let rows = "";

                if (result.success && result.data.length > 0) {
                    result.data.forEach(event => {
                        const photoUrl = event.photo ? `/storage/${event.photo}` : '/images/placeholder.jpg';
                        const isHomePageActive = event.home_page ? 'checked' : '';
                        const toggleBtnClass = event.home_page ? 'btn-success' : 'btn-outline-secondary';
                        const toggleIcon = event.home_page ? '✓' : '✗';
                        rows += `
                            <tr>
                                <td><img src="${photoUrl}" width="80" class="rounded border shadow-sm"></td>
                                <td><strong>${event.title}</strong></td>
                                <td>${event.date}</td>
                                <td>${event.time}</td>
                                <td>${event.location}</td>
                                <td>
                                    <button class="btn btn-sm ${toggleBtnClass}" onclick="toggleHomePage(${event.id})" title="Toggle home page visibility">
                                        ${toggleIcon} ${event.home_page ? 'Yes' : 'No'}
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info me-1" onclick="viewEvent(${event.id})">👁️ View</button>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editEvent(${event.id})">✏️ Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteEvent(${event.id})">🗑️ Delete</button>
                                </td>
                            </tr>`;
                    });
                } else {
                    rows = '<tr><td colspan="7" class="text-center text-muted">No events found</td></tr>';
                }

                document.getElementById("eventsTable").innerHTML = rows;
            } catch (error) {
                console.error('Error fetching events:', error);
                showAlert("Failed to load events", "error");
            }
        }

        // Add / Update Event
        document.getElementById("eventForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            let id = document.getElementById("event_id").value;

            let formData = new FormData();
            formData.append("title", document.getElementById("title").value);
            formData.append("date", document.getElementById("date").value);
            formData.append("time", document.getElementById("time").value);
            formData.append("location", document.getElementById("location").value);
            formData.append("description", document.getElementById("description").value);
            formData.append("spf_project_id", document.getElementById("spf_project_id").value);
            formData.append("event_reg_start", document.getElementById("event_reg_start").value);
            formData.append("event_reg_close", document.getElementById("event_reg_close").value);

            let photoFile = document.getElementById("photo").files[0];
            if (photoFile) {
                if (photoFile.size > 2 * 1024 * 1024) {
                    showAlert("Image must be less than 2MB!", "error");
                    return;
                }
                formData.append("photo", photoFile);
            } else if (!id) {
                showAlert("Please select a photo!", "error");
                return;
            }

            let url = id ? `${apiUrl}/${id}` : apiUrl;
            let method = "POST";
            if (id) formData.append("_method", "PUT");

            try {
                let res = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                let result = await res.json();
                if (res.ok && result.success) {
                    showAlert(result.message);
                    fetchEvents();
                    resetForm();
                } else {
                    showAlert(result.message || "Error saving event!", "error");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to save event", "error");
            }
        });

        // View Event Details
        async function viewEvent(id) {
            try {
                let res = await fetch(`${apiUrl}/${id}`);
                let result = await res.json();

                if (result.success) {
                    const event = result.data;
                    const photoUrl = event.photo ? `/storage/${event.photo}` : '/images/placeholder.jpg';

                    Swal.fire({
                        title: event.title,
                        html: `
                                <div class="text-start">
                                    <img src="${photoUrl}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                                    <p><strong>📅 Date:</strong> ${event.date}</p>
                                    <p><strong>🕐 Time:</strong> ${event.time}</p>
                                    <p><strong>📍 Location:</strong> ${event.location}</p>
                                    <p><strong>📝 Description:</strong></p>
                                    <p>${event.description}</p>
                                </div>
                            `,
                        width: '600px',
                        confirmButtonText: 'Close'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to load event details", "error");
            }
        }

        // Edit Event
        async function editEvent(id) {
            try {
                let res = await fetch(`${apiUrl}/${id}`);
                let result = await res.json();

                if (result.success) {
                    const event = result.data;
                    document.getElementById("event_id").value = event.id;
                    document.getElementById("title").value = event.title;
                    document.getElementById("date").value = event.date;
                    document.getElementById("time").value = event.time;
                    document.getElementById("location").value = event.location;
                    document.getElementById("description").value = event.description;
                    document.getElementById("spf_project_id").value = event.spf_project_id || "";
                    document.getElementById("event_reg_start").value = event.event_reg_start || "";
                    document.getElementById("event_reg_close").value = event.event_reg_close || "";

                    const photoUrl = event.photo ? `/storage/${event.photo}` : '';
                    if (photoUrl) {
                        document.getElementById("previewContainer").innerHTML = `
                                <img src="${photoUrl}" width="150" class="rounded border shadow-sm">
                                <p class="text-muted mt-2">Select a new photo to replace</p>
                            `;
                    }

                    document.getElementById("photo").removeAttribute('required');
                    document.getElementById("submitBtn").textContent = "Update Event";
                    document.getElementById("cancelBtn").classList.remove("d-none");

                    showAlert("You can now update this event", "info");
                    document.getElementById("eventForm").scrollIntoView({ behavior: "smooth", block: "start" });
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to load event", "error");
            }
        }

        // Cancel Edit
        document.getElementById("cancelBtn").addEventListener("click", resetForm);

        function resetForm() {
            document.getElementById("eventForm").reset();
            document.getElementById("event_id").value = "";
            document.getElementById("previewContainer").innerHTML = "";
            document.getElementById("submitBtn").textContent = "Save Event";
            document.getElementById("cancelBtn").classList.add("d-none");
            document.getElementById("photo").setAttribute('required', 'required');
        }

        // Toggle Home Page
        async function toggleHomePage(id) {
            try {
                let res = await fetch(`${apiUrl}/${id}/toggle-home-page`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });

                let result = await res.json();
                if (res.ok && result.success) {
                    showAlert(result.message);
                    fetchEvents();
                } else {
                    showAlert(result.message || "Failed to toggle home page!", "error");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to toggle home page", "error");
            }
        }

        // Delete Event
        async function deleteEvent(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This event will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#d33"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        let res = await fetch(`${apiUrl}/${id}`, {
                            method: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        let resultData = await res.json();
                        if (res.ok && resultData.success) {
                            showAlert(resultData.message);
                            fetchEvents();
                        } else {
                            showAlert("Delete failed!", "error");
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert("Failed to delete event", "error");
                    }
                }
            });
        }

        // Initial load
        fetchEvents();
        fetchProjects();
    </script>
@endsection