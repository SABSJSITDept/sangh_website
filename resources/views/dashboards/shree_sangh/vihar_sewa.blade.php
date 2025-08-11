@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container py-4">

    {{-- 🔔 Latest Entry --}}
    <div class="mb-4">
        <div class="card shadow-sm border-start border-success border-4">
            <div class="card-body">
                <h5 class="card-title text-success">🔔 आज की विहार जानकारी</h5>
                <div id="latestVihar" class="text-muted">
                    <em>लोड हो रहा है...</em>
                </div>
            </div>
        </div>
    </div>

    {{-- 🛕 Heading --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">🛕 विहार जानकारी प्रबंधन</h3>
    </div>

    {{-- 📋 Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="viharForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="aadi_thana" class="form-label">आदि थाना</label>
                        <input type="number" 
                               class="form-control shadow-sm" 
                               id="aadi_thana" 
                               placeholder="जैसे - 9" 
                               min="0" 
                               step="1"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               required>
                    </div>

                    <div class="col-md-5">
                        <label for="location" class="form-label">स्थान (रात्रि विश्राम हेतु)</label>
                        <input type="text" class="form-control shadow-sm" id="location" placeholder="स्थान लिखें" required>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-success btn-lg shadow">जोड़ें</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 📊 Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">📋 हाल की प्रविष्टियाँ</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>📅 तारीख</th>
                            <th>🏙️ आदि थाना</th>
                            <th>🛏️ स्थान</th>
                            <th>⚙️ क्रिया</th>
                        </tr>
                    </thead>
                    <tbody id="viharList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ✏️ Edit Modal --}}
<div class="modal fade" id="editViharModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="editViharForm">
        <div class="modal-header">
          <h5 class="modal-title">📝 विहार जानकारी संपादित करें</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_aadi_thana" class="form-label">आदि थाना</label>
            <input type="number" id="edit_aadi_thana" class="form-control" min="0" required>
          </div>
          <div class="mb-3">
            <label for="edit_location" class="form-label">स्थान (रात्रि विश्राम हेतु)</label>
            <input type="text" id="edit_location" class="form-control" required>
          </div>
          <input type="hidden" id="editViharId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">रद्द करें</button>
          <button type="submit" class="btn btn-primary">सहेजें</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ✅ Toast Notification --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
  <div id="toastAlert" class="toast align-items-center text-bg-success border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">सफलतापूर्वक जोड़ा गया!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

{{-- 🔄 Script --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let editModal = new bootstrap.Modal(document.getElementById('editViharModal'));
let toastEl = document.getElementById('toastAlert');
let toast = new bootstrap.Toast(toastEl);

function showToast(message, type = 'success') {
    document.getElementById('toastMessage').textContent = message;
    toastEl.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : 'success') + ' border-0';
    toast.show();
}

function fetchVihar() {
    fetch('/api/vihar')
        .then(res => res.json())
        .then(data => {
            let rows = '';
            data.forEach((item) => {
                rows += `
                    <tr>
                        <td>${item.formatted_date ?? ''}</td>
                        <td>${item.aadi_thana}</td>
                        <td>${item.location}</td>            
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="openEditModal(${item.id}, '${item.aadi_thana}', '${item.location}')">✏️</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteVihar(${item.id})">🗑️</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('viharList').innerHTML = rows;
        });
}

function fetchLatestVihar() {
    fetch('/api/vihar/latest')
        .then(res => res.json())
        .then(data => {
            if (data) {
                document.getElementById('latestVihar').innerHTML = `
                    <strong>📅 तारीख:</strong> ${data.formatted_date}<br>
                    <strong>🏙️ आदि थाना:</strong> ${data.aadi_thana}<br>
                    <strong>🛏️ रात्रि विश्राम हेतु:</strong> ${data.location}
                `;
            } else {
                document.getElementById('latestVihar').innerHTML = 'कोई जानकारी उपलब्ध नहीं है।';
            }
        });
}

document.getElementById('viharForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let aadi_thana = document.getElementById('aadi_thana').value;
    let location = document.getElementById('location').value;

    fetch('/api/vihar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ aadi_thana, location })
    })
    .then(res => res.json())
    .then(() => {
        document.getElementById('viharForm').reset();
        showToast('विहार सूचना जोड़ी गई!');
        fetchVihar();
        fetchLatestVihar();
    });
});

function openEditModal(id, aadi_thana, location) {
    document.getElementById('editViharId').value = id;
    document.getElementById('edit_aadi_thana').value = aadi_thana;
    document.getElementById('edit_location').value = location;
    editModal.show();
}

document.getElementById('editViharForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let id = document.getElementById('editViharId').value;
    let aadi_thana = document.getElementById('edit_aadi_thana').value;
    let location = document.getElementById('edit_location').value;

    fetch(`/api/vihar/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ aadi_thana, location })
    })
    .then(res => res.json())
    .then(() => {
        editModal.hide();
        showToast('विहार सूचना अपडेट हुई!');
        fetchVihar();
        fetchLatestVihar();
    });
});

function deleteVihar(id) {
    if (confirm("क्या आप वाकई इसे हटाना चाहते हैं?")) {
        fetch(`/api/vihar/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(() => {
            showToast('विहार सूचना हटाई गई!', 'error');
            fetchVihar();
            fetchLatestVihar();
        });
    }
}

// Initial load
fetchVihar();
fetchLatestVihar();
</script>
@endsection
