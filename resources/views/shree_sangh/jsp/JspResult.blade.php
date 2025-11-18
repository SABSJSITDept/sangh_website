@extends('includes.layouts.shree_sangh')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JSP Results — Manage & Fetch</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body { background: #f6f8fb; font-family: Inter, Arial, sans-serif; }
        .page-header { background: linear-gradient(90deg,#0d6efd 0%, #6610f2 100%); color: white; padding: 28px; border-radius: 12px; }
        .card-rounded { border-radius: 12px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
        .form-label { font-weight: 600; font-size: .92rem; }
        .small-muted { color: #6b7280; font-size: .85rem; }
        .table-responsive { max-height: 420px; overflow: auto; }
        #toastContainer { position: fixed; top: 20px; right: 20px; z-index: 1080; }
        .logo-small { width: 70px; height: 70px; object-fit: contain; }
        @media (max-width: 575px) { .logo-small { width: 56px; height: 56px; } }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="page-header mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="h3 mb-1">JSP Results Management</h1>
            <div class="small-muted">Add, edit, delete results and allow students to fetch their JSP result card.</div>
        </div>
        <div class="text-end">
            <img src="/images/logo.png" alt="Logo" class="logo-small rounded-circle bg-white p-1">
        </div>
    </div>

    <div class="card card-rounded p-3">
        <ul class="nav nav-tabs mb-3" id="jspTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="add-data-tab" data-bs-toggle="tab" data-bs-target="#add-data" type="button" role="tab" aria-controls="add-data" aria-selected="true">Add / Edit Data</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="fetch-result-tab" data-bs-toggle="tab" data-bs-target="#fetch-result" type="button" role="tab" aria-controls="fetch-result" aria-selected="false">Fetch Result</button>
            </li>
        </ul>

        <div class="tab-content" id="jspTabsContent">
            <!-- Add / Edit Tab -->
            <div class="tab-pane fade show active" id="add-data" role="tabpanel" aria-labelledby="add-data-tab">
                <form id="jspResultForm" class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Student Name</label>
                        <input type="text" name="Student_Name" class="form-control" placeholder="Enter student name" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Guardian Name</label>
                        <input type="text" name="Guardian_Name" class="form-control" placeholder="Guardian / Parent name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mobile</label>
                        <input type="tel" name="Mobile" class="form-control" placeholder="10-digit mobile" pattern="[0-9]{10}" required>
                        <div class="form-text small-muted">Only digits, 10 characters.</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="City" class="form-control" placeholder="City">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input type="text" name="State" class="form-control" placeholder="State">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Class</label>
                        <input type="text" name="Class" class="form-control" placeholder="e.g. Class 5" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Marks</label>
                        <input type="number" name="Marks" class="form-control" placeholder="Marks" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rank</label>
                        <input type="number" name="Rank" class="form-control" placeholder="Rank" min="1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="Remarks" class="form-control" placeholder="Optional remarks">
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Result</button>
                        <button type="button" id="resetFormBtn" class="btn btn-outline-secondary">Reset</button>
                        <div class="ms-auto small-muted align-self-center">Tip: Click a row in the table below to edit.</div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0" id="resultsGrid">
                        <thead class="table-light small-muted">
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Mobile</th>
                                <th>Class</th>
                                <th>Marks</th>
                                <th>Rank</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Fetch Result Tab -->
            <div class="tab-pane fade" id="fetch-result" role="tabpanel" aria-labelledby="fetch-result-tab">
                <div class="card p-3 card-rounded">
                    <form id="fetchResultForm" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Class</label>
                            <select class="form-select" id="class" name="class" required>
                                <option value="">Select Class</option>
                                @for($i=1;$i<=12;$i++)
                                    <option value="Class {{$i}}">Class {{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="10-digit mobile" pattern="[0-9]{10}" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" onclick="fetchResult()">Find Result</button>
                        </div>
                    </form>

                    <div id="result" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div id="toastContainer"></div>

<script>
    const apiUrl = '/api/jsp-result';

    function showToast(message, type = 'primary'){
        const id = 't'+Date.now();
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0 show mb-2`;
        toast.role = 'alert';
        toast.id = id;
        toast.innerHTML = `<div class=\"d-flex\"><div class=\"toast-body\">${message}</div><button type=\"button\" class=\"btn-close btn-close-white me-2 m-auto\" data-bs-dismiss=\"toast\"></button></div>`;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(()=>{ const el = document.getElementById(id); if(el) el.remove(); }, 4500);
    }

    // Fetch and render grid
    async function fetchResults(){
        try{
            const res = await fetch(apiUrl, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            const tbody = document.querySelector('#resultsGrid tbody');
            tbody.innerHTML = '';
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.Student_Name || ''}</td>
                    <td>${row.Mobile || ''}</td>
                    <td>${row.Class || ''}</td>
                    <td>${row.Marks || ''}</td>
                    <td>${row.Rank || ''}</td>
                    <td>
                        <button class='btn btn-sm btn-outline-warning me-1' onclick='editResult(${row.id})'>Edit</button>
                        <button class='btn btn-sm btn-outline-danger' onclick='deleteResult(${row.id})'>Delete</button>
                    </td>`;
                tr.querySelector('td').addEventListener('click', ()=> editResult(row.id));
                tbody.appendChild(tr);
            });
        }catch(err){ console.error(err); showToast('Failed to load results', 'danger'); }
    }

    // Save (create) result
    const jspResultForm = document.getElementById('jspResultForm');
    let editingId = null;

    jspResultForm.addEventListener('submit', async function(e){
        e.preventDefault();
        const formData = new FormData(this);
        const payload = Object.fromEntries(formData.entries());

        try{
            const method = editingId ? 'POST' : 'POST'; // Laravel accepts POST with _method override for PUT
            const url = editingId ? `${apiUrl}/${editingId}` : apiUrl;

            const headers = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' };
            if(editingId) payload._method = 'PUT';

            const res = await fetch(url, { method: method, headers, body: JSON.stringify(payload) });
            const data = await res.json();
            if(data.success){
                showToast(editingId ? 'Result updated' : 'Result added', 'success');
                this.reset(); editingId = null;
                fetchResults();
            }else{
                const msg = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Save failed');
                showToast(msg, 'danger');
            }
        }catch(err){ console.error(err); showToast('Save failed', 'danger'); }
    });

    document.getElementById('resetFormBtn').addEventListener('click', ()=>{ jspResultForm.reset(); editingId = null; });

    async function editResult(id){
        try{
            const res = await fetch(`${apiUrl}/${id}`, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if(data){
                editingId = id;
                for(const key in data){
                    const el = document.querySelector(`[name="${key}"]`);
                    if(el) el.value = data[key] ?? '';
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
                showToast('Editing result ID '+id, 'info');
            }
        }catch(err){ console.error(err); showToast('Failed to load record', 'danger'); }
    }

    async function deleteResult(id){
        if(!confirm('Delete this result?')) return;
        try{
            const res = await fetch(`${apiUrl}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if(data.success){ showToast('Deleted', 'success'); fetchResults(); } else showToast('Delete failed', 'danger');
        }catch(err){ console.error(err); showToast('Delete failed', 'danger'); }
    }

    // Fetch single result for students
    async function fetchResult(){
        const classValue = document.getElementById('class').value;
        const mobileValue = document.getElementById('mobile').value;
        if(!classValue){ showToast('Please select class', 'warning'); return; }
        try{
            const response = await fetch('/api/get-result', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ class: classValue, mobile: mobileValue }) });
            const resultDiv = document.getElementById('result'); resultDiv.innerHTML = '';
            if(response.ok){
                const data = await response.json();
                const rows = Array.isArray(data.result) ? data.result : (data.result ? [data.result] : []);
                if(rows.length===0){ resultDiv.innerHTML = '<div class="alert alert-warning">No results found.</div>'; return; }

                rows.forEach(row => {
                    const card = document.createElement('div');
                    card.className = 'card card-rounded p-4 mb-3';
                    card.style.backgroundColor = '#ffffff';
                    card.innerHTML = `
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="/images/logo.png" alt="Logo" style="width:90px;height:90px;object-fit:contain;" crossorigin="anonymous"> 
                            <div class="flex-grow-1 text-center"> 
                                <h4 class="mb-1" style="color: #0d6efd; font-weight: 700;">जैन संस्कार पाठ्यक्रम </h4>
                                <h5 class="mb-1" style="font-weight: 600;">श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ</h5>
                                <small class="small-muted" style="color: #6b7280; font-size: 0.85rem;">Result Card</small>
                            </div>
                            <div style="width:90px"></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong style="color: #374151;">Student:</strong> 
                                <span style="color: #1f2937; font-weight: 500;">${row.Student_Name || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #374151;">Guardian:</strong> 
                                <span style="color: #1f2937;">${row.Guardian_Name || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #374151;">Mobile:</strong> 
                                <span style="color: #1f2937;">${row.Mobile || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #374151;">City:</strong> 
                                <span style="color: #1f2937;">${row.City || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #374151;">State:</strong> 
                                <span style="color: #1f2937;">${row.State || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #374151;">Class:</strong> 
                                <span style="color: #1f2937; font-weight: 500;">${row.Class || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #374151;">Marks:</strong> 
                                <span style="color: #059669; font-weight: 700; font-size: 1.15rem;">${row.Marks || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong style="color: #374151;">Rank:</strong> 
                                <span style="color: #0d6efd; font-weight: 700; font-size: 1.15rem;">${row.Rank || 'N/A'}</span>
                            </div>
                            ${row.Remarks ? `<div class="col-12 mt-2">
                                <strong style="color: #374151;">Remarks:</strong> 
                                <span style="color: #1f2937;">${row.Remarks}</span>
                            </div>` : ''}
                            <div class="col-12 mt-3 text-center">
                                <button class="btn btn-primary px-4" onclick="downloadElementAsImage(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download me-2" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                    Download as Image
                                </button>
                            </div>
                        </div>
                        <div class="text-center small-muted mt-4 pt-3 border-top" style="color: #6b7280; font-size: 0.85rem;">
                            This is a system generated result and does not require a signature.<br>
                            <strong style="color: #374151;">— SABSJS IT Department —</strong>
                        </div>`;
                    resultDiv.appendChild(card);
                });
            }else{
                const err = await response.json(); showToast(err.message || 'Error fetching', 'danger');
            }
        }catch(err){ console.error(err); showToast('Error fetching result', 'danger'); }
    }

    function downloadElementAsImage(btn){
        const card = btn.closest('.card');
        const buttonContainer = card.querySelector('.mt-3.text-center');
        
        // Temporarily hide the download button
        buttonContainer.style.display = 'none';
        
        // Generate image with better quality
        html2canvas(card, {
            scale: 2, // Higher quality (double resolution)
            backgroundColor: '#ffffff',
            logging: false,
            useCORS: true,
            allowTaint: true
        }).then(canvas => {
            // Show button again
            buttonContainer.style.display = 'block';
            
            // Get student name for filename
            const studentNameElement = card.querySelector('.row .col-md-6:first-child');
            const studentName = studentNameElement ? 
                studentNameElement.textContent.replace('Student:', '').trim().replace(/\s+/g, '_') : 
                'student';
            
            // Download image
            const link = document.createElement('a');
            link.download = `JSP_Result_${studentName}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            showToast('Result downloaded successfully', 'success');
        }).catch(err => {
            // Show button again in case of error
            buttonContainer.style.display = 'block';
            console.error('Image generation failed:', err);
            showToast('Failed to generate image', 'danger');
        });
    }

    // init
    fetchResults();
</script>
</body>
</html>
@endsection
