@extends('includes.layouts.spf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container mt-4">
        <h3 class="mb-4">📚 SPF Safarnama</h3>

        <!-- Edit Safarnama Form (Hidden by default) -->
        <form id="safarnamaForm" class="mb-4 card p-4 shadow-sm d-none" enctype="multipart/form-data">
            <input type="hidden" id="safarnama_id">

            <div class="mb-3">
                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">PDF File <small class="text-muted">(Max: 5MB)</small></label>
                <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf">
                <div class="form-text">Only PDF files are allowed. Maximum file size: 5MB</div>
                <div id="currentPdfInfo" class="mt-2">
                    <small class="text-info">Current PDF: <a href="#" id="currentPdfLink" target="_blank">View PDF</a></small>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" id="submitBtn">Update Safarnama</button>
                <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
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

        // Update Safarnama
        document.getElementById("safarnamaForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            let id = document.getElementById("safarnama_id").value;

            if (!id) {
                showAlert("No safarnama selected for editing", "error");
                return;
            }

            // Validate PDF file size (5MB = 5 * 1024 * 1024 bytes)
            const pdfFile = document.getElementById("pdf").files[0];
            if (pdfFile) {
                if (pdfFile.size > 5 * 1024 * 1024) {
                    showAlert("PDF file size must not exceed 5MB", "error");
                    return;
                }
                console.log('PDF File:', pdfFile.name, 'Size:', pdfFile.size, 'Type:', pdfFile.type);
            }

            // Create FormData for file upload
            let formData = new FormData();
            formData.append('title', document.getElementById("title").value);
            formData.append('_method', 'PUT');

            if (pdfFile) {
                formData.append('pdf', pdfFile);
            }

            let url = `${apiUrl}/${id}`;

            console.log('Updating safarnama ID:', id);
            console.log('FormData entries:', Array.from(formData.entries()));

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
                console.log('Response:', result);

                if (res.ok && result.success) {
                    showAlert(result.message);
                    fetchSafarnama();
                    resetForm();
                } else {
                    let errorMsg = result.message || "Error updating safarnama!";
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join(', ');
                    }
                    console.error('Validation errors:', result.errors);
                    showAlert(errorMsg, "error");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert("Failed to update safarnama", "error");
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

                    // Show current PDF info
                    if (safarnama.pdf) {
                        document.getElementById("currentPdfLink").href = storageUrl + safarnama.pdf;
                    }

                    // Show the form
                    document.getElementById("safarnamaForm").classList.remove("d-none");

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
            document.getElementById("safarnamaForm").classList.add("d-none");
        }

        // Initial load
        fetchSafarnama();
    </script>
@endsection