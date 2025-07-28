@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- 👉 Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4">📋 प्रवर्ती संयोजक प्रबंधन</h3>

    {{-- ✅ FORM --}}
    <div class="card shadow-sm p-4 mb-4 rounded-4 border-start border-success border-3">
     <form id="pravartiSanyojakForm" enctype="multipart/form-data">
    <input type="hidden" id="formMethod" name="formMethod" value="POST">
    <input type="hidden" id="editId" name="editId">

    <div class="row mb-3">
        <div class="col-md-6">
            <label>नाम</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="नाम" required>
        </div>
        <div class="col-md-6">
            <label>पद</label>
            <input type="text" class="form-control" id="post" name="post" placeholder="पद" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>शहर</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="शहर" required>
        </div>
        <div class="col-md-6">
            <label>प्रवर्ती</label>
            <select class="form-control" id="pravarti_id" name="pravarti_id" required>
                <option value="">चयन करें</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>मोबाइल</label>
           <input type="text" name="mobile" id="mobile" class="form-control"
       maxlength="10" pattern="\d{10}" required
       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
       placeholder="Enter 10-digit mobile number">

        </div>
        <div class="col-md-6">
            <label>फोटो (200KB तक)</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            <div id="preview" class="mt-2"></div>
        </div>
    </div>

    <button type="submit" class="btn btn-success px-4">💾 सेव करें</button>
</form>

    </div>

    {{-- ✅ LIST --}}
    <div class="row gy-4" id="pravartiSanyojakList"></div>
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
// 👇 Submit form with error handling
// ✅ Submit form with FormData and NO custom headers
document.getElementById("pravartiSanyojakForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const id = document.getElementById("editId").value;
    const formData = new FormData(this);
    const url = id ? `/api/pravarti-sanyojak/${id}` : '/api/pravarti-sanyojak';
    const method = 'POST'; // Always POST for both store & update

    try {
        const res = await fetch(url, {
            method: method,
            body: formData, // ✅ No manual headers!
        });

        const result = await res.json();

        if (!res.ok) {
            if (result.errors) {
                const messages = Object.values(result.errors).flat().join('\n');
                alert("⚠️ त्रुटियाँ:\n" + messages);
            } else {
                alert("❌ कोई अनजान त्रुटि हुई।");
            }
            return;
        }

        if (result.success) {
            alert("✅ सफलतापूर्वक सहेजा गया!");
            location.reload();
        }
    } catch (error) {
        console.error("Error:", error);
        alert("❌ नेटवर्क या सर्वर की समस्या है।");
    }
});


// 👇 Fetch & show cards
fetch('/api/pravarti-sanyojak')
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById("pravartiSanyojakList");
        container.innerHTML = "";

        data.forEach(item => {
            const imageUrl = item.photo ? `/storage/${item.photo}` : 'https://via.placeholder.com/150x200?text=No+Image';
            container.innerHTML += `
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm text-center rounded-4 h-100 border-0">
                        <div class="card-body">
                            <img src="${imageUrl}" onerror="this.src='https://via.placeholder.com/150x200?text=No+Image';" 
                                class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover;">
                            <h5 class="fw-bold text-primary text-uppercase">${item.name}</h5>
                            <p class="mb-1">पद: ${item.post}</p>
                            <p class="mb-1">शहर: ${item.city}</p>
                            <p class="mb-2 text-muted">
                                <i class="bi bi-telephone-fill text-danger me-1"></i> ${item.mobile}
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <button onclick="editEntry(${item.id})" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button onclick="deleteEntry(${item.id})" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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

// (Optional) 👇 Edit entry - if needed in next step
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
