@extends('includes.layouts.yuva_sangh')

@section('title', 'Manage Yuva Content')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold outfit-font mb-1">Manage Yuva Content</h2>
                <p class="text-muted small mb-0">Edit and update general content and descriptions for the Yuva Sangh section.</p>
            </div>
            <div class="bg-primary-subtle p-3 rounded-4">
                <i class="bi bi-file-earmark-text text-primary fs-3"></i>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold outfit-font mb-0">Editable Content Sections</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div id="contentList" class="d-flex flex-column gap-3">
                        <!-- Content loaded via AJAX -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Professional Toast notification handled via Layout or local script -->
<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1100; margin-top:70px;">
    <div id="liveToast" class="toast align-items-center text-white border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 10px;">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Message</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const contentList = document.getElementById('contentList');
    const toastEl = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    const toast = new bootstrap.Toast(toastEl);
    const csrfToken = '{{ csrf_token() }}';

    const headers = {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
    };

    async function fetchContent() {
        try {
            const res = await fetch('/api/yuva-content');
            if (!res.ok) throw new Error('Failed to fetch content');
            const data = await res.json();

            contentList.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                contentList.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x display-1 text-light mb-3 d-block"></i>
                        <p class="text-muted">No content found in the database.</p>
                    </div>`;
                return;
            }

            data.forEach(item => contentList.appendChild(createListItem(item)));
        } catch (err) {
            console.error(err);
            showToast('Error loading content', 'danger');
        }
    }

    function createListItem(item) {
        const wrapper = document.createElement('div');
        wrapper.className = 'content-item p-4 border rounded-4 transition-all bg-white';
        wrapper.style.borderStyle = 'solid';

        const row = document.createElement('div');
        row.className = 'd-flex justify-content-between align-items-start gap-4';

        const textWrapper = document.createElement('div');
        textWrapper.className = 'flex-grow-1';

        const label = document.createElement('div');
        label.className = 'small text-uppercase fw-bold text-primary mb-2 opacity-75';
        label.textContent = `Content Block #${item.id}`;

        const contentSpan = document.createElement('div');
        contentSpan.className = 'content-text text-dark-emphasis';
        contentSpan.style.lineHeight = '1.6';
        contentSpan.textContent = item.content || '';

        textWrapper.appendChild(label);
        textWrapper.appendChild(contentSpan);

        const actions = document.createElement('div');
        actions.className = 'ms-auto';

        const editBtn = document.createElement('button');
        editBtn.className = 'btn btn-sm btn-light border px-3 py-2 rounded-3 fw-bold';
        editBtn.innerHTML = '<i class="bi bi-pencil-square me-2 text-primary"></i>Edit';
        editBtn.addEventListener('click', () => enterEditMode(wrapper, item.id, contentSpan, actions));

        actions.appendChild(editBtn);

        row.appendChild(textWrapper);
        row.appendChild(actions);
        wrapper.appendChild(row);

        return wrapper;
    }

    function enterEditMode(listItem, id, contentSpan, actionWrapper) {
        if (listItem.querySelector('textarea')) return;

        const originalText = contentSpan.textContent;
        const originalActions = actionWrapper.innerHTML;
        actionWrapper.innerHTML = ''; // Hide edit button

        const textarea = document.createElement('textarea');
        textarea.className = 'form-control rounded-3 border-primary shadow-sm mt-2';
        textarea.value = originalText;
        textarea.rows = 5;
        textarea.maxLength = 5000;

        contentSpan.replaceWith(textarea);

        const btnRow = document.createElement('div');
        btnRow.className = 'mt-3 d-flex gap-2';

        const saveBtn = document.createElement('button');
        saveBtn.className = 'btn btn-primary btn-sm px-4 rounded-pill fw-bold';
        saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Save Changes';

        const cancelBtn = document.createElement('button');
        cancelBtn.className = 'btn btn-light btn-sm px-4 rounded-pill fw-bold border';
        cancelBtn.textContent = 'Cancel';

        btnRow.appendChild(saveBtn);
        btnRow.appendChild(cancelBtn);
        textarea.after(btnRow);

        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd = textarea.value.length;

        saveBtn.addEventListener('click', async () => {
            const newContent = textarea.value.trim();
            if (!newContent) {
                showToast('Content cannot be empty', 'warning');
                return;
            }

            saveBtn.disabled = true;
            cancelBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

            try {
                const res = await fetch(`/api/yuva-content/${id}`, {
                    method: 'PUT',
                    headers,
                    body: JSON.stringify({ content: newContent })
                });

                const data = await res.json();
                if (!res.ok) throw data;

                const updatedSpan = document.createElement('div');
                updatedSpan.className = 'content-text text-dark-emphasis';
                updatedSpan.textContent = data?.content?.content ?? newContent;

                btnRow.remove();
                textarea.replaceWith(updatedSpan);
                actionWrapper.innerHTML = originalActions;
                // Re-bind click event to new button
                actionWrapper.querySelector('button').addEventListener('click', () => enterEditMode(listItem, id, updatedSpan, actionWrapper));

                showToast(data.message || 'Content updated!', 'success');
            } catch (err) {
                showToast(err.message || 'Error saving changes', 'danger');
                saveBtn.disabled = false;
                cancelBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Save Changes';
            }
        });

        cancelBtn.addEventListener('click', () => {
            const restoredSpan = document.createElement('div');
            restoredSpan.className = 'content-text text-dark-emphasis';
            restoredSpan.textContent = originalText;
            btnRow.remove();
            textarea.replaceWith(restoredSpan);
            actionWrapper.innerHTML = originalActions;
            actionWrapper.querySelector('button').addEventListener('click', () => enterEditMode(listItem, id, restoredSpan, actionWrapper));
        });
    }

    function showToast(message, type = 'primary') {
        toastMessage.textContent = message;
        toastEl.className = `toast align-items-center text-bg-${type} border-0 shadow-lg`;
        toast.show();
    }

    fetchContent();
});
</script>

<style>
    .content-item:hover {
        border-color: #6366f1 !important;
        background-color: #f8fafc !important;
        transform: scale(1.005);
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .text-bg-success { background-color: #10b981 !important; }
    .text-bg-danger { background-color: #ef4444 !important; }
    .text-bg-warning { background-color: #f59e0b !important; }
    .text-bg-info { background-color: #3b82f6 !important; }
</style>
@endsection
