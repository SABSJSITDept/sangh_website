@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">संचोजन मंडल - अंतरराष्ट्रीय सदस्यता</h2>

    <form id="addForm" enctype="multipart/form-data" class="row g-3">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="col-md-6">
            <input type="text" name="name" id="name" class="form-control" placeholder="नाम" required>
        </div>
        <div class="col-md-6">
            <input type="text" name="city" id="city" class="form-control" placeholder="शहर" required>
        </div>
        <div class="col-md-6">
            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="मोबाइल" required>
        </div>
        <div class="col-md-6">
            <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
            <small class="text-muted">* नई फ़ोटो चुनें यदि अपडेट करना हो</small>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary" id="submitBtn">जमा करें</button>
        </div>
    </form>

    <hr>

    <div class="row mt-4" id="cardContainer"></div>
</div>

<script>
    const apiUrl = "/api/sanyojan-mandal-antrastriya-sadasyata";
    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    };

    function fetchAll() {
        fetch(apiUrl)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.forEach(item => {
                    html += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="/storage/${item.photo}" class="card-img-top" height="200">
                                <div class="card-body">
                                    <h5>${item.name}</h5>
                                    <p>शहर: ${item.city}</p>
                                    <p>मोबाइल: ${item.mobile}</p>
                                    <button class="btn btn-danger btn-sm" onclick="del(${item.id})">हटाएं</button>
                                    <button class="btn btn-warning btn-sm ms-2" onclick='edit(${JSON.stringify(item)})'>संपादित करें</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('cardContainer').innerHTML = html;
            });
    }

    fetchAll();

    document.getElementById('addForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = new FormData(this);
        const id = document.getElementById('edit_id').value;

        const method = id ? 'POST' : 'POST';
        const url = id ? `${apiUrl}/${id}` : apiUrl;

        if (id) {
            form.append('_method', 'PUT');
        }

        fetch(url, {
            method,
            headers,
            body: form
        }).then(res => {
            if (res.ok) {
                this.reset();
                document.getElementById('submitBtn').innerText = 'जमा करें';
                fetchAll();
                document.getElementById('edit_id').value = '';
            }
        });
    });

    function del(id) {
        fetch(`${apiUrl}/${id}`, {
            method: 'DELETE',
            headers
        }).then(() => fetchAll());
    }

    function edit(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('city').value = data.city;
        document.getElementById('mobile').value = data.mobile;
        document.getElementById('submitBtn').innerText = 'अपडेट करें';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endsection
