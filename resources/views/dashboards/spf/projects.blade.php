@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container mt-4">
        <h3 class="mb-4">🚀 SPF Projects</h3>

        <!-- Project Form -->
        <form id="projectForm" class="mb-4 card p-4 shadow-sm">
            <input type="hidden" id="project_id">

            <div class="mb-3">
                <label class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" id="submitBtn">Save Project</button>
                <button type="button" class="btn btn-secondary d-none" id="cancelBtn">Cancel</button>
            </div>
        </form>

        <!-- Projects Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">All Projects</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%;">Title</th>
                                <th>Description</th>
                                <th style="width: 200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="projectsTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiUrl = "/api/spf-projects";

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

        // Fetch Projects
        async function fetchProjects() {
            try {
                let res = await fetch(apiUrl);
                let result = await res.json();
                let rows = "";

                if (result.success && result.data.length > 0) {
                    result.data.forEach(project => {
                        // Truncate description for table view
                        let desc = project.description.length > 100 ? project.description.substring(0, 100) + '...' : project.description;
                        rows += `
                                <tr>
                                    <td><strong>${project.title}</strong></td>
                                    <td>${desc}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info me-1" onclick="viewProject(${project.id})">👁️ View</button>
                                        <button class="btn btn-sm btn-warning me-1" onclick="editProject(${project.id})">✏️ Edit</button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteProject(${project.id})">🗑️ Delete</button>
                                    </td>
                                </tr>`;
                    });
                } else {
                    rows = '<tr><td colspan="3" class="text-center text-muted">No projects found</td></tr>';
                }

                document.getElementById("projectsTable").innerHTML = rows;
            } catch (error) {
                console.error('Error fetching projects:', error);
                showAlert("Failed to load projects", "error");
            }
        }

        // Add / Update Project
        document.getElementById("projectForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            let id = document.getElementById("project_id").value;

            let jsonData = {
                title: document.getElementById("title").value,
                description: document.getElementById("description").value
            };

            let url = id ? `${apiUrl}/${id}` : apiUrl;
            let method = id ? "PUT" : "POST";

            try {
                let res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(jsonData)
                });

                let result = await res.json();
                if (res.ok && result.success) {
                    showAlert(result.message);
                    fetchProjects();
                    resetForm();
                } else {
                    showAlert(result.message || "Error saving project!", "error");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to save project", "error");
            }
        });

        // View Project Details
        async function viewProject(id) {
            try {
                let res = await fetch(`${apiUrl}/${id}`);
                let result = await res.json();

                if (result.success) {
                    const project = result.data;

                    Swal.fire({
                        title: project.title,
                        html: `
                                    <div class="text-start">
                                        <p><strong>📝 Description:</strong></p>
                                        <p>${project.description}</p>
                                    </div>
                                `,
                        width: '600px',
                        confirmButtonText: 'Close'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to load project details", "error");
            }
        }

        // Edit Project
        async function editProject(id) {
            try {
                let res = await fetch(`${apiUrl}/${id}`);
                let result = await res.json();

                if (result.success) {
                    const project = result.data;
                    document.getElementById("project_id").value = project.id;
                    document.getElementById("title").value = project.title;
                    document.getElementById("description").value = project.description;

                    document.getElementById("submitBtn").textContent = "Update Project";
                    document.getElementById("cancelBtn").classList.remove("d-none");

                    showAlert("You can now update this project", "info");
                    document.getElementById("projectForm").scrollIntoView({ behavior: "smooth", block: "start" });
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to load project", "error");
            }
        }

        // Cancel Edit
        document.getElementById("cancelBtn").addEventListener("click", resetForm);

        function resetForm() {
            document.getElementById("projectForm").reset();
            document.getElementById("project_id").value = "";
            document.getElementById("submitBtn").textContent = "Save Project";
            document.getElementById("cancelBtn").classList.add("d-none");
        }

        // Delete Project
        async function deleteProject(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This project will be permanently deleted!",
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
                            fetchProjects();
                        } else {
                            showAlert("Delete failed!", "error");
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert("Failed to delete project", "error");
                    }
                }
            });
        }

        // Initial load
        fetchProjects();
    </script>
@endsection