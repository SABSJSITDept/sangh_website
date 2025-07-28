@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- ✅ Bootstrap 5 & Bootstrap Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container py-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>कार्यसमिति सदस्य फ़ॉर्म</h5>
        </div>

        <div class="card-body">
            <form id="karyasamitiForm" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" id="editId">

                <div class="col-md-6">
                    <label class="form-label">नाम</label>
                    <input type="text" name="name" id="name" class="form-control shadow-sm" required  placeholder="नाम लिखें">
                </div>

                <div class="col-md-6">
                    <label class="form-label">शहर</label>
                    <input type="text" name="city" id="city" class="form-control shadow-sm" required  placeholder="शहर लिखें">
                </div>

                <div class="col-md-6">
                    <label class="form-label">आंचल</label>
                    <select id="aanchal_id" name="aanchal_id" class="form-select shadow-sm" required>
                        <option selected disabled>आंचल चुनें</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">मोबाइल</label>
                    <input type="text" name="mobile" id="mobile" class="form-control shadow-sm" required pattern="\d{10}" maxlength="10" placeholder="10 अंकों का नंबर">
                </div>

                <div class="col-md-12">
                    <label class="form-label">फोटो (200KB तक)</label>
                    <input type="file" name="photo" id="photo" class="form-control shadow-sm" accept="image/*">
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="bi bi-save2 me-1"></i> सबमिट करें
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <h5 class="fw-semibold mb-3">📋 सदस्य सूची</h5>
        <div id="dataList" class="row g-4"></div>
    </div>
</div>

<script>
    const token = document.querySelector('meta[name="csrf-token"]').content;

    async function loadAanchal() {
        let res = await fetch('/api/aanchal');
        let data = await res.json();
        let select = document.getElementById('aanchal_id');
        select.innerHTML += data.map(d => `<option value="${d.id}">${d.name}</option>`).join('');
    }

async function loadData() {
    let res = await fetch('/api/karyasamiti_sadasya');
    let data = await res.json();
    let list = document.getElementById('dataList');
    list.innerHTML = '';

    // ✅ Group by aanchal name
    const grouped = data.reduce((acc, item) => {
        const key = item.aanchal?.name || 'अन्य';
        acc[key] = acc[key] || [];
        acc[key].push(item);
        return acc;
    }, {});

    // ✅ Render sections
    for (const [aanchalName, entries] of Object.entries(grouped)) {
        const section = document.createElement('div');
        section.className = 'mb-5';

        section.innerHTML = `
            <h5 class="text-primary border-bottom pb-1 mb-4">${aanchalName}</h5>
            <div class="row g-4">
                ${entries.map(d => `
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow rounded-4 overflow-hidden">
                            <div class="ratio ratio-4x3">
                                ${d.photo ? `<img src="/storage/${d.photo}" class="object-fit-cover w-100 h-100" alt="Photo">` : `<div class="bg-secondary text-white d-flex justify-content-center align-items-center">No Image</div>`}
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-1">${d.name}</h5>
                                <p class="card-text small mb-2">
                                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i>${d.city}<br>
                                    <i class="bi bi-telephone-fill me-1 text-success"></i>${d.mobile}
                                </p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editData(${d.id})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteData(${d.id})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        list.appendChild(section);
    }
}


    document.getElementById('karyasamitiForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let id = document.getElementById('editId').value;
        let url = id ? `/api/karyasamiti_sadasya/${id}?_method=PUT` : '/api/karyasamiti_sadasya';

        let res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (res.ok) {
            this.reset();
            document.getElementById('editId').value = '';
            loadData();
            showToast('Success', 'डेटा सफलतापूर्वक सेव हो गया', 'success');
        } else {
            showToast('Error', 'कुछ गड़बड़ हो गई', 'danger');
        }
    });

    async function deleteData(id) {
        if (!confirm('क्या आप वाकई इसे हटाना चाहते हैं?')) return;
        let res = await fetch(`/api/karyasamiti_sadasya/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        if (res.ok) {
            loadData();
            showToast('Deleted', 'रिकॉर्ड हटा दिया गया है', 'warning');
        }
    }

    async function editData(id) {
        let res = await fetch(`/api/karyasamiti_sadasya/${id}`);
        let d = await res.json();
        document.getElementById('name').value = d.name;
        document.getElementById('city').value = d.city;
        document.getElementById('mobile').value = d.mobile;
        document.getElementById('aanchal_id').value = d.aanchal_id;
        document.getElementById('editId').value = d.id;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showToast(title, msg, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed top-0 end-0 m-3 show`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong> ${msg}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    loadAanchal();
    loadData();
</script>
@endsection
