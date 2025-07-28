@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">🧠 आज का विचार</h2>

    <div class="row">
        {{-- Thought Form --}}
        <div class="col-md-7">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">नया विचार जोड़ें</div>
                <div class="card-body">
                    <form id="thoughtForm">
                        <div class="mb-3">
                            <label for="thought" class="form-label">विचार <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="thought" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">दिनांक</label>
                            <input type="date" class="form-control" id="date" value="{{ date('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-success w-100">➕ सबमिट करें</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Latest Thought --}}
        <div class="col-md-5">
            <div class="card bg-light shadow-sm">
                <div class="card-header bg-primary text-white">📌 आज का विचार</div>
                <div class="card-body" id="latestThought">
                    <p>🔄 लोड हो रहा है...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Thought List --}}
    <div class="card shadow-sm">
        <div class="card-header">📋 पिछले विचार</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>दिनांक</th>
                        <th>विचार</th>
                        <th class="text-center">कार्य</th>
                    </tr>
                </thead>
                <tbody id="thoughtTableBody">
                    <tr><td colspan="3">विचार लोड हो रहे हैं...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-center" id="paginationLinks"></div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editThoughtModal" tabindex="-1" aria-labelledby="editThoughtModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editThoughtForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">✏️ विचार संपादित करें</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id">
                    <div class="mb-3">
                        <label for="edit-thought" class="form-label">विचार</label>
                        <textarea class="form-control" id="edit-thought" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-date" class="form-label">दिनांक</label>
                        <input type="date" class="form-control" id="edit-date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">बंद करें</button>
                    <button type="submit" class="btn btn-primary">💾 अपडेट करें</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
document.addEventListener('DOMContentLoaded', async function () {
    const listUrl = "{{ route('thoughts.index') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const thoughtTableBody = document.getElementById('thoughtTableBody');
    const paginationLinks = document.getElementById('paginationLinks');
    const latestThoughtDiv = document.getElementById('latestThought');

    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });

    function loadThoughts(page = 1) {
        fetch(`${listUrl}?page=${page}`)
            .then(response => response.json())
            .then(data => {
                thoughtTableBody.innerHTML = '';
                if (data.data.length === 0) {
                    thoughtTableBody.innerHTML = `<tr><td colspan="3">कोई विचार उपलब्ध नहीं है।</td></tr>`;
                } else {
                    data.data.forEach(item => {
                        thoughtTableBody.innerHTML += `
                            <tr>
                                <td>${item.date ?? '—'}</td>
                                <td>${item.thought}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning me-2" onclick="editThought(${item.id}, \`${item.thought}\`, '${item.date ?? ''}')">✏️</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteThought(${item.id})">🗑️</button>
                                </td>
                            </tr>`;
                    });
                }
                paginationLinks.innerHTML = renderPagination(data);
            })
            .catch(() => {
                thoughtTableBody.innerHTML = `<tr><td colspan="3">❌ विचार लोड करने में समस्या हुई।</td></tr>`;
            });
    }

    function loadLatestThought() {
        fetch('/api/latest-thought')
            .then(res => res.json())
            .then(data => {
                if (data && data.thought) {
                    latestThoughtDiv.innerHTML = `
                        <p><strong>🗓️ दिनांक:</strong> ${data.date ?? '—'}</p>
                        <p><strong>💡 विचार:</strong><br>${data.thought}</p>
                    `;
                } else {
                    latestThoughtDiv.innerHTML = `<p>कोई हालिया विचार नहीं मिला।</p>`;
                }
            })
            .catch(() => {
                latestThoughtDiv.innerHTML = `<p>❌ विचार लोड करने में समस्या हुई।</p>`;
            });
    }

    function renderPagination(data) {
        let buttons = '';
        for (let i = 1; i <= data.last_page; i++) {
            buttons += `<button class="btn btn-sm ${i === data.current_page ? 'btn-primary' : 'btn-outline-primary'} m-1" onclick="loadThoughts(${i})">${i}</button>`;
        }
        return buttons;
    }

    window.editThought = function(id, thought, date) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-thought').value = thought;
        document.getElementById('edit-date').value = date;
        new bootstrap.Modal(document.getElementById('editThoughtModal')).show();
    };

    window.deleteThought = function(id) {
        if (confirm("क्या आप यह विचार हटाना चाहते हैं?")) {
            fetch(`/api/thoughts/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'include'
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                loadThoughts();
                loadLatestThought();
            })
            .catch(() => alert("❌ हटाने में समस्या हुई।"));
        }
    };

    document.getElementById('editThoughtForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const updatedThought = document.getElementById('edit-thought').value.trim();
        const updatedDate = document.getElementById('edit-date').value;

        if (!updatedThought) {
            alert("कृपया विचार दर्ज करें।");
            return;
        }

        fetch(`/api/thoughts/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include',
            body: JSON.stringify({ thought: updatedThought, date: updatedDate })
        })
        .then(res => res.json())
        .then(() => {
            alert("✅ विचार अपडेट हो गया!");
            bootstrap.Modal.getInstance(document.getElementById('editThoughtModal')).hide();
            loadThoughts();
            loadLatestThought();
        });
    });

    document.getElementById('thoughtForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const thought = document.getElementById('thought').value.trim();
        const date = document.getElementById('date').value;

        if (!thought) {
            alert("कृपया विचार दर्ज करें।");
            return;
        }

        fetch('/api/thoughts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include',
            body: JSON.stringify({ thought, date })
        })
        .then(res => res.json())
        .then(() => {
            alert("✅ विचार जोड़ा गया!");
            document.getElementById('thoughtForm').reset();
            document.getElementById('date').value = '{{ date('Y-m-d') }}';
            loadThoughts();
            loadLatestThought();
        });
    });

    loadThoughts();
    loadLatestThought();
});
</script>
@endsection
