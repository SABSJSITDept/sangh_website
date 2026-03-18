@extends('includes.layouts.super_admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Registration Status</h3>
                <button class="btn btn-primary" id="addNewBtn">
                    <i class="fas fa-plus"></i> New Status
                </button>
            </div>
        </div>
    </div>

    <!-- Status Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="statusTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="statusBody">
                                <!-- Data will be populated via API -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Add/Edit Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="statusId">

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="enable">Enable</option>
                            <option value="disable">Disable</option>
                        </select>
                        <small class="text-danger" id="status-error"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE_URL = '/api/registration-statuses';

let currentStatusId = null;
let deleteStatusId = null;

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Set default headers for fetch requests
const getHeaders = () => {
    return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    };
};

// Load all statuses
function loadStatuses() {
    console.log('Loading statuses from:', API_BASE_URL);
    fetch(API_BASE_URL, {
        method: 'GET',
        headers: getHeaders()
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Statuses data:', data);
        if (data.success) {
            populateTable(data.data);
        } else {
            showAlert('Error loading statuses: ' + (data.message || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error loading statuses:', error);
        showAlert('Error loading statuses: ' + error.message, 'danger');
    });
}

// Populate table with data
function populateTable(statuses) {
    const tbody = document.getElementById('statusBody');
    tbody.innerHTML = '';

    if (statuses.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">No statuses found</td></tr>';
        return;
    }

    statuses.forEach(status => {
        const row = document.createElement('tr');
        const statusBadgeClass = status.status === 'enable' ? 'bg-success' : 'bg-danger';
        row.innerHTML = `
            <td>${status.id}</td>
            <td><span class="badge ${statusBadgeClass}">${status.status.charAt(0).toUpperCase() + status.status.slice(1)}</span></td>
            <td>
                <button class="btn btn-sm btn-info" onclick="editStatus(${status.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteStatus(${status.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Edit status
function editStatus(id) {
    fetch(`${API_BASE_URL}/${id}`, {
        method: 'GET',
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const status = data.data;
            document.getElementById('statusId').value = id;
            document.getElementById('status').value = status.status;
            currentStatusId = id;
            document.getElementById('statusModalLabel').innerText = 'Edit Status';
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        } else {
            showAlert('Error loading status', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error loading status', 'danger');
    });
}

// Clear form
function clearForm() {
    document.getElementById('statusForm').reset();
    document.getElementById('statusId').value = '';
    currentStatusId = null;
    document.getElementById('statusModalLabel').innerText = 'Add/Edit Status';
    document.getElementById('status-error').innerText = '';
}

// Show alert
function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    const container = document.querySelector('.container-fluid');
    const alertElement = document.createElement('div');
    alertElement.innerHTML = alertHtml;
    container.insertBefore(alertElement.firstElementChild, container.firstChild);

    // Auto dismiss after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => alert.remove());
    }, 5000);
}

// Delete status
function deleteStatus(id) {
    deleteStatusId = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');
    
    // Load statuses on page load
    loadStatuses();

    // Add new status button functionality
    const addNewBtn = document.getElementById('addNewBtn');
    if (addNewBtn) {
        console.log('Add button found');
        addNewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add button clicked');
            clearForm();
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        });
    } else {
        console.log('Add button NOT found');
    }

    // Save button listener
    const saveBtn = document.getElementById('saveBtn');
    if (saveBtn) {
        console.log('Save button found');
        saveBtn.addEventListener('click', function() {
            console.log('Save button clicked');
            const formData = new FormData(document.getElementById('statusForm'));
            const data = Object.fromEntries(formData);
            
            // Remove CSRF token from data if present
            delete data._token;

            const method = currentStatusId ? 'PUT' : 'POST';
            const url = currentStatusId ? `${API_BASE_URL}/${currentStatusId}` : API_BASE_URL;

            console.log('Sending', method, 'request to', url);

            fetch(url, {
                method: method,
                headers: getHeaders(),
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                    clearForm();
                    loadStatuses();
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorEl = document.getElementById(`${key}-error`);
                            if (errorEl) {
                                errorEl.innerText = data.errors[key].join(', ');
                            }
                        });
                    }
                    showAlert(data.message || 'Error saving status', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error saving status', 'danger');
            });
        });
    } else {
        console.log('Save button NOT found');
    }

    // Confirm delete button listener
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            fetch(`${API_BASE_URL}/${deleteStatusId}`, {
                method: 'DELETE',
                headers: getHeaders()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    loadStatuses();
                } else {
                    showAlert(data.message || 'Error deleting status', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting status', 'danger');
            });
        });
    }

    // Modal close listener
    const statusModal = document.getElementById('statusModal');
    if (statusModal) {
        statusModal.addEventListener('hidden.bs.modal', function() {
            clearForm();
        });
    }
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.btn-sm {
    margin: 2px;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}
</style>
@endsection
