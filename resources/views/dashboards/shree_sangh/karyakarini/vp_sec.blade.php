@extends('includes.layouts.shree_sangh')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mt-4">
        <h3 class="mb-4 fw-bold text-primary">📋 VP/SEC सदस्य प्रबंधन</h3>

        {{-- 🔹 FORM --}}
        {{-- 🔹 FORM --}}
        <div class="card shadow-sm border border-primary mb-4">
            <div class="card-body">

                <!-- Validation Info -->
                <div class="alert alert-info text-center">
                    सभी फ़ील्ड अनिवार्य हैं और फोटो का आकार 200 KB से अधिक नहीं होना चाहिए। कृपया सही अंचल चुनें।
                </div>

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
                            <input type="text" name="mobile" class="form-control" maxlength="10" pattern="[0-9]{10}"
                                title="10 अंकों का मोबाइल नंबर डालें"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="col-md-4">
                            <label>अंचल</label>
                            <select name="aanchal_id" class="form-select" id="aanchalDropdown" required>
                                <option value="">चुनें</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>फोटो (200KB तक)</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" id="photoInput">
                        </div>
                        <div class="col-md-4">
                            <label>सत्र</label>
                            <select name="session" class="form-select" required>
                                <option value="">चुनें</option>
                                <option value="2025-27" selected>2025-27</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success" id="submitBtn">➕ जोड़ें</button>
                        <button type="reset" class="btn btn-secondary" onclick="resetForm()">🔄 रीसेट</button>
                    </div>
                </form>
            </div>
        </div>


        {{-- 🔹 FILTER --}}
        <div class="mb-3">
            <label class="form-label">🔍 अंचल फ़िल्टर करें</label>
            <select id="filterAanchal" class="form-select" onchange="loadData()">
                <option value="">सभी</option>
            </select>
        </div>

        {{-- 🔹 LIST VIEW --}}
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>फोटो</th>
                        <th>नाम</th>
                        <th>पद</th>
                        <th>शहर</th>
                        <th>अंचल</th>
                        <th>मोबाइल</th>
                        <th>सत्र</th>
                        <th>एक्शन</th>
                    </tr>
                </thead>
                <tbody id="vpSecList"></tbody>
            </table>
        </div>
    </div>

    {{-- 🔻 TOAST --}}
    <div class="position-fixed top-0 end-0 mt-5 me-3" style="z-index: 1055">
        <div id="toastBox" class="toast align-items-center text-bg-primary border-0 shadow" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMsg">Toast message here</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        loadData();
        loadAanchals();

        function showToast(message, type = "primary") {
            const toastEl = document.getElementById("toastBox");
            const toastMsg = document.getElementById("toastMsg");
            toastMsg.textContent = message;
            toastEl.className = `toast align-items-center text-bg-${type} border-0`;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        document.getElementById("photoInput").addEventListener("change", function () {
            const file = this.files[0];

            if (!file) {
                showToast("⚠️ कृपया फ़ोटो चुनें!", "danger");
                return;
            }

            if (file.size > 200 * 1024) {
                showToast("⚠️ फ़ोटो का SIZE 200KB से अधिक है!", "danger");
                this.value = "";
            }
        });


        async function loadAanchals() {
            try {
                const res = await fetch("/api/aanchal");
                const aanchals = await res.json();
                const dropdown = document.getElementById("aanchalDropdown");
                const filter = document.getElementById("filterAanchal");

                aanchals.forEach(item => {
                    const opt1 = document.createElement("option");
                    opt1.value = item.id;         // id (numeric)
                    opt1.textContent = item.name;
                    dropdown.appendChild(opt1);

                    const opt2 = document.createElement("option");
                    opt2.value = item.id;         // filter by id
                    opt2.textContent = item.name;
                    filter.appendChild(opt2);
                });
            } catch (error) {
                showToast("❌ अंचल लोड नहीं हुआ", "danger");
            }
        }


        async function loadData() {
            const selected = document.getElementById("filterAanchal").value;
            const res = await fetch("/api/vp-sec");
            const data = await res.json();
            const tbody = document.getElementById("vpSecList");
            tbody.innerHTML = "";

            data.flat().filter(item => !selected || item.aanchal === selected).forEach(item => {
                const row = document.createElement("tr");
                row.innerHTML = `
                <td><img src="${item.photo ? `/storage/${item.photo}` : 'https://via.placeholder.com/60x60?text=No+Image'}" class="rounded" style="height: 60px; object-fit: contain;"/></td>
                <td>${item.name}</td>
                <td>${item.post}</td>
                <td>${item.city}</td>
                <td>${item.aanchal}</td>
                <td>${item.mobile}</td>
                <td>${item.session || '2025-27'}</td>
                <td>
                    <button class="btn btn-sm btn-outline-warning" onclick='editItem(${JSON.stringify(item)})'>✏️</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(${item.id})">🗑️</button>
                </td>`;
                tbody.appendChild(row);
            });
        }

        function editItem(item) {
            document.querySelector("input[name='name']").value = item.name;
            document.querySelector("select[name='post']").value = item.post;
            document.querySelector("input[name='city']").value = item.city;
            document.querySelector("input[name='mobile']").value = item.mobile;
            document.querySelector("select[name='session']").value = item.session || '2025-27';

            // ✅ Set aanchal by name (find the option with matching text)
            const aanchalDropdown = document.querySelector("select[name='aanchal_id']");
            if (item.aanchal) {
                for (let option of aanchalDropdown.options) {
                    if (option.textContent === item.aanchal) {
                        aanchalDropdown.value = option.value;
                        break;
                    }
                }
            } else {
                aanchalDropdown.value = "";
            }

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

            // ✅ Convert aanchal_id to aanchal name
            const aanchalId = formData.get('aanchal_id');
            if (aanchalId) {
                const aanchalDropdown = document.getElementById('aanchalDropdown');
                const selectedOption = aanchalDropdown.options[aanchalDropdown.selectedIndex];
                const aanchalName = selectedOption ? selectedOption.textContent : '';
                formData.delete('aanchal_id');
                formData.append('aanchal', aanchalName);
            }

            let url = "/api/vp-sec";
            let fetchMethod = "POST";

            if (method === "PUT" && editId) {
                url += `/${editId}`;
                formData.append("_method", "PUT");
            }

            try {
                const response = await fetch(url, {
                    method: fetchMethod,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
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
                showToast("⚠️ सर्वर से जवाब नहीं मिला।", "danger");
            }
        });

        async function deleteItem(id) {
            if (!confirm("क्या आप वाकई इस सदस्य को हटाना चाहते हैं?")) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const res = await fetch(`/api/vp-sec/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                credentials: "same-origin" // ✅ ensures cookies/sessions sent
            });

            if (res.ok) {
                showToast("🗑️ सदस्य को हटा दिया गया", "success");
                loadData();
            } else if (res.status === 419) {
                showToast("⚠️ सत्र समाप्त हुआ — कृपया पेज रीफ़्रेश करें।", "danger");
            } else {
                showToast("❌ डिलीट में समस्या आई", "danger");
            }
        }

    </script>
@endsection