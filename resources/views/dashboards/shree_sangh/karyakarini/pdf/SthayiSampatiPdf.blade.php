@extends('includes.layouts.shree_sangh')

@section('content')
    <div class="container my-4">
        <div class="row">
            {{-- 🔹 Form Column --}}
            <div class="col-md-5 mb-4">
                <div class="card shadow-lg border-0 rounded-4"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-header text-white fw-bold border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-file-pdf me-2"></i>📄 स्थायी संपत्ति PDF अपलोड करें
                        </h5>
                    </div>
                    <div class="card-body bg-white rounded-bottom-4">
                        <form id="sthayiSampatiPdfForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">
                            <input type="hidden" id="editId">

                            {{-- Name Field --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-signature text-primary"></i> नाम:
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control form-control-sm shadow-sm"
                                    placeholder="PDF का नाम दर्ज करें" required>
                            </div>

                            {{-- Session Dropdown --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-success"></i> सत्र (Session):
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="session" class="form-select form-select-sm shadow-sm" required>
                                    <option value="">-- सत्र चुनें --</option>
                                    <option value="2023-25">2023-25</option>
                                </select>
                            </div>

                            {{-- PDF Upload Field --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-upload text-danger"></i> PDF अपलोड करें (5MB तक):
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="pdf" accept=".pdf" class="form-control form-control-sm shadow-sm"
                                    id="pdfInput" required>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> केवल PDF फाइल, अधिकतम 5MB
                                </small>
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm">
                                    <i class="fas fa-save"></i> 💾 सबमिट करें
                                </button>
                                <button type="button" onclick="resetForm()" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-redo"></i> रीसेट करें
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 🔸 Data Table Column --}}
            <div class="col-md-7">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-gradient text-white fw-bold border-0 py-3"
                        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> 📋 अपलोड की गई PDF सूची
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>नाम</th>
                                        <th class="text-center">सत्र</th>
                                        <th class="text-center">कार्रवाई</th>
                                    </tr>
                                </thead>
                                <tbody id="pdfTableBody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-spinner fa-spin"></i> लोड हो रहा है...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔻 TOAST ALERT --}}
    <div class="position-fixed top-0 end-0 p-3 mt-5" style="z-index: 9999;">
        <div id="toastBox" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-semibold" id="toastMsg">Toast message</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchData();

            document.getElementById("pdfInput").addEventListener("change", function () {
                const file = this.files[0];
                if (!file) return;
                if (file.type !== 'application/pdf') {
                    showToast("⚠️ केवल PDF फाइल अपलोड करें!", "danger");
                    this.value = "";
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    showToast("⚠️ PDF का SIZE 5MB से अधिक है!", "danger");
                    this.value = "";
                }
            });

            document.getElementById('sthayiSampatiPdfForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                const id = document.getElementById('editId').value;
                const url = id ? `/api/sthayi-sampati-pdf/${id}` : '/api/sthayi-sampati-pdf';

                if (id) formData.append('_method', 'PUT');

                const pdfInput = document.querySelector('[name="pdf"]');
                if (!id && (!pdfInput.files || pdfInput.files.length === 0)) {
                    showToast("📄 PDF फाइल अनिवार्य है!", "danger");
                    return;
                }

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                        }
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        if (data.errors) {
                            const errors = Object.values(data.errors).flat().join(" | ");
                            showToast("⚠️ " + errors, "danger");
                            return;
                        }
                        showToast(data.message || "❌ कोई त्रुटि हुई", "danger");
                        return;
                    }

                    showToast(data.message || "✅ सफलतापूर्वक सहेजा गया!", "success");
                    resetForm();
                    fetchData();

                } catch (err) {
                    console.error(err);
                    showToast("❌ सर्वर से संपर्क नहीं हो सका", "danger");
                }
            });
        });

        function showToast(message, type = "primary") {
            const toastEl = document.getElementById("toastBox");
            const toastMsg = document.getElementById("toastMsg");
            toastMsg.textContent = message;
            toastEl.className = `toast align-items-center text-bg-${type} border-0`;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        function fetchData() {
            fetch('/api/sthayi-sampati-pdf')
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('pdfTableBody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-4"><i class="fas fa-inbox"></i> कोई PDF उपलब्ध नहीं है।</td></tr>`;
                        return;
                    }

                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                                <tr>
                                    <td class="text-center fw-bold">${index + 1}</td>
                                    <td><i class="fas fa-file-pdf text-danger me-2"></i>${item.name}</td>
                                    <td class="text-center"><span class="badge bg-info">${item.session}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                            <a href="/storage/${item.pdf}" target="_blank" class="btn btn-success btn-sm d-flex align-items-center gap-1" title="PDF देखें">
                                                <i class="fas fa-eye"></i><span class="d-none d-md-inline">देखें</span>
                                            </a>
                                            <button onclick="editPdf(${item.id})" class="btn btn-warning btn-sm d-flex align-items-center gap-1" title="संपादित करें">
                                                <i class="fas fa-edit"></i><span class="d-none d-md-inline">संपादित</span>
                                            </button>
                                            <button onclick="deletePdf(${item.id})" class="btn btn-danger btn-sm d-flex align-items-center gap-1" title="हटाएं">
                                                <i class="fas fa-trash"></i><span class="d-none d-md-inline">हटाएं</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                    });
                })
                .catch(err => {
                    console.error(err);
                    showToast("❌ डेटा लोड करने में त्रुटि", "danger");
                });
        }

        function editPdf(id) {
            fetch(`/api/sthayi-sampati-pdf/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.querySelector('[name="name"]').value = data.name;
                    document.querySelector('[name="session"]').value = data.session;
                    document.getElementById('editId').value = data.id;
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('pdfInput').removeAttribute('required');
                    showToast("✏️ संपादन मोड सक्रिय", "info");
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                })
                .catch(err => {
                    console.error(err);
                    showToast("❌ डेटा लोड करने में त्रुटि", "danger");
                });
        }

        function deletePdf(id) {
            if (!confirm('क्या आप वाकई इस PDF को हटाना चाहते हैं?')) return;

            fetch(`/api/sthayi-sampati-pdf/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(res => {
                    if (res.ok) {
                        showToast("🗑️ सफलतापूर्वक हटाया गया!", "success");
                        fetchData();
                    } else {
                        return res.json().then(data => {
                            showToast(data.message || "❌ डिलीट में समस्या आई", "danger");
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast("❌ सर्वर से संपर्क नहीं हो सका", "danger");
                });
        }

        function resetForm() {
            document.getElementById('sthayiSampatiPdfForm').reset();
            document.getElementById('editId').value = '';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('pdfInput').setAttribute('required', 'required');
        }
    </script>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .badge {
            padding: 0.5em 0.75em;
            font-size: 0.85rem;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.35rem 0.6rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }

        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            border: none;
            color: #000;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
        }

        @media (max-width: 768px) {
            .btn-sm span {
                display: none !important;
            }
        }
    </style>
@endsection