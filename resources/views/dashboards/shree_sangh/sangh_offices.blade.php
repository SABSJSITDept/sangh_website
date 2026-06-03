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
                🏢 आप यहाँ संघ के ऑफिस/कार्यालय की जानकारी जोड़ सकते हैं।<br>
                📞 मोबाइल नंबर और ईमेल एक से ज़्यादा जोड़ने के लिए कोमा (<b>,</b>) लगा कर अलग करें।
            </div>
        </div>

        <h4 class="mb-3">🏢 संघ ऑफिस (Sangh Offices)</h4>

        <!-- Form -->
        <form id="officeForm" class="row g-3">
            <input type="hidden" name="id" id="office_id">

            <!-- Name -->
            <div class="col-md-6">
                <label class="form-label">Office Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="E.g., Head Office">
            </div>

            <!-- Sequence -->
            <div class="col-md-6">
                <label class="form-label">Sequence (क्रम)</label>
                <input type="number" class="form-control" name="sequence" id="sequence" value="0">
            </div>

            <!-- Address -->
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" id="address" placeholder="Address..." rows="2"></textarea>
            </div>

            <!-- Google Maps Link -->
            <div class="col-md-6">
                <label class="form-label">Google Maps Link</label>
                <input type="url" class="form-control" name="google_link" id="google_link" placeholder="https://maps.google.com/...">
            </div>

            <!-- Phone Numbers -->
            <div class="col-md-6">
                <label class="form-label">Phone Number(s)</label>
                <input type="text" class="form-control" name="phone_numbers" id="phone_numbers" placeholder="9876543210, 8765432109">
                <small class="text-muted">एक से अधिक नंबर कोमा (,) लगा कर लिखें</small>
            </div>

            <!-- Emails -->
            <div class="col-md-6">
                <label class="form-label">Email(s)</label>
                <input type="text" class="form-control" name="emails" id="emails" placeholder="abc@example.com, xyz@example.com">
                <small class="text-muted">एक से अधिक ईमेल कोमा (,) लगा कर लिखें</small>
            </div>

            <div class="col-12 text-end mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-plus-circle"></i> Add Office
                </button>
            </div>
        </form>

        <!-- List -->
        <div class="mt-5">
            <h5 class="mb-3">📌 Offices List</h5>
            <div id="officeList"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('officeForm');
            const officeList = document.getElementById('officeList');
            const submitBtn = document.getElementById('submitBtn');

            // Fetch Data
            function fetchOffices() {
                fetch('/api/sangh-offices')
                    .then(res => res.json())
                    .then(data => {
                        officeList.innerHTML = '';
                        if (!data.length) {
                            officeList.innerHTML = `<div class="text-center text-muted border p-3 rounded">No office records found.</div>`;
                            return;
                        }

                        data.forEach(item => {
                            let phones = item.phone_numbers ? item.phone_numbers.join(', ') : '';
                            let emails = item.emails ? item.emails.join(', ') : '';

                            officeList.innerHTML += `
                                <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center bg-white shadow-sm">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-primary">${item.name} <small class="text-muted">(Seq: ${item.sequence})</small></h6>
                                        <div class="small mt-2">
                                            ${item.address ? `<div><i class="bi bi-building text-muted"></i> <strong>Address:</strong> ${item.address}</div>` : ''}
                                            ${item.google_link ? `<div><i class="bi bi-geo-alt-fill text-danger"></i> <a href="${item.google_link}" target="_blank">Google Map Link</a></div>` : ''}
                                            ${phones ? `<div class="mt-1"><i class="bi bi-telephone-fill text-muted"></i> ${phones}</div>` : ''}
                                            ${emails ? `<div><i class="bi bi-envelope-fill text-muted"></i> ${emails}</div>` : ''}
                                        </div>
                                    </div>
                                    <div class="text-end" style="min-width: 90px;">
                                        <button class="btn btn-sm btn-outline-warning mb-1" onclick='editOffice(${JSON.stringify(item).replace(/'/g, "&apos;")})'>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger mb-1" onclick="deleteOffice(${item.id})">
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

                const id = document.getElementById('office_id').value;
                
                // process arrays
                let phones = document.getElementById('phone_numbers').value;
                let emailsStr = document.getElementById('emails').value;

                let phoneArray = phones ? phones.split(',').map(s => s.trim()).filter(s => s) : [];
                let emailArray = emailsStr ? emailsStr.split(',').map(s => s.trim()).filter(s => s) : [];

                const payload = {
                    name: document.getElementById('name').value,
                    address: document.getElementById('address').value,
                    google_link: document.getElementById('google_link').value,
                    phone_numbers: phoneArray,
                    emails: emailArray,
                    sequence: document.getElementById('sequence').value || 0
                };

                if (!payload.name) {
                    Swal.fire('Error', 'Office Name is required!', 'error');
                    return;
                }

                const url = id ? `/api/sangh-offices/${id}` : '/api/sangh-offices';
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
                    document.getElementById('office_id').value = '';
                    submitBtn.innerHTML = `<i class="bi bi-plus-circle"></i> Add Office`;
                    fetchOffices();
                })
                .catch(err => console.error(err));
            });

            // Edit
            window.editOffice = function (data) {
                document.getElementById('office_id').value = data.id;
                document.getElementById('name').value = data.name;
                document.getElementById('address').value = data.address || '';
                document.getElementById('google_link').value = data.google_link || '';
                document.getElementById('sequence').value = data.sequence || 0;
                document.getElementById('phone_numbers').value = data.phone_numbers ? data.phone_numbers.join(', ') : '';
                document.getElementById('emails').value = data.emails ? data.emails.join(', ') : '';
                
                submitBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Update Office`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // Delete
            window.deleteOffice = function (id) {
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
                        fetch(`/api/sangh-offices/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(() => {
                            fetchOffices();
                            Swal.fire('Deleted!', 'Record has been deleted.', 'success');
                        });
                    }
                });
            };

            fetchOffices();
        });
    </script>
@endsection
