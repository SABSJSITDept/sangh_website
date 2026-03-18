@extends('includes.layouts.super_admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3>App Registrations</h3>
                <button class="btn btn-primary" id="addNewBtn">
                    <i class="fas fa-plus"></i> New Registration
                </button>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="registrationTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Member ID</th>
                                    <th>Family ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="registrationBody">
                                <!-- Data will be populated via API -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrationModalLabel">Add/Edit Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registrationForm">
                    @csrf
                    <input type="hidden" id="registrationId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <small class="text-danger" id="first_name-error"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <small class="text-danger" id="last_name-error"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required>
                            <small class="text-danger" id="mobile-error"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email_address" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email_address" name="email_address">
                            <small class="text-danger" id="email_address-error"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="marital_status" class="form-label">Marital Status</label>
                            <select class="form-select" id="marital_status" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birth_day" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="birth_day" name="birth_day">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="relation_id" class="form-label">Relation</label>
                            <select class="form-select" id="relation_id" name="relation_id">
                                <option value="">Select Relation</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guardian_name" class="form-label">Guardian Name</label>
                            <input type="text" class="form-control" id="guardian_name" name="guardian_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="education" class="form-label">Education</label>
                            <select class="form-select" id="education" name="education">
                                <option value="">Select Education</option>
                                <option value="Less than SSC">Less than SSC</option>
                                <option value="SSC">SSC</option>
                                <option value="HSC">HSC</option>
                                <option value="CA">CA</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Engineer">Engineer</option>
                                <option value="Software Engineer">Software Engineer</option>
                                <option value="LLB">LLB</option>
                                <option value="MBA">MBA</option>
                                <option value="PHD">PHD</option>
                                <option value="Graduate">Graduate</option>
                                <option value="Post Graduate">Post Graduate</option>
                                <option value="Professional Degree">Professional Degree</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="occupation" class="form-label">Occupation</label>
                            <select class="form-select" id="occupation" name="occupation">
                                <option value="">व्यवसाय का चयन करें</option>
                                <option value="Business">Business</option>
                                <option value="Industrialist">Industrialist</option>
                                <option value="Housewife">Housewife</option>
                                <option value="Professional">Professional</option>
                                <option value="Service">Service</option>
                                <option value="Student">Student</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rel_faith" class="form-label">Religious Faith</label>
                            <select class="form-select" id="rel_faith" name="rel_faith">
                                <option value="">Select Religious Faith</option>
                                <option value="Sadhumargi">साधुमार्गी/Sadhumargi</option>
                                <option value="Terapanth">तेरापंथ/Terapanth</option>
                                <option value="Sthanakvasi">स्थानकवासी/Sthanakvasi</option>
                                <option value="Murtipujak">मूर्तिपूजक/Murtipujak</option>
                                <option value="Shraman Sangh">श्रमण संघ/Shraman Sangh</option>
                                <option value="Gyan Gacha">ज्ञान गच्छ/Gyan Gacha</option>
                                <option value="Other">अन्य/Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                            <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alternate_number" class="form-label">Alternate Number</label>
                            <input type="text" class="form-control" id="alternate_number" name="alternate_number">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address_type" class="form-label">Address Type</label>
                            <select class="form-select" id="address_type" name="address_type">
                                <option value="">Select Address Type</option>
                                <option value="Residential">Residential</option>
                                <option value="Factory">Factory</option>
                                <option value="Office">Office</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address2" class="form-label">Address 2</label>
                            <input type="text" class="form-control" id="address2" name="address2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="post" class="form-label">Post</label>
                            <input type="text" class="form-control" id="post" name="post">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country">
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state" name="state" disabled>
                                <option value="">Select State</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select" id="city" name="city" disabled>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="district" class="form-label">District</label>
                            <input type="text" class="form-control" id="district" name="district">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="adhar_name" class="form-label">Aadhaar Name</label>
                            <input type="text" class="form-control" id="adhar_name" name="adhar_name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="adhar1" class="form-label">Aadhaar 1</label>
                            <input type="text" class="form-control" id="adhar1" name="adhar1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="adhar2" class="form-label">Aadhaar 2</label>
                            <input type="text" class="form-control" id="adhar2" name="adhar2">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="adhar3" class="form-label">Aadhaar 3</label>
                            <input type="text" class="form-control" id="adhar3" name="adhar3">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="adharfatherName" class="form-label">Aadhaar Father Name</label>
                            <input type="text" class="form-control" id="adharfatherName" name="adharfatherName">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="app_status" class="form-label">App Status</label>
                            <select class="form-select" id="app_status" name="app_status">
                                <option value="0">Invalid</option>
                                <option value="1">Valid</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save Registration</button>
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
                Are you sure you want to delete this registration?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE_URL = '/api/app-registrations';
const RELATIONS_API = 'https://mrm.sadhumargi.org/api/relations';
const COUNTRY_STATE_API = 'https://api.countrystatecity.in/v1/countries';
const COUNTRY_STATE_API_KEY = 'S2dBYnJldWtmRFM4U2VUdG9Fd0hiRXp2RjhpTm81YlhVVThiWEdiTA==';

let currentRegistrationId = null;
let deleteRegistrationId = null;
let countriesData = {}; // Store countries data for quick access

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

// Load relations from external API
function loadRelations() {
    console.log('Loading relations from:', RELATIONS_API);
    fetch(RELATIONS_API)
    .then(response => response.json())
    .then(data => {
        console.log('Relations data:', data);
        const relationSelect = document.getElementById('relation_id');
        relationSelect.innerHTML = '<option value="">Select Relation</option>';
        
        if (Array.isArray(data)) {
            data.forEach(relation => {
                const option = document.createElement('option');
                option.value = relation.id;
                option.textContent = relation.relation_utf8 || relation.relation || relation.id;
                relationSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading relations:', error);
    });
}

// Load countries
function loadCountries() {
    console.log('Loading countries...');
    fetch(COUNTRY_STATE_API, {
        headers: {
            'X-CSCAPI-KEY': COUNTRY_STATE_API_KEY
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Countries data:', data);
        const countrySelect = document.getElementById('country');
        countrySelect.innerHTML = '<option value="">Select Country</option>';
        
        if (Array.isArray(data)) {
            data.forEach(country => {
                countriesData[country.iso2] = country;
                const option = document.createElement('option');
                option.value = country.iso2;
                option.textContent = country.name;
                countrySelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading countries:', error);
    });
}

// Load states when country changes
function loadStates(iso2) {
    if (!iso2) {
        document.getElementById('state').innerHTML = '<option value="">Select State</option>';
        document.getElementById('state').disabled = true;
        document.getElementById('city').innerHTML = '<option value="">Select City</option>';
        document.getElementById('city').disabled = true;
        return;
    }

    console.log('Loading states for country:', iso2);
    fetch(`${COUNTRY_STATE_API}/${iso2}/states`, {
        headers: {
            'X-CSCAPI-KEY': COUNTRY_STATE_API_KEY
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('States data:', data);
        const stateSelect = document.getElementById('state');
        stateSelect.innerHTML = '<option value="">Select State</option>';
        stateSelect.disabled = false;
        
        if (Array.isArray(data)) {
            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state.iso2;
                option.textContent = state.name;
                stateSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading states:', error);
    });
}

// Load cities when state changes
function loadCities(iso2, stateIso2) {
    if (!iso2 || !stateIso2) {
        document.getElementById('city').innerHTML = '<option value="">Select City</option>';
        document.getElementById('city').disabled = true;
        return;
    }

    console.log('Loading cities for:', iso2, stateIso2);
    fetch(`${COUNTRY_STATE_API}/${iso2}/states/${stateIso2}/cities`, {
        headers: {
            'X-CSCAPI-KEY': COUNTRY_STATE_API_KEY
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cities data:', data);
        const citySelect = document.getElementById('city');
        citySelect.innerHTML = '<option value="">Select City</option>';
        citySelect.disabled = false;
        
        if (Array.isArray(data)) {
            data.forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading cities:', error);
    });
}

// Load all registrations
function loadRegistrations() {
    console.log('Loading registrations from:', API_BASE_URL);
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
        console.log('Registrations data:', data);
        if (data.success) {
            populateTable(data.data);
        } else {
            showAlert('Error loading registrations: ' + (data.message || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error loading registrations:', error);
        showAlert('Error loading registrations: ' + error.message, 'danger');
    });
}

// Populate table with data
function populateTable(registrations) {
    const tbody = document.getElementById('registrationBody');
    tbody.innerHTML = '';

    if (registrations.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">No registrations found</td></tr>';
        return;
    }

    registrations.forEach(registration => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${registration.id}</td>
            <td>${registration.member_id || 'N/A'}</td>
            <td>${registration.family_id || 'N/A'}</td>
            <td>${registration.first_name}</td>
            <td>${registration.last_name}</td>
            <td>${registration.mobile || 'N/A'}</td>
            <td>${registration.email_address || 'N/A'}</td>
            <td><span class="badge ${registration.app_status == 1 ? 'bg-success' : 'bg-danger'}">${registration.app_status == 1 ? 'Valid' : 'Invalid'}</span></td>
            <td>
                <button class="btn btn-sm btn-info" onclick="editRegistration(${registration.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteRegistration(${registration.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Edit registration
function editRegistration(id) {
    fetch(`${API_BASE_URL}/${id}`, {
        method: 'GET',
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const registration = data.data;
            populateForm(registration);
            currentRegistrationId = id;
            document.getElementById('registrationModalLabel').innerText = 'Edit Registration';
            const modal = new bootstrap.Modal(document.getElementById('registrationModal'));
            modal.show();
        } else {
            showAlert('Error loading registration', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error loading registration', 'danger');
    });
}

// Populate form with data
function populateForm(registration) {
    Object.keys(registration).forEach(key => {
        const field = document.getElementById(key);
        if (field) {
            if (key === 'country' && registration[key]) {
                // Set country and trigger state loading
                field.value = registration[key];
                loadStates(registration[key]);
            } else if (key === 'state' && registration[key]) {
                // Set state (after country is set) and trigger city loading
                setTimeout(() => {
                    field.value = registration[key];
                    const countryValue = document.getElementById('country').value;
                    if (countryValue) {
                        loadCities(countryValue, registration[key]);
                    }
                }, 500);
            } else {
                field.value = registration[key] || '';
            }
        }
    });
}

// Clear form
function clearForm() {
    document.getElementById('registrationForm').reset();
    document.getElementById('registrationId').value = '';
    currentRegistrationId = null;
    document.getElementById('registrationModalLabel').innerText = 'Add/Edit Registration';
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

// Delete registration
function deleteRegistration(id) {
    deleteRegistrationId = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Save registration
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');
    
    // Load registrations on page load
    loadRegistrations();
    
    // Load relations and countries
    loadRelations();
    loadCountries();

    // Country dropdown change event
    const countrySelect = document.getElementById('country');
    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            loadStates(this.value);
        });
    }

    // State dropdown change event
    const stateSelect = document.getElementById('state');
    if (stateSelect) {
        stateSelect.addEventListener('change', function() {
            const countryValue = document.getElementById('country').value;
            loadCities(countryValue, this.value);
        });
    }

    // Add new registration button functionality
    const addNewBtn = document.getElementById('addNewBtn');
    if (addNewBtn) {
        console.log('Add button found');
        addNewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add button clicked');
            clearForm();
            const modal = new bootstrap.Modal(document.getElementById('registrationModal'));
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
            const formData = new FormData(document.getElementById('registrationForm'));
            const data = Object.fromEntries(formData);
            
            // Remove CSRF token from data if present
            delete data._token;

            const method = currentRegistrationId ? 'PUT' : 'POST';
            const url = currentRegistrationId ? `${API_BASE_URL}/${currentRegistrationId}` : API_BASE_URL;

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
                    bootstrap.Modal.getInstance(document.getElementById('registrationModal')).hide();
                    clearForm();
                    loadRegistrations();
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorEl = document.getElementById(`${key}-error`);
                            if (errorEl) {
                                errorEl.innerText = data.errors[key].join(', ');
                            }
                        });
                    }
                    showAlert(data.message || 'Error saving registration', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error saving registration', 'danger');
            });
        });
    } else {
        console.log('Save button NOT found');
    }

    // Confirm delete button listener
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            fetch(`${API_BASE_URL}/${deleteRegistrationId}`, {
                method: 'DELETE',
                headers: getHeaders()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    loadRegistrations();
                } else {
                    showAlert(data.message || 'Error deleting registration', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting registration', 'danger');
            });
        });
    }

    // Modal close listener
    const registrationModal = document.getElementById('registrationModal');
    if (registrationModal) {
        registrationModal.addEventListener('hidden.bs.modal', function() {
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
