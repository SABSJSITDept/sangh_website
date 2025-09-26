@extends('includes.layouts.sahitya')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pakhi Ka Panna - Edit Only</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light p-4">

<div class="container">
    <h2 class="mb-4 text-center text-primary fw-bold">📄 पक्खी का पाना </h2>

    <!-- NOTE: Add / Delete have been removed. Only Edit is available via modal. -->

    <!-- Uploaded PDFs Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-semibold">📂 Uploaded PDFs</div>
        <div class="card-body">
            <table class="table table-bordered align-middle text-center" id="pakhiTable">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Year</th>
                        <th>PDF</th>
                        <th style="width: 160px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will load here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">✏ Edit PDF</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm">
            <input type="hidden" id="edit_pakhi_id" name="pakhi_id">

            <div class="mb-3">
                <label class="form-label fw-semibold">Year</label>
                <select name="year" id="edit_year" class="form-select" required>
                    <option value="">-- Select Year --</option>
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Replace PDF (Optional, Max 2MB)</label>
                <input type="file" name="pdf" id="edit_pdf" class="form-control" accept="application/pdf">
                <div class="form-text text-muted">If left empty, existing PDF will remain.</div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", fetchPakhi);

function fetchPakhi() {
    fetch("/api/pakhi")
        .then(res => res.json())
        .then(data => {
            let rows = "";
            if (!data || data.length === 0) {
                rows = `<tr><td colspan="4" class="text-muted">No records found</td></tr>`;
            } else {
                data.forEach(item => {
                    rows += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.year}</td>
                            <td>
                                <a href="${item.pdf}" target="_blank" class="btn btn-sm btn-outline-primary">View PDF</a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="openEditModal(${item.id}, '${item.year}')">✏ Edit</button>
                            </td>
                        </tr>`;
                });
            }
            document.querySelector("#pakhiTable tbody").innerHTML = rows;
        })
        .catch(err => console.error(err));
}

function openEditModal(id, year){
    document.getElementById('edit_pakhi_id').value = id;
    document.getElementById('edit_year').value = year;
    // clear file input
    document.getElementById('edit_pdf').value = '';
    // show modal (Bootstrap 5)
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}

// Handle update form submit (PUT)
document.getElementById('updateForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    let id = document.getElementById('edit_pakhi_id').value;
    formData.append('_method', 'PUT');

    fetch(`/api/pakhi/${id}`, {
        method: 'POST', // using POST with _method=PUT for Laravel
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    })
    .then(async res => {
        let data = await res.json();
        if(res.ok && data.success){
            Swal.fire('✅ Success', 'Updated Successfully!', 'success');
            // hide modal
            var modalEl = document.getElementById('editModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            fetchPakhi();
        } else {
            if(data.errors){
                let errorMessages = "";
                Object.values(data.errors).forEach(errArr => {
                    errArr.forEach(msg => errorMessages += `• ${msg}<br>`);
                });
                Swal.fire({ icon: 'error', title: 'Validation Error', html: errorMessages });
            } else {
                Swal.fire('❌ Error', data.message || 'Something went wrong!', 'error');
            }
        }
    })
    .catch(err => Swal.fire('❌ Error', err.message, 'error'));
});
</script>

<!-- Bootstrap JS (required for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection