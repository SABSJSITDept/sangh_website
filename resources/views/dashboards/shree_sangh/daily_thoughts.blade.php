@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4">
    <h2 class="mb-4">🧠 Daily Thoughts</h2>

    <div class="row">
        <!-- 🔵 Left: Latest Thought -->
        <div class="col-md-6 mb-3">
            <h5>🆕 Latest Thought</h5>
            <div id="latestThoughtBox" class="alert alert-info">Loading latest...</div>
        </div>

        <!-- 🟢 Right: Form -->
        <div class="col-md-6 mb-3">
            <form id="thoughtForm">
                <div class="mb-3">
                    <label for="thought" class="form-label">New Thought</label>
                    <textarea class="form-control" id="thought" name="thought" required></textarea>
                </div>
                <input type="hidden" id="thoughtId">
                <button type="submit" class="btn btn-success">Save Thought</button>
            </form>
        </div>
    </div>

    <hr>

    <!-- 📃 All Thoughts -->
    <div class="mt-4">
        <h4>📜 All Thoughts</h4>
        <ul class="list-group" id="thoughtList"></ul>
        <div id="pagination" class="mt-3"></div>
    </div>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').content;

function showToast(icon, title) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: 2500
    });
}

function formatDate(dateString) {
    const options = { day: '2-digit', month: 'short', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-GB', options);
}

function fetchLatestThought() {
    fetch('/api/latest-thought')
        .then(res => res.json())
        .then(latest => {
            const box = document.getElementById('latestThoughtBox');
            if (latest && latest.thought) {
                const date = formatDate(latest.created_at);
                box.innerHTML = `<strong>${latest.thought}</strong><br><small class="text-muted">${date}</small>`;
            } else {
                box.innerHTML = `<em>No thought found.</em>`;
            }
        });
}

function fetchThoughts(page = 1) {
    fetch(`/api/thoughts?page=${page}`)
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('thoughtList');
            list.innerHTML = '';

            data.data.forEach(thought => {
                const date = formatDate(thought.created_at);
                list.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <div>${thought.thought}</div>
                            <small class="text-muted">${date}</small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary me-2" onclick="editThought(${thought.id}, \`${thought.thought}\`)">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteThought(${thought.id})">Delete</button>
                        </div>
                    </li>
                `;
            });

            renderPagination(data);
        });
}

function renderPagination(data) {
    let html = '';
    const pagination = document.getElementById('pagination');

    if (data.last_page > 1) {
        html += `<nav><ul class="pagination justify-content-center">`;

        if (data.prev_page_url) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="fetchThoughts(${data.current_page - 1})">Previous</a></li>`;
        }

        for (let i = 1; i <= data.last_page; i++) {
            html += `<li class="page-item ${i === data.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="fetchThoughts(${i})">${i}</a>
                    </li>`;
        }

        if (data.next_page_url) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="fetchThoughts(${data.current_page + 1})">Next</a></li>`;
        }

        html += `</ul></nav>`;
    }

    pagination.innerHTML = html;
}

function editThought(id, text) {
    document.getElementById('thought').value = text;
    document.getElementById('thoughtId').value = id;
}

function deleteThought(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this thought?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/thoughts/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => {
                showToast('success', 'Deleted successfully!');
                fetchThoughts();
                fetchLatestThought();
            }).catch(() => {
                showToast('error', 'Deletion failed!');
            });
        }
    });
}

document.getElementById('thoughtForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('thoughtId').value;
    const thought = document.getElementById('thought').value.trim();

    if (!thought) {
        showToast('error', 'Thought cannot be empty!');
        return;
    }

    const method = id ? 'PUT' : 'POST';
    const url = id ? `/api/thoughts/${id}` : '/api/thoughts';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ thought })
    })
    .then(res => res.json())
    .then(() => {
        showToast('success', id ? 'Updated successfully!' : 'Added successfully!');
        this.reset();
        document.getElementById('thoughtId').value = '';
        fetchThoughts();
        fetchLatestThought();
    })
    .catch(() => {
        showToast('error', 'Something went wrong!');
    });
});

// Initial load
fetchLatestThought();
fetchThoughts();
</script>
@endsection
