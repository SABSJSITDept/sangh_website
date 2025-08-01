@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4">📋 प्रवर्ती संयोजक प्रबंधन</h3>

    {{-- 🔹 FORM --}}
    <div class="card shadow-sm p-4 mb-4 rounded-4 border-start border-success border-3">
        <form id="pravartiSanyojakForm" enctype="multipart/form-data">
            <input type="hidden" id="formMethod" name="formMethod" value="POST">
            <input type="hidden" id="editId" name="editId">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>नाम</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="नाम" required>
                </div>
                <div class="col-md-4">
                    <label>पद</label>
                    <select class="form-control" id="post" name="post" required>
                        <option value="">चयन करें</option>
                        <option value="संयोजक">संयोजक</option>
                        <option value="सह संयोजक">सह संयोजक</option>
                        <option value="संयोजन मण्डल सदस्य">संयोजन मण्डल सदस्य</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>शहर</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="शहर" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>प्रवर्ती</label>
                    <select class="form-control" id="pravarti_id" name="pravarti_id" required>
                        <option value="">चयन करें</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>मोबाइल</label>
                    <input type="text" name="mobile" id="mobile" class="form-control"
                        maxlength="10" pattern="\d{10}" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        placeholder="10 अंकों का मोबाइल नंबर">
                </div>
                <div class="col-md-4">
                    <label>फोटो (200KB तक)</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    <div id="preview" class="mt-2"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-success px-4">💾 सेव करें</button>
        </form>
    </div>

    {{-- 🔹 TABLE LIST --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="pravartiSanyojakList">
            <thead class="table-light text-center">
                <tr>
                    <th>फोटो</th>
                    <th>नाम</th>
                    <th>पद</th>
                    <th>शहर</th>
                    <th>मोबाइल</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'X-Requested-With': 'XMLHttpRequest'
};

// 👇 Fill pravarti dropdown
fetch("/api/pravarti")
    .then(res => res.json())
    .then(data => {
        const dropdown = document.getElementById("pravarti_id");
        data.forEach(item => {
            dropdown.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    });

// 👇 Preview Image
document.getElementById("photo").addEventListener("change", function () {
    const file = this.files[0];
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("preview").innerHTML = `<img src="${e.target.result}" class="img-thumbnail" width="100">`;
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById("preview").innerHTML = "";
    }
});

// 👇 Submit form
document.getElementById("pravartiSanyojakForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const id = document.getElementById("editId").value;
    const formData = new FormData(this);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    const url = id ? `/api/pravarti-sanyojak/${id}` : '/api/pravarti-sanyojak';
    const method = 'POST';

    try {
        const res = await fetch(url, {
            method,
            body: formData
        });

        const result = await res.json();

        if (!res.ok) {
            alert(result.error || "❌ कोई त्रुटि हुई।");
            return;
        }

        if (result.success) {
            alert("✅ सफलतापूर्वक सहेजा गया!");
            location.reload();
        }
    } catch (error) {
        alert("❌ नेटवर्क या सर्वर की समस्या है।");
    }
});

// 👇 Fetch & Show table rows
fetch('/api/pravarti-sanyojak')
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector("#pravartiSanyojakList tbody");
        tbody.innerHTML = "";

        data.forEach(item => {
            const imageUrl = item.photo ? `/storage/${item.photo}` : 'https://via.placeholder.com/80x100?text=No+Image';
            tbody.innerHTML += `
                <tr class="text-center">
                    <td><img src="${imageUrl}" class="img-thumbnail" style="width: 80px; height: 100px; object-fit: cover;"></td>
                    <td>${item.name}</td>
                    <td>${item.post}</td>
                    <td>${item.city}</td>
                    <td>${item.mobile}</td>
                    <td>
                        <button onclick="editEntry(${item.id})" class="btn btn-sm btn-warning me-1">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button onclick="deleteEntry(${item.id})" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    });

// 👇 Delete Entry
function deleteEntry(id) {
    if (confirm("क्या आप वाकई इसे हटाना चाहते हैं?")) {
        fetch(`/api/pravarti-sanyojak/${id}`, {
            method: 'DELETE',
            headers
        }).then(() => location.reload());
    }
}

// 👇 Edit Entry
function editEntry(id) {
    fetch(`/api/pravarti-sanyojak`)
        .then(res => res.json())
        .then(data => {
            const entry = data.find(e => e.id === id);
            if (entry) {
                document.getElementById("editId").value = entry.id;
                document.getElementById("name").value = entry.name;
                document.getElementById("post").value = entry.post;
                document.getElementById("city").value = entry.city;
                document.getElementById("pravarti_id").value = entry.pravarti_id;
                document.getElementById("mobile").value = entry.mobile;
                document.getElementById("formMethod").value = 'PUT';
                document.getElementById("preview").innerHTML = `<img src="/storage/${entry.photo}" class="img-thumbnail" width="100">`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
}
</script>
@endsection
