@extends('includes.layouts.shree_sangh')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 30px;
        }

        .card-form {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card-form.edit-mode {
            border-left: 5px solid #ffc107;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            font-weight: 500;
        }

        table tbody tr:hover {
            background-color: #f1f7ff;
            transition: 0.3s;
        }

        .btn-icon {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        img.table-img {
            border-radius: 50%;
            object-fit: cover;
            height: 50px;
            width: 50px;
        }
    </style>

    <div class="container py-4">

        <h2>स्थायी संपत्ति संवर्धन समिति</h2>

        <!-- Form -->
        <div class="card-form" id="formCard">
            <form id="sampatiForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" name="edit_id">

                <div class="alert alert-info text-center">
                    सभी फ़ील्ड अनिवार्य हैं और फोटो का आकार 200 KB से अधिक नहीं होना चाहिए।
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="नाम"
                            required>
                    </div>
                    <div class="col-md-2">
                        <select id="post" name="post" class="form-select form-select-lg" required>
                            <option value="">पोस्ट चुनें</option>
                            <option value="sanyojak">संयोजक</option>
                            <option value="seh sanyojak">सह संयोजक </option>
                            <option value="sanyojan mandal sadasy">संयोजन मण्डल सदस्य</option>
                            <option value="अंचल संयोजक">अंचल संयोजक</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="city" name="city" class="form-control form-control-lg" placeholder="शहर"
                            required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="mobile_number" name="mobile_number" class="form-control form-control-lg"
                            placeholder="मोबाइल नंबर" required>
                    </div>
                    <div class="col-md-2">
                        <input type="file" id="photo" name="photo" class="form-control form-control-lg" accept="image/*">
                    </div>
                    <div class="col-md-2">
                        <select id="session" name="session" class="form-select form-select-lg" required>
                            <option value="">सत्र चुनें</option>
                            <option value="2025-27" selected>2025-27</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-lg btn-primary"><i class="bi bi-save"></i> Save</button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="mt-5 table-responsive">
            <table class="table table-hover table-bordered align-middle" id="dataTable">
                <thead class="table-primary">
                    <tr>
                        <th>फोटो</th>
                        <th>नाम</th>
                        <th>पोस्ट</th>
                        <th>शहर</th>
                        <th>मोबाइल नंबर</th>
                        <th>सत्र</th>
                        <th>एक्शन</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", fetchData);

        function fetchData() {
            fetch('/api/sthayi-sampati')
                .then(res => res.json())
                .then(data => {
                    let rows = '';
                    data.forEach(item => {
                        rows += `
                                    <tr>
                                        <td><img src="${item.photo}" class="table-img"></td>
                                        <td>${item.name}</td>
                                        <td>${item.post}</td>
                                        <td>${item.city}</td>
                                        <td>${item.mobile_number}</td>
                                        <td>${item.session || '2025-27'}</td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm btn-icon" onclick="editItem(${item.id})"><i class="bi bi-pencil-square"></i> Edit</button>
                                            <button class="btn btn-danger btn-sm btn-icon" onclick="deleteItem(${item.id})"><i class="bi bi-trash"></i> Delete</button>
                                        </td>
                                    </tr>`;
                    });
                    document.querySelector("#dataTable tbody").innerHTML = rows;
                });
        }

        // Form submission
        document.getElementById('sampatiForm').addEventListener('submit', function (e) {
            e.preventDefault();
            let id = document.getElementById('edit_id').value;
            let name = document.getElementById('name').value.trim();
            let post = document.getElementById('post').value.trim();
            let city = document.getElementById('city').value.trim();
            let mobile_number = document.getElementById('mobile_number').value.trim();
            let photoInput = document.getElementById('photo');
            let photo = photoInput.files[0];

            if (!name) return Swal.fire('Error', 'Name is required', 'error');
            if (!post) return Swal.fire('Error', 'Post is required', 'error');
            if (!city) return Swal.fire('Error', 'City is required', 'error');
            if (!mobile_number) return Swal.fire('Error', 'Mobile number is required', 'error');

            if (photo) {
                if (!photo.type.startsWith('image/')) return Swal.fire('Error', 'Photo must be an image', 'error');
                if (photo.size > 200 * 1024) return Swal.fire('Error', 'Photo size must be less than 200 KB', 'error');
            }

            let formData = new FormData(this);
            let url = id ? `/api/sthayi-sampati/${id}` : '/api/sthayi-sampati';
            if (id) formData.append('_method', 'PUT');

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.errors) {
                        let messages = Object.values(data.errors).flat().join('<br>');
                        return Swal.fire('Error', messages, 'error');
                    }
                    Swal.fire('Success', 'Saved Successfully', 'success');
                    this.reset();
                    document.getElementById('edit_id').value = '';
                    document.getElementById('formCard').classList.remove('edit-mode');
                    fetchData();
                })
                .catch(() => Swal.fire('Error', 'Something went wrong', 'error'));
        });

        function editItem(id) {
            fetch('/api/sthayi-sampati')
                .then(res => res.json())
                .then(data => {
                    let item = data.find(i => i.id === id);
                    if (!item) return;

                    document.getElementById('edit_id').value = item.id;
                    document.getElementById('name').value = item.name;
                    document.getElementById('post').value = item.post;
                    document.getElementById('city').value = item.city;
                    document.getElementById('mobile_number').value = item.mobile_number;
                    document.getElementById('session').value = item.session || '2025-27';

                    // Highlight form in edit mode
                    let formCard = document.getElementById('formCard');
                    formCard.classList.add('edit-mode');

                    // Scroll to form smoothly
                    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
        }

        function deleteItem(id) {
            Swal.fire({
                title: 'Delete?',
                text: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/sthayi-sampati/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                        .then(() => {
                            Swal.fire('Deleted', 'Record removed', 'success');
                            fetchData();
                        });
                }
            });
        }
    </script>
@endsection