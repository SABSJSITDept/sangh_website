@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold text-primary">📋 VP/SEC सदस्य प्रबंधन</h3>

    {{-- 🔹 FORM --}}
    <div class="card shadow-sm border border-primary mb-4">
        <div class="card-body">
            <form id="vpSecForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="formMethod" value="POST">
                <input type="hidden" id="editId">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label>नाम</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>पद</label>
                        <input type="text" name="post" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>शहर</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-4">
    <label>मोबाइल</label>
    <input 
        type="text" 
        name="mobile" 
        class="form-control" 
        required 
        maxlength="10" 
        pattern="[0-9]{10}" 
        title="10 अंकों का मोबाइल नंबर डालें" 
        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
    >
</div>

                    <div class="col-md-4">
                        <label>फोटो (200KB तक)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success" id="submitBtn">➕ जोड़ें</button>
                    <button type="reset" class="btn btn-secondary" onclick="resetForm()">🔄 रीसेट</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🔹 CARD GRID --}}
    <h5 class="fw-bold text-secondary mb-3">🔽 सदस्यों की सूची</h5>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="vpSecList"></div>
</div>

{{-- 🔻 SCRIPT --}}
<script>
loadData();

async function loadData() {
    const res = await fetch("/api/vp-sec");
    const data = await res.json();
    const container = document.getElementById("vpSecList");
    container.innerHTML = "";

    data.forEach(item => {
        const card = document.createElement("div");
        card.className = "col";

        card.innerHTML = `
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
               <img src="${item.photo ? `/storage/${item.photo}` : 'https://via.placeholder.com/300x240?text=No+Image'}"
     class="card-img-top rounded-top"
     style="height: 240px; width: 100%; object-fit: contain; padding: 10px; background: #f8f9fa;">

                <div class="card-body text-center p-3">
                    <h6 class="fw-bold mb-1 text-primary">${item.name}</h6>
                    <p class="mb-1 small text-muted">पद: ${item.post}</p>
                    <p class="mb-1 small text-muted">शहर: ${item.city}</p>
                    <p class="mb-3 small text-muted">📞 ${item.mobile}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-warning" onclick='editItem(${JSON.stringify(item)})'>✏️</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(${item.id})">🗑️</button>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(card);
    });
}

function editItem(item) {
    document.querySelector("input[name='name']").value = item.name;
    document.querySelector("input[name='post']").value = item.post;
    document.querySelector("input[name='city']").value = item.city;
    document.querySelector("input[name='mobile']").value = item.mobile;

    document.getElementById("editId").value = item.id;
    document.getElementById("formMethod").value = "PUT";
    document.getElementById("submitBtn").innerText = "✅ अपडेट करें";
}

function resetForm() {
    document.getElementById("editId").value = "";
    document.getElementById("formMethod").value = "POST";
    document.getElementById("submitBtn").innerText = "➕ जोड़ें";
    document.getElementById("vpSecForm").reset();
}

document.getElementById("vpSecForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const editId = document.getElementById("editId").value.trim();
    const method = document.getElementById("formMethod").value;

    let url = "/api/vp-sec";
    let fetchMethod = "POST";

    if (method === "PUT" && editId) {
        url += `/${editId}`;
        formData.append("_method", "PUT");
    }

    try {
        const response = await fetch(url, {
            method: fetchMethod,
            body: formData,
        });

        const resultText = await response.text();
        const result = JSON.parse(resultText);

        if (response.ok) {
            alert("✅ सफलतापूर्वक सहेजा गया!");
            resetForm();
            loadData();
        } else {
            console.error(result);
            alert("❌ Error: " + JSON.stringify(result));
        }
    } catch (err) {
        console.error("❌ Server/Network Error", err);
        alert("⚠️ Unexpected error. See console.");
    }
});

async function deleteItem(id) {
    if (!confirm("क्या आप वाकई इस सदस्य को हटाना चाहते हैं?")) return;

    const res = await fetch(`/api/vp-sec/${id}`, {
        method: "DELETE"
    });

    if (res.ok) {
        alert("🗑️ सदस्य को हटा दिया गया");
        loadData();
    } else {
        alert("❌ डिलीट में समस्या आई");
    }
}
</script>
@endsection
