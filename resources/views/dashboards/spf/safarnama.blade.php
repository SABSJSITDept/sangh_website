@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container mt-4">
        <h3 class="mb-4">📚 SPF Safarnama</h3>

        <!-- Safarnama Form -->
        <form id="safarnamaForm" class="mb-4 card p-4 shadow-sm" enctype="multipart/form-data">
            <input type="hidden" id="safarnama_id">

            <div class="mb-3">
                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">PDF File <span class="text-danger">*</span> <small
                        class="text-muted">(Max: 5MB)</small></label>
                <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf" required>
                <div class="form-text">Only PDF files are allowed. Maximum file size: 5MB</div>
                <div id="currentPdfInfo" class="mt-2 d-none">
                    <small class="text-info">Current PDF: <a href="#" id="currentPdfLink" target="_blank">View
                            PDF</a></small>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" id="submitBtn">Save Safarnama</button>
                <button type="button" class="btn btn-secondary d-none" id="cancelBtn">Cancel</button>
            </div>
        </form>

        <!-- Safarnama Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">All Safarnama</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%;">Title</th>
                                <th style="width: 30%;">PDF</th>
                                <th style="width: 30%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="safarnamaTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiUrl = "/api/spf-safarnama";
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

        // Fetch Safarnama
        async function fetchSafarnama() {
            try {
                let res = await fetch(apiUrl);
                let result = await res.json();
                let rows = "";

                if (result.success && result.data.length > 0) {
                    result.data.forEach(safarnama => {
                        let pdfUrl = safarnama.pdf ? storageUrl + safarnama.pdf : '#';
                        rows += `
                                    <tr>
                                        <td><strong>${safarnama.title}</strong></td>
                                        <td>
                                            ${safarnama.pdf ?
                                `<a href="${pdfUrl}" target="_blank" class="btn btn-sm btn-outline-primary">📄 View PDF</a>` :
                                '<span class="text-muted">No PDF</span>'
                            }
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning me-1" onclick="editSafarnama(${safarnama.id})">✏️ Edit</button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteSafarnama(${safarnama.id})">🗑️ Delete</button>
                                        </td>
                                    </tr>`;
                    });
                } else {
                    rows = '<tr><td colspan="3" class="text-center text-muted">No safarnama found</td></tr>';
                }

                document.getElementById("safarnamaTable").innerHTML = rows;
            } catch (error) {
                console.error('Error fetching safarnama:', error);
                showAlert("Failed to load safarnama", "error");
            }
        }

        // Add / Update Safarnama
        document.getElementById("safarnamaForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            let id = document.getElementById("safarnama_id").value;

            // Validate PDF file size (5MB = 5 * 1024 * 1024 bytes)
            const pdfFile = document.getElementById("pdf").files[0];
            if (pdfFile && pdfFile.size > 5 * 1024 * 1024) {
                showAlert("PDF file size must not exceed 5MB", "error");
                return;
            }

            // Create FormData for file upload
            let formData = new FormData();
            formData.append('title', document.getElementById("title").value);

            if (pdfFile) {
                formData.append('pdf', pdfFile);
            }

            // For update, we need to add _method field
            if (id) {
                formData.append('_method', 'PUT');
            }

            let url = id ? `${apiUrl}/${id}` : apiUrl;
            let method = "POST"; // Always POST for FormData

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
                    fetchSafarnama();
                    resetForm();
                } else {
                    let errorMsg = result.message || "Error saving safarnama!";
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join(', ');
                    }
                    showAlert(errorMsg, "error");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to save safarnama", "error");
            }
        });

        // Edit Safarnama
        async function editSafarnama(id) {
            try {
                let res = await fetch(`${apiUrl}/${id}`);
                let result = await res.json();

                if (result.success) {
                    const safarnama = result.data;
                    document.getElementById("safarnama_id").value = safarnama.id;
                    document.getElementById("title").value = safarnama.title;

                    // Make PDF field optional for edit
                    document.getElementById("pdf").removeAttribute('required');

                    // Show current PDF info
                    if (safarnama.pdf) {
                        document.getElementById("currentPdfInfo").classList.remove("d-none");
                        document.getElementById("currentPdfLink").href = storageUrl + safarnama.pdf;
                    }

                    document.getElementById("submitBtn").textContent = "Update Safarnama";
                    document.getElementById("cancelBtn").classList.remove("d-none");

                    showAlert("You can now update this safarnama", "info");
                    document.getElementById("safarnamaForm").scrollIntoView({ behavior: "smooth", block: "start" });
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to load safarnama", "error");
            }
        }

        // Cancel Edit
        document.getElementById("cancelBtn").addEventListener("click", resetForm);

        function resetForm() {
            document.getElementById("safarnamaForm").reset();
            document.getElementById("safarnama_id").value = "";
            document.getElementById("submitBtn").textContent = "Save Safarnama";
            document.getElementById("cancelBtn").classList.add("d-none");
            document.getElementById("pdf").setAttribute('required', 'required');
            document.getElementById("currentPdfInfo").classList.add("d-none");
        }

        // Delete Safarnama
        async function deleteSafarnama(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This safarnama and its PDF will be permanently deleted!",
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
                            fetchSafarnama();
                        } else {
                            showAlert("Delete failed!", "error");
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert("Failed to delete safarnama", "error");
                    }
                }
            });
        }

        // Initial load
        fetchSafarnama();
    </script>
@endsection