@extends('includes.layouts.sahitya')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 Bundle JS (includes Popper + Modal support) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h4 class="text-center mb-4">चातुर्मास सूची</h4>

    <!-- Upload Form -->
    <form id="chaturmasForm" enctype="multipart/form-data" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <label for="year" class="form-label">वर्ष</label>
                <select class="form-select" name="year" id="year" required>
                    <option value="">वर्ष चुनें</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="pdf" class="form-label">PDF (अधिकतम 2MB)</label>
                <input type="file" class="form-control" name="pdf" id="pdf" accept="application/pdf" required>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">अपलोड करें</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered" id="suchiTable">
            <thead>
                <tr>
                    <th>वर्ष</th>
                    <th>PDF</th>
                    <th>कार्य</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">PDF अपडेट करें</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="mb-3">
          <label for="edit_year" class="form-label">वर्ष</label>
          <select class="form-select" id="edit_year" name="year" required></select>
        </div>
        <div class="mb-3">
          <label for="edit_pdf" class="form-label">नई PDF (यदि बदलनी हो)</label>
          <input type="file" class="form-control" id="edit_pdf" name="pdf" accept="application/pdf">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">अपडेट करें</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const yearSelect = document.getElementById("year");
    const currentYear = new Date().getFullYear();
    for (let y = 2020; y <= currentYear; y++) {
        const option = document.createElement("option");
        option.value = y;
        option.text = y;
        yearSelect.appendChild(option);
    }

    const tableBody = document.querySelector("#suchiTable tbody");

    function fetchData() {
        fetch("/api/chaturmas-suchi")
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(item => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.year}</td>
                            <td><a href="${item.pdf}" target="_blank" class="btn btn-sm btn-primary">देखें</a></td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" onclick="openEditModal(${item.id}, ${item.year})">✏️</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteEntry(${item.id})">🗑️</button>
                            </td>
                        </tr>
                    `;
                });
            });
    }

    fetchData();

    document.getElementById("chaturmasForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("/api/chaturmas-suchi", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(async res => {
            if (!res.ok) {
                const errorData = await res.json();
                if (errorData.errors?.year?.[0]?.includes("taken")) {
                    throw new Error("इस वर्ष की PDF पहले से मौजूद है।");
                }
                throw new Error("कृपया सही फ़ॉर्म भरें और PDF जोड़ें!");
            }
            return res.json();
        })
        .then(() => {
            Swal.fire("सफल!", "PDF सफलतापूर्वक जोड़ी गई!", "success");
            this.reset();
            fetchData();
        })
        .catch(err => {
            Swal.fire("त्रुटि!", err.message, "error");
        });
    });

    window.openEditModal = function(id, year) {
        document.getElementById("edit_id").value = id;

        const editYear = document.getElementById("edit_year");
        editYear.innerHTML = '';
        const currentYear = new Date().getFullYear();
        for (let y = 2020; y <= currentYear; y++) {
            const option = document.createElement("option");
            option.value = y;
            option.text = y;
            if (y === year) option.selected = true;
            editYear.appendChild(option);
        }

        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }

    document.getElementById("editForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const id = document.getElementById("edit_id").value;
        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        fetch(`/api/chaturmas-suchi/${id}`, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(async res => {
            if (!res.ok) {
                const errorData = await res.json();
                if (errorData.errors?.year?.[0]?.includes("taken")) {
                    throw new Error("इस वर्ष की PDF पहले से मौजूद है।");
                }
                throw new Error("फ़ॉर्म मान्य नहीं है।");
            }
            return res.json();
        })
        .then(() => {
            Swal.fire("अपडेट!", "PDF सफलतापूर्वक अपडेट की गई!", "success");
            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
            fetchData();
        })
        .catch(err => {
            Swal.fire("त्रुटि!", err.message, "error");
        });
    });

    window.deleteEntry = function(id) {
        Swal.fire({
            title: "क्या आप वाकई हटाना चाहते हैं?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "हां, हटाएं!",
            cancelButtonText: "नहीं",
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/api/chaturmas-suchi/${id}`, {
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(() => {
                    Swal.fire("हटाया गया!", "PDF सफलतापूर्वक हटाई गई!", "success");
                    fetchData();
                })
                .catch(() => {
                    Swal.fire("त्रुटि!", "कुछ गलत हो गया!", "error");
                });
            }
        });
    }
});
</script>
@endsection
