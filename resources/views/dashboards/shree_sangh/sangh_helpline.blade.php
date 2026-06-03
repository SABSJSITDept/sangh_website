@extends('includes.layouts.shree_sangh')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container py-4">

        <!-- Alert Message -->
        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <div>
                📞 आप यहाँ कई मोबाइल नंबर और ईमेल जोड़ सकते हैं। (कोमा <b>,</b> लगा कर अलग करें) <br>
                💬 केवल WhatsApp का विकल्प भी उपलब्ध है।
            </div>
        </div>

        <h4 class="mb-3">📞 संघ हेल्पलाइन</h4>

        <!-- Form -->
        <form id="helplineForm" class="row g-3">
            <input type="hidden" name="id" id="helpline_id">

            <!-- Department Name -->
            <div class="col-md-6">
                <label class="form-label">Department Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="dept_name" id="dept_name" placeholder="Department Name">
            </div>

            <!-- Sequence -->
            <div class="col-md-6">
                <label class="form-label">Sequence (क्रम)</label>
                <input type="number" class="form-control" name="sequence" id="sequence" value="0">
            </div>

            <!-- Mobile Numbers -->
            <div class="col-md-6">
                <label class="form-label">Mobile Number(s)</label>
                <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="9876543210, 8765432109">
                <small class="text-muted">एक से अधिक नंबर कोमा (,) लगा कर लिखें</small>
            </div>

            <!-- Emails -->
            <div class="col-md-6">
                <label class="form-label">Email(s)</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="abc@example.com, xyz@example.com">
                <small class="text-muted">एक से अधिक ईमेल कोमा (,) लगा कर लिखें</small>
            </div>

            <!-- WhatsApp Number -->
            <div class="col-md-6">
                <label class="form-label">WhatsApp Number</label>
                <input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number" placeholder="9876543210">
            </div>

            <!-- Only WhatsApp Toggle -->
            <div class="col-md-6 d-flex align-items-center mt-5">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_only_whatsapp" name="is_only_whatsapp" value="1">
                    <label class="form-check-label" for="is_only_whatsapp">
                        केवल WhatsApp (Only WhatsApp)
                    </label>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-plus-circle"></i> Add Helpline
                </button>
            </div>
        </form>

        <!-- List -->
        <div class="mt-5">
            <h5 class="mb-3">📌 Helpline List</h5>
            <div id="helplineList"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('helplineForm');
            const helplineList = document.getElementById('helplineList');
            const submitBtn = document.getElementById('submitBtn');

            // Fetch Data
            function fetchHelplines() {
                fetch('/api/sangh-helplines')
                    .then(res => res.json())
                    .then(data => {
                        helplineList.innerHTML = '';
                        if (!data.length) {
                            helplineList.innerHTML = `<div class="text-center text-muted border p-3 rounded">No helpline records found.</div>`;
                            return;
                        }

                        data.forEach(item => {
                            let mobiles = item.mobile_number ? item.mobile_number.join(', ') : '';
                            let emails = item.email ? item.email.join(', ') : '';
                            let wpIcon = item.is_only_whatsapp ? '<span class="badge bg-success"><i class="bi bi-whatsapp"></i> Only WhatsApp</span>' : '';

                            helplineList.innerHTML += `
                                <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center bg-white shadow-sm">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-primary">${item.dept_name} <small class="text-muted">(Seq: ${item.sequence})</small> ${wpIcon}</h6>
                                        <div class="small">
                                            ${mobiles ? `<div><i class="bi bi-telephone-fill text-muted"></i> ${mobiles}</div>` : ''}
                                            ${emails ? `<div><i class="bi bi-envelope-fill text-muted"></i> ${emails}</div>` : ''}
                                            ${item.whatsapp_number ? `<div><i class="bi bi-whatsapp text-success"></i> ${item.whatsapp_number}</div>` : ''}
                                        </div>
                                    </div>
                                    <div class="text-end" style="min-width: 90px;">
                                        <button class="btn btn-sm btn-outline-warning mb-1" onclick='editHelpline(${JSON.stringify(item).replace(/'/g, "&apos;")})'>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger mb-1" onclick="deleteHelpline(${item.id})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                    });
            }

            // Submit Form
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const id = document.getElementById('helpline_id').value;
                
                // process arrays
                let mobiles = document.getElementById('mobile_number').value;
                let emailsStr = document.getElementById('email').value;

                let mobileArray = mobiles ? mobiles.split(',').map(s => s.trim()).filter(s => s) : [];
                let emailArray = emailsStr ? emailsStr.split(',').map(s => s.trim()).filter(s => s) : [];

                const payload = {
                    dept_name: document.getElementById('dept_name').value,
                    mobile_number: mobileArray,
                    email: emailArray,
                    whatsapp_number: document.getElementById('whatsapp_number').value,
                    is_only_whatsapp: document.getElementById('is_only_whatsapp').checked,
                    sequence: document.getElementById('sequence').value || 0
                };

                if (!payload.dept_name) {
                    Swal.fire('Error', 'Department Name is required!', 'error');
                    return;
                }

                const url = id ? `/api/sangh-helplines/${id}` : '/api/sangh-helplines';
                const method = id ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(async res => {
                    if (res.status === 422) {
                        const errorData = await res.json();
                        Swal.fire('Validation Error', Object.values(errorData.errors)[0][0], 'error');
                        throw new Error('Validation failed');
                    }
                    return res.json();
                })
                .then(data => {
                    Swal.fire('Success', id ? 'Updated successfully!' : 'Added successfully!', 'success');
                    form.reset();
                    document.getElementById('helpline_id').value = '';
                    submitBtn.innerHTML = `<i class="bi bi-plus-circle"></i> Add Helpline`;
                    fetchHelplines();
                })
                .catch(err => console.error(err));
            });

            // Edit
            window.editHelpline = function (data) {
                document.getElementById('helpline_id').value = data.id;
                document.getElementById('dept_name').value = data.dept_name;
                document.getElementById('sequence').value = data.sequence || 0;
                document.getElementById('mobile_number').value = data.mobile_number ? data.mobile_number.join(', ') : '';
                document.getElementById('email').value = data.email ? data.email.join(', ') : '';
                document.getElementById('whatsapp_number').value = data.whatsapp_number || '';
                document.getElementById('is_only_whatsapp').checked = !!data.is_only_whatsapp;
                
                submitBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Update Helpline`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // Delete
            window.deleteHelpline = function (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/api/sangh-helplines/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(() => {
                            fetchHelplines();
                            Swal.fire('Deleted!', 'Record has been deleted.', 'success');
                        });
                    }
                });
            };

            fetchHelplines();
        });
    </script>
@endsection
