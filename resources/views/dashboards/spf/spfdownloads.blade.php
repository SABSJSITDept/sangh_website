@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>📥 SPF Downloads</h3>
            <button class="btn btn-success" onclick="showCreateForm()">
                <i class="bi bi-plus-circle"></i> Add New Download
            </button>
        </div>

        <!-- Create/Edit Download Form (Hidden by default) -->
        <form id="downloadForm" class="mb-4 card p-4 shadow-sm d-none" enctype="multipart/form-data">
            <h5 class="card-title mb-3" id="formTitle">Add New Download</h5>
            <input type="hidden" id="download_id">
            <input type="hidden" id="formMode" value="create">

            <div class="mb-3">
                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">File <small class="text-muted">(Max: 5MB)</small> <span
                        class="text-danger" id="fileRequired">*</span></label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
                <div class="form-text">Allowed: PDF, JPG, JPEG, PNG. Maximum file size: 5MB</div>
                <div id="currentFileInfo" class="mt-2 d-none">
                    <small class="text-info">Current File: <a href="#" id="currentFileLink" target="_blank">View
                            File</a></small>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-save"></i> Save Download
                </button>
                <button type="button" class="btn btn-secondary" id="cancelBtn">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
            </div>
        </form>

        <!-- Downloads Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">All Downloads</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%;">Title</th>
                                <th style="width: 30%;">File</th>
                                <th style="width: 30%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="downloadsTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiUrl = "/api/spf-downloads";
        const storageUrl = "/storage/";

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

        // Get file extension
        function getFileExtension(filename) {
            return filename.split('.').pop().toLowerCase();
        }

        // Show Create Form
        function showCreateForm() {
            resetForm();
            document.getElementById("formMode").value = "create";
            document.getElementById("formTitle").textContent = "Add New Download";
            document.getElementById("submitBtn").innerHTML = '<i class="bi bi-save"></i> Save Download';
            document.getElementById("fileRequired").style.display = "inline";
            document.getElementById("currentFileInfo").classList.add("d-none");
            document.getElementById("downloadForm").classList.remove("d-none");
            document.getElementById("downloadForm").scrollIntoView({ behavior: "smooth", block: "start" });
        }

        // Fetch Downloads (READ)
        async function fetchDownloads() {
            try {
                let res = await fetch(apiUrl);
                let result = await res.json();
                let rows = "";

                if (result.success && result.data.length > 0) {
                    result.data.forEach(download => {
                        let fileUrl = download.file ? storageUrl + download.file : '#';
                        let fileExt = download.file ? getFileExtension(download.file) : '';
                        let fileIcon = '';

                        if (fileExt === 'pdf') {
                            fileIcon = '📄';
                        } else if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                            fileIcon = '🖼️';
                        }

                        rows += `
                            <tr>
                                <td><strong>${download.title}</strong></td>
                                <td>
                                    ${download.file ?
                                `<a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">${fileIcon} View File</a>` :
                                '<span class="text-muted">No File</span>'
                            }
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editDownload(${download.id})">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteDownload(${download.id})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    rows = '<tr><td colspan="3" class="text-center text-muted">No downloads found. Click "Add New Download" to create one.</td></tr>';
                }

                document.getElementById("downloadsTable").innerHTML = rows;
            } catch (error) {
                console.error('Error fetching downloads:', error);
                showAlert("Failed to load downloads", "error");
            }
        }

        // Create/Update Download Form Submit
        document.getElementById("downloadForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            
            const mode = document.getElementById("formMode").value;
            const id = document.getElementById("download_id").value;
            const file = document.getElementById("file").files[0];

            // Validation
            if (mode === "create" && !file) {
                showAlert("Please select a file to upload", "error");
                return;
            }

            if (file && file.size > 5 * 1024 * 1024) {
                showAlert("File size must not exceed 5MB", "error");
                return;
            }

            // Create FormData
            let formData = new FormData();
            formData.append('title', document.getElementById("title").value);
            
            if (file) {
                formData.append('file', file);
            }

            let url = apiUrl;
            let method = "POST";

            if (mode === "update") {
                url = `${apiUrl}/${id}`;
                formData.append('_method', 'PUT');
            }

            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                let result = await res.json();

                if (res.ok && result.success) {
                    showAlert(result.message);
                    fetchDownloads();
                    resetForm();
                } else {
                    let errorMsg = result.message || "Error saving download!";
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join(', ');
                    }
                    showAlert(errorMsg, "error");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to save download", "error");
            }
        });

        // Edit Download (UPDATE)
        async function editDownload(id) {
            try {
                let res = await fetch(`${apiUrl}/${id}`);
                let result = await res.json();

                if (result.success) {
                    const download = result.data;
                    
                    document.getElementById("formMode").value = "update";
                    document.getElementById("download_id").value = download.id;
                    document.getElementById("title").value = download.title;
                    document.getElementById("formTitle").textContent = "Edit Download";
                    document.getElementById("submitBtn").innerHTML = '<i class="bi bi-save"></i> Update Download';
                    document.getElementById("fileRequired").style.display = "none";

                    // Show current file info
                    if (download.file) {
                        document.getElementById("currentFileLink").href = storageUrl + download.file;
                        document.getElementById("currentFileInfo").classList.remove("d-none");
                    }

                    // Show the form
                    document.getElementById("downloadForm").classList.remove("d-none");
                    document.getElementById("downloadForm").scrollIntoView({ behavior: "smooth", block: "start" });
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to load download", "error");
            }
        }

        // Delete Download (DELETE)
        async function deleteDownload(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        let res = await fetch(`${apiUrl}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        let response = await res.json();

                        if (res.ok && response.success) {
                            showAlert(response.message || 'Download deleted successfully');
                            fetchDownloads();
                        } else {
                            showAlert(response.message || 'Failed to delete download', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert('Failed to delete download', 'error');
                    }
                }
            });
        }

        // Cancel Edit
        document.getElementById("cancelBtn").addEventListener("click", resetForm);

        function resetForm() {
            document.getElementById("downloadForm").reset();
            document.getElementById("download_id").value = "";
            document.getElementById("formMode").value = "create";
            document.getElementById("downloadForm").classList.add("d-none");
            document.getElementById("currentFileInfo").classList.add("d-none");
        }

        // Initial load
        fetchDownloads();
    </script>
@endsection