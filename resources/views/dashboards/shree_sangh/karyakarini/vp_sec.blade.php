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
                        <select name="post" class="form-select" required>
                            <option value="">चुनें</option>
                            <option value="उपाध्यक्ष">उपाध्यक्ष</option>
                            <option value="मंत्री">मंत्री</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>शहर</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>मोबाइल</label>
                        <input type="text" name="mobile" class="form-control"
                               required maxlength="10" pattern="[0-9]{10}"
                               title="10 अंकों का मोबाइल नंबर डालें"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="col-md-4">
                        <label>अंचल</label>
                        <select name="aanchal" class="form-select" id="aanchalDropdown" required>
                            <option value="">चुनें</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>फोटो (200KB तक)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" id="photoInput">
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
    <div id="vpSecList"></div>
</div>

{{-- 🔻 TOAST CONTAINER --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toastBox" class="toast align-items-center text-bg-primary border-0" role="alert"
         aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMsg">Toast message here</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

{{-- 🔻 SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
loadData();
loadAanchals();

document.getElementById("photoInput").addEventListener("change", function () {
    if (this.files[0] && this.files[0].size > 200 * 1024) {
        showToast("⚠️ फ़ोटो का आकार 200KB से अधिक है!", "danger");
        this.value = "";
    }
});

function showToast(message, type = "primary") {
    const toastEl = document.getElementById("toastBox");
    const toastMsg = document.getElementById("toastMsg");
    toastMsg.textContent = message;

    toastEl.className = `toast align-items-center text-bg-${type} border-0`;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

async function loadAanchals() {
    try {
        const res = await fetch("/api/aanchal");
        const aanchals = await res.json();
        const dropdown = document.getElementById("aanchalDropdown");
        aanchals.forEach(item => {
            const opt = document.createElement("option");
            opt.value = item.name;
            opt.textContent = item.name;
            dropdown.appendChild(opt);
        });
    } catch (error) {
        showToast("❌ आंचल लोड नहीं हुआ", "danger");
        console.error(error);
    }
}

async function loadData() {
    const res = await fetch("/api/vp-sec");
    const data = await res.json();
    const container = document.getElementById("vpSecList");
    container.innerHTML = "";

    data.forEach(group => {
        if (group.length === 0) return;

        const aanchalName = group[0].aanchal ?? '—';
        const sectionTitle = document.createElement("h5");
        sectionTitle.className = "mt-4 mb-2 text-primary fw-bold";
        sectionTitle.textContent = `अंचल: ${aanchalName}`;
        container.appendChild(sectionTitle);

        const row = document.createElement("div");
        row.className = "row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2";

        group.forEach(item => {
            const card = document.createElement("div");
            card.className = "col";
            card.innerHTML = `
                <div class="card border h-100" style="border-radius: 8px; font-size: 12px;">
                    <div class="text-center p-2" style="background-color: #f9f9f9;">
                        <img src="${item.photo ? `/storage/${item.photo}` : 'https://via.placeholder.com/80x80?text=No+Image'}"
                             class="rounded" style="height: 80px; object-fit: contain;" />
                    </div>
                    <div class="card-body p-2 text-center">
                        <div class="fw-bold text-primary" style="font-size: 13px;">${item.name}</div>
                        <div class="text-muted">पद: ${item.post}</div>
                        <div class="text-muted">शहर: ${item.city}</div>
                        <div class="text-muted">📞 ${item.mobile}</div>
                        <div class="d-flex justify-content-center gap-1 mt-2">
                            <button class="btn btn-sm btn-outline-warning py-0 px-1" title="Edit"
                                    onclick='editItem(${JSON.stringify(item)})'>✏️</button>
                            <button class="btn btn-sm btn-outline-danger py-0 px-1" title="Delete"
                                    onclick="deleteItem(${item.id})">🗑️</button>
                        </div>
                    </div>
                </div>`;
            row.appendChild(card);
        });

        container.appendChild(row);
    });
}

function editItem(item) {
    document.querySelector("input[name='name']").value = item.name;
    document.querySelector("select[name='post']").value = item.post;
    document.querySelector("input[name='city']").value = item.city;
    document.querySelector("input[name='mobile']").value = item.mobile;
    document.querySelector("select[name='aanchal']").value = item.aanchal ?? "";

    document.getElementById("editId").value = item.id;
    document.getElementById("formMethod").value = "PUT";
    document.getElementById("submitBtn").innerText = "✅ अपडेट करें";

    window.scrollTo({ top: 0, behavior: 'smooth' });
    document.querySelector("input[name='name']").focus();
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
        let result;
        try {
            result = JSON.parse(resultText);
        } catch {
            result = { message: "⚠️ Unexpected error." };
        }

        if (response.ok) {
            showToast("✅ सफलतापूर्वक सहेजा गया!", "success");
            resetForm();
            loadData();
        } else {
            if (result.message) {
                showToast(result.message, "danger");
            } else if (typeof result === 'object') {
                const errors = Object.values(result).flat().join(" | ");
                showToast(errors, "danger");
            } else {
                showToast("❌ Unknown Error", "danger");
            }
        }
    } catch (err) {
        console.error("❌ Server/Network Error", err);
        showToast("⚠️ सर्वर से जवाब नहीं मिला।", "danger");
    }
});

async function deleteItem(id) {
    if (!confirm("क्या आप वाकई इस सदस्य को हटाना चाहते हैं?")) return;

    const res = await fetch(`/api/vp-sec/${id}`, {
        method: "DELETE"
    });

    if (res.ok) {
        showToast("🗑️ सदस्य को हटा दिया गया", "success");
        loadData();
    } else {
        showToast("❌ डिलीट में समस्या आई", "danger");
    }
}
</script>
@endsection
