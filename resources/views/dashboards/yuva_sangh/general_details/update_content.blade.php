@extends('includes.layouts.yuva_sangh')

@section('content')
<!-- Bootstrap CSS CDN (included here as requested) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
    <h2 class="mb-4">Manage Yuva Content</h2>

    <!-- Content List -->
    <div id="contentList" class="list-group"></div>
</div>

<!-- Toast container: top-right, header ke niche -->
<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1100; margin-top:70px;">
    <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Message</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Bootstrap JS CDN (included here in case layout doesn't include it) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const contentList = document.getElementById('contentList');
    const toastEl = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    const toast = new bootstrap.Toast(toastEl);

    // CSRF token (blade helper)
    const csrfToken = '{{ csrf_token() }}';

    const headers = {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
    };

    // Adjust this if your header is taller/shorter
    const toastMarginTopPx = 70; // used in inline style already; change if needed

    // Fetch all content
    async function fetchContent() {
        try {
            const res = await fetch('/api/yuva-content');
            if (!res.ok) throw new Error('Failed to fetch content');
            const data = await res.json();

            contentList.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'list-group-item text-muted';
                empty.textContent = 'No content found.';
                contentList.appendChild(empty);
                return;
            }

            data.forEach(item => contentList.appendChild(createListItem(item)));
        } catch (err) {
            console.error(err);
            showToast('Error loading content', 'danger');
        }
    }

    // Create list item element safely
    function createListItem(item) {
        const wrapper = document.createElement('div');
        wrapper.className = 'list-group-item';

        const row = document.createElement('div');
        row.className = 'd-flex justify-content-between align-items-start gap-2';

        const textWrapper = document.createElement('div');
        textWrapper.className = 'flex-grow-1';

        const contentSpan = document.createElement('div');
        contentSpan.className = 'content-text';
        contentSpan.textContent = item.content || '';

        textWrapper.appendChild(contentSpan);

        const actions = document.createElement('div');

        const editBtn = document.createElement('button');
        editBtn.className = 'btn btn-sm btn-warning';
        editBtn.type = 'button';
        editBtn.textContent = 'Edit';
        editBtn.addEventListener('click', () => enterEditMode(wrapper, item.id, contentSpan));

        actions.appendChild(editBtn);

        row.appendChild(textWrapper);
        row.appendChild(actions);
        wrapper.appendChild(row);

        return wrapper;
    }

    // Enter inline edit mode
    function enterEditMode(listItem, id, contentSpan) {
        // Prevent multiple editors on same item
        if (listItem.querySelector('textarea')) return;

        const originalText = contentSpan.textContent;

        // Create textarea
        const textarea = document.createElement('textarea');
        textarea.className = 'form-control mb-2';
        textarea.value = originalText;
        textarea.rows = 3;
        textarea.maxLength = 5000; // optional limit

        // Replace contentSpan with textarea
        contentSpan.replaceWith(textarea);

        // Create Save and Cancel buttons
        const btnRow = document.createElement('div');
        btnRow.className = 'mt-2';

        const saveBtn = document.createElement('button');
        saveBtn.className = 'btn btn-sm btn-success me-2';
        saveBtn.type = 'button';
        saveBtn.textContent = 'Save';

        const cancelBtn = document.createElement('button');
        cancelBtn.className = 'btn btn-sm btn-secondary';
        cancelBtn.type = 'button';
        cancelBtn.textContent = 'Cancel';

        btnRow.appendChild(saveBtn);
        btnRow.appendChild(cancelBtn);

        textarea.after(btnRow);

        // Focus and move cursor to end
        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd = textarea.value.length;

        // Save handler
        saveBtn.addEventListener('click', async () => {
            const newContent = textarea.value.trim();
            if (!newContent) {
                showToast('Content cannot be empty', 'warning');
                textarea.focus();
                return;
            }

            // optional: disable buttons to avoid duplicate submits
            saveBtn.disabled = true;
            cancelBtn.disabled = true;

            try {
                const res = await fetch(`/api/yuva-content/${id}`, {
                    method: 'PUT',
                    headers,
                    body: JSON.stringify({ content: newContent })
                });

                const data = await res.json();

                if (!res.ok) {
                    // validation errors or other errors
                    const msg = data?.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Save failed');
                    showToast(msg, 'danger');
                    saveBtn.disabled = false;
                    cancelBtn.disabled = false;
                    return;
                }

                // Replace textarea with updated text (prefer server returned content if present)
                const updatedText = data?.content?.content ?? newContent;
                const updatedSpan = document.createElement('div');
                updatedSpan.className = 'content-text';
                updatedSpan.textContent = updatedText;

                btnRow.remove();
                textarea.replaceWith(updatedSpan);

                showToast(data.message || 'Updated successfully', 'success');

            } catch (err) {
                console.error(err);
                showToast('Error saving content', 'danger');
                saveBtn.disabled = false;
                cancelBtn.disabled = false;
            }
        });

        // Cancel handler
        cancelBtn.addEventListener('click', () => {
            const restoredSpan = document.createElement('div');
            restoredSpan.className = 'content-text';
            restoredSpan.textContent = originalText;

            btnRow.remove();
            textarea.replaceWith(restoredSpan);
        });
    }

    // Show toast at top-right below header
    function showToast(message, type = 'primary') {
        // set message
        toastMessage.textContent = message;

        // normalize type to allowed bootstrap bg types (primary, success, danger, warning, info, secondary, dark, light)
        const allowed = ['primary','success','danger','warning','info','secondary','dark','light'];
        if (!allowed.includes(type)) type = 'primary';

        // update classes: keep "toast" and "align-items-center" and "border-0"
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        // show it
        toast.show();
    }

    // initial load
    fetchContent();
});
</script>
@endsection
