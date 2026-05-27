@extends('includes.layouts.spf')

@section('title', 'SPF Social & Contact Links')
@section('page-title', 'Social & Contact Links')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1e2347 0%, #2d3561 100%);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-link-45deg me-2"></i>Social & Contact Links</h4>
                            <p class="text-white-50 mb-0">Manage website links, registration portals, social media pages, and contact information for SPF.</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-globe2" style="font-size: 3.5rem; opacity: 0.15;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Links Form -->
    <form id="linksForm" class="row g-4">
        <!-- Contact Details Column -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0"><i class="bi bi-person-rolodex me-2"></i>Contact Details</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Mobile Numbers -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">Mobile Numbers</label>
                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 fw-semibold" onclick="createInputRow('mobile-container', 'mobile_number[]', 'bi-telephone-fill', 'text', 'e.g. +91 9876543210')">
                                <i class="bi bi-plus-circle me-1"></i>Add Number
                            </button>
                        </div>
                        <div id="mobile-container" class="d-flex flex-column">
                            <!-- Dynamic rows -->
                        </div>
                        <div class="form-text mt-1">Contact mobile number(s) for queries.</div>
                    </div>

                    <!-- Email Addresses -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">Email Addresses</label>
                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 fw-semibold" onclick="createInputRow('email-container', 'email[]', 'bi-envelope-fill', 'email', 'e.g. contact@spf.org')">
                                <i class="bi bi-plus-circle me-1"></i>Add Email
                            </button>
                        </div>
                        <div id="email-container" class="d-flex flex-column">
                            <!-- Dynamic rows -->
                        </div>
                        <div class="form-text mt-1">Official contact email address(es).</div>
                    </div>

                    <!-- WhatsApp Numbers -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">WhatsApp Numbers</label>
                            <button type="button" class="btn btn-sm btn-outline-success py-1 px-2 fw-semibold" onclick="createInputRow('whatsapp-container', 'whatsapp_number[]', 'bi-whatsapp', 'text', 'e.g. +91 9876543210')">
                                <i class="bi bi-plus-circle me-1"></i>Add WhatsApp
                            </button>
                        </div>
                        <div id="whatsapp-container" class="d-flex flex-column">
                            <!-- Dynamic rows -->
                        </div>
                        <div class="form-text mt-1">WhatsApp contact or helpline number(s).</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media & Web Links Column -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title text-primary mb-0"><i class="bi bi-share-fill me-2"></i>Social & Web Links</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Website Link -->
                    <div class="mb-4">
                        <label for="website_link" class="form-label fw-semibold">Website Link</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-info">
                                <i class="bi bi-globe"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 bg-light animate-input" id="website_link" name="website_link" placeholder="e.g. https://sadhumargi.in">
                        </div>
                    </div>

                    <!-- Registration Link -->
                    <div class="mb-4">
                        <label for="registration_link" class="form-label fw-semibold">Registration Link</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-warning">
                                <i class="bi bi-clipboard-check-fill"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 bg-light animate-input" id="registration_link" name="registration_link" placeholder="e.g. https://reg.spf.org">
                        </div>
                    </div>

                    <!-- Facebook Link -->
                    <div class="mb-4">
                        <label for="facebook_link" class="form-label fw-semibold">Facebook Link</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color: #3b5998;">
                                <i class="bi bi-facebook"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 bg-light animate-input" id="facebook_link" name="facebook_link" placeholder="e.g. https://facebook.com/spf">
                        </div>
                    </div>

                    <!-- Instagram Link -->
                    <div class="mb-4">
                        <label for="instagram_link" class="form-label fw-semibold">Instagram Link</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="color: #e1306c;">
                                <i class="bi bi-instagram"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 bg-light animate-input" id="instagram_link" name="instagram_link" placeholder="e.g. https://instagram.com/spf">
                        </div>
                    </div>

                    <!-- YouTube Link -->
                    <div class="mb-4">
                        <label for="youtube_link" class="form-label fw-semibold">YouTube Link</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-danger">
                                <i class="bi bi-youtube"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 bg-light animate-input" id="youtube_link" name="youtube_link" placeholder="e.g. https://youtube.com/c/spf">
                        </div>
                    </div>

                    <!-- Twitter Link -->
                    <div class="mb-3">
                        <label for="twitter_link" class="form-label fw-semibold">Twitter (X) Link</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-dark">
                                <i class="bi bi-twitter"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 bg-light animate-input" id="twitter_link" name="twitter_link" placeholder="e.g. https://twitter.com/spf">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Panel -->
        <div class="col-12 mt-4 text-end">
            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-semibold" id="saveBtn" style="background: linear-gradient(135deg, #2d3561 0%, #1e2347 100%);">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i>Save & Publish Changes
            </button>
        </div>
    </form>
</div>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Premium Hover & Interactive Styles */
    .animate-input {
        transition: all 0.3s ease;
    }
    .animate-input:focus {
        background-color: #fff !important;
        border-color: #2d3561 !important;
        box-shadow: 0 0 0 0.25rem rgba(45, 53, 97, 0.15) !important;
    }
    .input-group-text {
        transition: all 0.3s ease;
    }
    .input-group:focus-within .input-group-text {
        background-color: #e9ecef !important;
        border-color: #2d3561 !important;
    }
</style>

<script>
// Global function to add a dynamic row
function createInputRow(containerId, inputName, iconClass, type, placeholder, value = '') {
    const container = document.getElementById(containerId);
    
    const row = document.createElement('div');
    row.className = 'input-group mb-2';
    
    let colorClass = 'text-primary';
    if (iconClass === 'bi-envelope-fill') colorClass = 'text-danger';
    else if (iconClass === 'bi-whatsapp') colorClass = 'text-success';
    
    row.innerHTML = `
        <span class="input-group-text bg-light border-end-0 ${colorClass}">
            <i class="${iconClass}"></i>
        </span>
        <input type="${type}" class="form-control border-start-0 border-end-0 bg-light animate-input" name="${inputName}" value="${value}" placeholder="${placeholder}">
        <button type="button" class="btn btn-outline-danger border-start-0" onclick="removeInputRow(this)">
            <i class="bi bi-trash3-fill"></i>
        </button>
    `;
    
    container.appendChild(row);
}

function removeInputRow(button) {
    const row = button.closest('.input-group');
    row.remove();
}
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('linksForm');
    const saveBtn = document.getElementById('saveBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Loading overlay
    function showLoading() {
        Swal.fire({
            title: 'Fetching details...',
            html: 'Please wait while we load the configuration.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Load initial data
    showLoading();
    fetch('/api/spf-links')
        .then(res => res.json())
        .then(res => {
            Swal.close();
            if (res.success && res.data) {
                const data = res.data;
                
                // Populate Single fields
                document.getElementById('website_link').value = data.website_link || '';
                document.getElementById('registration_link').value = data.registration_link || '';
                document.getElementById('facebook_link').value = data.facebook_link || '';
                document.getElementById('instagram_link').value = data.instagram_link || '';
                document.getElementById('youtube_link').value = data.youtube_link || '';
                document.getElementById('twitter_link').value = data.twitter_link || '';

                // Populate Mobile numbers (Array)
                const mobiles = Array.isArray(data.mobile_number) ? data.mobile_number : [];
                if (mobiles.length === 0) {
                    createInputRow('mobile-container', 'mobile_number[]', 'bi-telephone-fill', 'text', 'e.g. +91 9876543210');
                } else {
                    mobiles.forEach(num => {
                        createInputRow('mobile-container', 'mobile_number[]', 'bi-telephone-fill', 'text', 'e.g. +91 9876543210', num);
                    });
                }

                // Populate Email addresses (Array)
                const emails = Array.isArray(data.email) ? data.email : [];
                if (emails.length === 0) {
                    createInputRow('email-container', 'email[]', 'bi-envelope-fill', 'email', 'e.g. contact@spf.org');
                } else {
                    emails.forEach(email => {
                        createInputRow('email-container', 'email[]', 'bi-envelope-fill', 'email', 'e.g. contact@spf.org', email);
                    });
                }

                // Populate WhatsApp numbers (Array)
                const whatsapps = Array.isArray(data.whatsapp_number) ? data.whatsapp_number : [];
                if (whatsapps.length === 0) {
                    createInputRow('whatsapp-container', 'whatsapp_number[]', 'bi-whatsapp', 'text', 'e.g. +91 9876543210');
                } else {
                    whatsapps.forEach(num => {
                        createInputRow('whatsapp-container', 'whatsapp_number[]', 'bi-whatsapp', 'text', 'e.g. +91 9876543210', num);
                    });
                }
            }
        })
        .catch(err => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch existing links. Please reload the page.'
            });
        });

    // Form Submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving Changes...';

        // Extract arrays from dynamic inputs
        const mobileInputs = document.getElementsByName('mobile_number[]');
        const mobile_number = Array.from(mobileInputs).map(input => input.value.trim()).filter(v => v !== '');

        const emailInputs = document.getElementsByName('email[]');
        const email = Array.from(emailInputs).map(input => input.value.trim()).filter(v => v !== '');

        const whatsappInputs = document.getElementsByName('whatsapp_number[]');
        const whatsapp_number = Array.from(whatsappInputs).map(input => input.value.trim()).filter(v => v !== '');

        const payload = {
            mobile_number: mobile_number,
            email: email,
            whatsapp_number: whatsapp_number,
            website_link: document.getElementById('website_link').value.trim(),
            registration_link: document.getElementById('registration_link').value.trim(),
            facebook_link: document.getElementById('facebook_link').value.trim(),
            instagram_link: document.getElementById('instagram_link').value.trim(),
            youtube_link: document.getElementById('youtube_link').value.trim(),
            twitter_link: document.getElementById('twitter_link').value.trim(),
        };

        fetch('/api/spf-links', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i>Save & Publish Changes';

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Contact details and social links updated successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message || 'Failed to save changes.'
                });
            }
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-cloud-arrow-up-fill me-2"></i>Save & Publish Changes';
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred. Please try again.'
            });
        });
    });
});
</script>
@endpush
@endsection
