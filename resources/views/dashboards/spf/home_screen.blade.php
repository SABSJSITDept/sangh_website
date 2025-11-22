@extends('includes.layouts.spf')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-house-door-fill me-2"></i>SPF Dashboard</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill mb-4" id="spfTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="about-tab" data-bs-toggle="pill" data-bs-target="#about" type="button" role="tab">
                                <i class="bi bi-info-circle me-1"></i>About
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vision-tab" data-bs-toggle="pill" data-bs-target="#vision" type="button" role="tab">
                                <i class="bi bi-eye me-1"></i>Vision
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="mission-tab" data-bs-toggle="pill" data-bs-target="#mission" type="button" role="tab">
                                <i class="bi bi-target me-1"></i>Mission
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="spfTabContent">
                        <div class="tab-pane fade show active" id="about" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary mb-0"><i class="bi bi-info-circle-fill me-2"></i>About Section</h5>
                                <div id="aboutLoading" class="spinner-border spinner-border-sm text-primary" role="status" style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="aboutList" class="row g-3 flex-column"></div>
                        </div>
                        <div class="tab-pane fade" id="vision" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-success mb-0"><i class="bi bi-eye-fill me-2"></i>Vision Section</h5>
                                <div id="visionLoading" class="spinner-border spinner-border-sm text-success" role="status" style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="visionList" class="row g-3 flex-column"></div>
                        </div>
                        <div class="tab-pane fade" id="mission" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-warning mb-0"><i class="bi bi-target-fill me-2"></i>Mission Section</h5>
                                <div id="missionLoading" class="spinner-border spinner-border-sm text-warning" role="status" style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="missionList" class="row g-3 flex-column"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editModalTextarea" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="editModalTextarea" rows="8" placeholder="Enter your content here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveEditBtn">
                        <i class="bi bi-check-circle me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="mainToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-bold" id="mainToastBody">
                    <i class="bi bi-check-circle-fill me-2"></i>Success!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JS not loaded');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentEdit = { type: '', id: null };

    // Toast helper
    function showToast(message, isError = false) {
        const toastEl = document.getElementById('mainToast');
        const toastBody = document.getElementById('mainToastBody');
        toastBody.innerHTML = `<i class="bi ${isError ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill'} me-2"></i>${message}`;
        toastEl.classList.remove('text-bg-success', 'text-bg-danger');
        toastEl.classList.add(isError ? 'text-bg-danger' : 'text-bg-success');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    // Modal helpers
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const editModalTextarea = document.getElementById('editModalTextarea');
    const saveEditBtn = document.getElementById('saveEditBtn');

    // Loading helpers
    function showLoading(type) {
        document.getElementById(`${type}Loading`).style.display = 'block';
    }
    function hideLoading(type) {
        document.getElementById(`${type}Loading`).style.display = 'none';
    }

    // --- About CRUD (Edit Only, Modal, Cards) ---
    // Attach edit button click using event delegation for About
    document.getElementById('aboutList').addEventListener('click', function(e) {
        if (e.target.closest('.btn-outline-primary')) {
            const btn = e.target.closest('.btn-outline-primary');
            const card = btn.closest('.card');
            const p = card.querySelector('.about-content');
            const id = p.getAttribute('data-id');
            const content = encodeURIComponent(p.textContent);
            openEditModal('about', id, content);
        }
    });

    // Attach edit button click using event delegation for Vision
    document.getElementById('visionList').addEventListener('click', function(e) {
        if (e.target.closest('.btn-outline-success')) {
            const btn = e.target.closest('.btn-outline-success');
            const card = btn.closest('.card');
            const p = card.querySelector('.vision-content');
            const id = p.getAttribute('data-id');
            const content = encodeURIComponent(p.textContent);
            openEditModal('vision', id, content);
        }
    });

    // Attach edit button click using event delegation for Mission
    document.getElementById('missionList').addEventListener('click', function(e) {
        if (e.target.closest('.btn-outline-warning')) {
            const btn = e.target.closest('.btn-outline-warning');
            const card = btn.closest('.card');
            const p = card.querySelector('.mission-content');
            const id = p.getAttribute('data-id');
            const content = encodeURIComponent(p.textContent);
            openEditModal('mission', id, content);
        }
    });

    function fetchAbout() {
        showLoading('about');
        fetch('/api/spf-about')
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.data.length === 0) {
                    html = '<div class="col-12"><div class="alert alert-info text-center"><i class="bi bi-info-circle me-2"></i>No About content available.</div></div>';
                } else {
                    data.data.forEach(item => {
                        html += `
                            <div class="col-12">
                                <div class="card flex-row align-items-center h-100 shadow-sm border-primary">
                                    <div class="card-body d-flex flex-row align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="bi bi-info-circle-fill text-primary" style="font-size:2.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title text-primary mb-2">About Content</h6>
                                            <p class="card-text about-content mb-0" data-id="${item.id}">${item.content}</p>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex align-items-center">
                                        <button class="btn btn-outline-primary btn-sm ms-2" onclick="openEditModal('about', ${item.id}, '${encodeURIComponent(item.content)}')">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                document.getElementById('aboutList').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('aboutList').innerHTML = '<div class="col-12"><div class="alert alert-danger text-center"><i class="bi bi-exclamation-triangle me-2"></i>Failed to load About content.</div></div>';
            })
            .finally(() => hideLoading('about'));
    }

    function openEditModal(type, id, content) {
        currentEdit = { type, id };
        editModalTextarea.value = decodeURIComponent(content);
        editModal.show();
    }

    saveEditBtn.onclick = function() {
        const newContent = editModalTextarea.value.trim();
        if (!newContent) {
            showToast('Content cannot be empty!', true);
            return;
        }
        saveEditBtn.disabled = true;
        saveEditBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';

        let url = '', fetchFn = null;
        if (currentEdit.type === 'about') {
            url = `/api/spf-about/${currentEdit.id}`;
            fetchFn = fetchAbout;
        } else if (currentEdit.type === 'vision') {
            url = `/api/spf-vision/${currentEdit.id}`;
            fetchFn = fetchVision;
        } else if (currentEdit.type === 'mission') {
            url = `/api/spf-mission/${currentEdit.id}`;
            fetchFn = fetchMission;
        }
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ content: newContent })
        })
        .then(res => {
            if (!res.ok) throw new Error('Update failed');
            return res.json();
        })
        .then(() => {
            editModal.hide();
            showToast('Content updated successfully!');
            fetchFn();
        })
        .catch(() => {
            showToast('Update failed. Please try again.', true);
        })
        .finally(() => {
            saveEditBtn.disabled = false;
            saveEditBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Save Changes';
        });
    };

    fetchAbout();

    // --- Vision CRUD (Edit Only, Modal, Cards) ---
    function fetchVision() {
        showLoading('vision');
        fetch('/api/spf-vision')
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.data.length === 0) {
                    html = '<div class="col-12"><div class="alert alert-success text-center"><i class="bi bi-eye me-2"></i>No Vision content available.</div></div>';
                } else {
                    data.data.forEach(item => {
                        html += `
                            <div class="col-12">
                                <div class="card flex-row align-items-center h-100 shadow-sm border-success">
                                    <div class="card-body d-flex flex-row align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="bi bi-eye-fill text-success" style="font-size:2.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title text-success mb-2">Vision Content</h6>
                                            <p class="card-text vision-content mb-0" data-id="${item.id}">${item.content}</p>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex align-items-center">
                                        <button class="btn btn-outline-success btn-sm ms-2" onclick="openEditModal('vision', ${item.id}, '${encodeURIComponent(item.content)}')">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                document.getElementById('visionList').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('visionList').innerHTML = '<div class="col-12"><div class="alert alert-danger text-center"><i class="bi bi-exclamation-triangle me-2"></i>Failed to load Vision content.</div></div>';
            })
            .finally(() => hideLoading('vision'));
    }
    fetchVision();

    // --- Mission CRUD (Edit Only, Modal, Cards) ---
    function fetchMission() {
        showLoading('mission');
        fetch('/api/spf-mission')
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.data.length === 0) {
                    html = '<div class="col-12"><div class="alert alert-warning text-center"><i class="bi bi-target me-2"></i>No Mission content available.</div></div>';
                } else {
                    data.data.forEach(item => {
                        html += `
                            <div class="col-12">
                                <div class="card flex-row align-items-center h-100 shadow-sm border-warning">
                                    <div class="card-body d-flex flex-row align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="bi bi-target-fill text-warning" style="font-size:2.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title text-warning mb-2">Mission Content</h6>
                                            <p class="card-text mission-content mb-0" data-id="${item.id}">${item.content}</p>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 d-flex align-items-center">
                                        <button class="btn btn-outline-warning btn-sm ms-2" onclick="openEditModal('mission', ${item.id}, '${encodeURIComponent(item.content)}')">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                document.getElementById('missionList').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('missionList').innerHTML = '<div class="col-12"><div class="alert alert-danger text-center"><i class="bi bi-exclamation-triangle me-2"></i>Failed to load Mission content.</div></div>';
            })
            .finally(() => hideLoading('mission'));
    }
    fetchMission();
});
</script>
@endsection
