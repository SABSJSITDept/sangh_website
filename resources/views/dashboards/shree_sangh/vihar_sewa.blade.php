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
                        <input type="text" class="form-control shadow-sm" id="aadi_thana" placeholder="जैसे - 9" required>
                    </div>
                    <div class="col-md-5">
                        <label for="location" class="form-label">स्थान (रात्रि विश्राम हेतु)</label>
                        <input type="text" class="form-control shadow-sm" id="location" placeholder="स्थान लिखें" required>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-success btn-lg shadow">जोड़ें / अपडेट करें</button>
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

{{-- 🔄 Script --}}
<script>
let editId = null;

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
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editVihar(${item.id}, '${item.aadi_thana}', '${item.location}')">✏️</button>
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

function resetForm() {
    editId = null;
    document.getElementById('viharForm').reset();
}

document.getElementById('viharForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let aadi_thana = document.getElementById('aadi_thana').value;
    let location = document.getElementById('location').value;

    let method = editId ? 'PUT' : 'POST';
    let url = '/api/vihar' + (editId ? `/${editId}` : '');

    fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ aadi_thana, location })
    })
    .then(res => res.json())
    .then(() => {
        resetForm();
        fetchVihar();
        fetchLatestVihar();
    });
});

function editVihar(id, aadi_thana, location) {
    editId = id;
    document.getElementById('aadi_thana').value = aadi_thana;
    document.getElementById('location').value = location;
    document.getElementById('aadi_thana').focus();
}

function deleteVihar(id) {
    if (confirm("क्या आप वाकई इसे हटाना चाहते हैं?")) {
        fetch(`/api/vihar/${id}`, { method: 'DELETE',
              headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    }
         })
            .then(() => {
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
