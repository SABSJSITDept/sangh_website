@extends('includes.layouts.shree_sangh')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bulk Upload JSP Results</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- XLSX library for Excel download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        .toast-container { position: fixed; top: 70px; right: 20px; z-index: 9999; }
        .spinner-border { display: none; }
        #loaderOverlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(255,255,255,0.85);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        #loaderOverlay .loader-content {
            text-align: center;
        }
        #loaderOverlay .spinner-border {
            width: 4rem; height: 4rem;
            color: #0d6efd;
        }
        #loaderOverlay .loader-text {
            margin-top: 18px;
            font-size: 1.3rem;
            color: #0d6efd;
            font-weight: 600;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
<div id="loaderOverlay">
    <div class="loader-content">
        <div class="spinner-border" role="status"></div>
        <div class="loader-text">Uploading, please wait...</div>
    </div>
</div>
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Bulk Upload JSP Results (Excel)</h2>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="classSelect" class="form-label">Select Class</label>
                <select id="classSelect" class="form-select" style="max-width:300px;">
                    <option value="">Select Class</option>
                    <option value=" 1">Class 1</option>
                    <option value=" 2">Class 2</option>
                    <option value=" 3">Class 3</option>
                    <option value=" 4">Class 4</option>
                    <option value=" 5">Class 5</option>
                    <option value=" 6">Class 6</option>
                    <option value=" 7">Class 7</option>
                    <option value=" 8">Class 8</option>
                    <option value=" 9">Class 9</option>
                    <option value=" 9">Class 9</option>
                    <option value=" 10">Class 10</option>
                    <option value=" 11 Aagam">Class 11 Aagam</option>
                    <option value=" 11 Tatwa">Class 11 Tatwa</option>
                    <option value=" 12 Aagam">Class 12 Aagam</option>
                    <option value=" 12 Tatwa">Class 12 Tatwa</option>
                </select>
            </div>
            <button type="button" class="btn btn-info mb-3" id="downloadFormat">
                <i class="fas fa-download"></i> Download Sample Excel Format
            </button>
            <form id="importForm" enctype="multipart/form-data" class="mb-4">
                <div class="input-group">
                    <input type="file" name="excel" id="excelFile" accept=".xlsx,.xls" class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Import Excel
                    </button>
                </div>
            </form>
            <form id="bulkUploadForm" class="mb-4" style="display:none;">
                <div id="previewTable"></div>
                <button type="submit" class="btn btn-success mt-3">
                    <i class="fas fa-upload"></i> Upload All
                </button>
            </form>
        </div>
    </div>
</div>
<div class="toast-container" id="toastContainer"></div>
<script>
const apiUrl = '/api/jsp-result';
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0 show`;
    toast.role = 'alert';
    toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// Download sample Excel format
document.getElementById('downloadFormat').addEventListener('click', function() {
    const ws = XLSX.utils.aoa_to_sheet([
        ['Student_Name', 'Guardian_Name', 'Mobile', 'City', 'State',  'Marks', 'Rank', 'Remarks'],
        ['', '', '', '', '', '', '', '', '']
    ]);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'JSP Results');
    XLSX.writeFile(wb, 'jsp_result_sample.xlsx');
    showToast('Sample Excel downloaded', 'success');
});

// Import Excel file and show preview
document.getElementById('importForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const file = document.getElementById('excelFile').files[0];
    if (!file) {
        showToast('Please select an Excel file', 'warning');
        return;
    }
    const spinner = document.querySelector('#importForm .spinner-border');
    spinner.style.display = 'inline-block';
    const reader = new FileReader();
    reader.onload = function(evt) {
        try {
            const data = new Uint8Array(evt.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            const sheet = workbook.Sheets[workbook.SheetNames[0]];
            const rows = XLSX.utils.sheet_to_json(sheet, {header:1});
            if (rows.length < 2) {
                showToast('Excel file is empty or missing data', 'danger');
                return;
            }
            
            let html = `<table class='table table-bordered'><thead><tr>`;
            rows[0].forEach(h => html += `<th>${h}</th>`);
            html += `</tr></thead><tbody>`;
            for (let i = 1; i < rows.length; i++) {
                html += `<tr>`;
                for (let j = 0; j < rows[0].length; j++) {
                    const val = rows[i][j] !== undefined ? rows[i][j] : '';
                    html += `<td><input type='text' name='row[${i-1}][${rows[0][j]}]' value='${val}' class='form-control form-control-sm'></td>`;
                }
                html += `</tr>`;
            }
            html += `</tbody></table>`;
            document.getElementById('previewTable').innerHTML = html;
            document.getElementById('bulkUploadForm').style.display = 'block';
            showToast('Excel imported successfully. Review and edit if needed', 'success');
        } catch(error) {
            showToast('Error reading Excel file: ' + error.message, 'danger');
            console.error(error);
        } finally {
            spinner.style.display = 'none';
        }
    };
    reader.readAsArrayBuffer(file);
});

document.getElementById('bulkUploadForm').onsubmit = function(e) {
    e.preventDefault();
    const classValue = document.getElementById('classSelect').value;
    if (!classValue) {
        showToast('Please select a class', 'warning');
        return;
    }
    const loader = document.getElementById('loaderOverlay');
    loader.style.display = 'flex';
    const inputs = document.querySelectorAll('#previewTable input');
    const rows = {};
    inputs.forEach(input => {
        const match = input.name.match(/row\[(\d+)\]\[(.+)\]/);
        if (match) {
            const idx = match[1], key = match[2];
            if (!rows[idx]) rows[idx] = {};
            rows[idx][key] = input.value || '';
        }
    });
    // Add class to each row
    Object.keys(rows).forEach(key => {
        rows[key]['Class'] = classValue;
    });
    // Serialize and send data correctly during submission
    const dataToSend = Object.values(rows).filter(row => {
        return Object.values(row).some(val => val.trim() !== ''); // Ensure empty rows are excluded
    });

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ bulk: dataToSend })
    })
    .then(res => res.json())
    .then(data => {
        loader.style.display = 'none';
        if (data.success) {
            showToast('Bulk upload successful - ' + data.message, 'success');
            document.getElementById('bulkUploadForm').style.display = 'none';
            document.getElementById('previewTable').innerHTML = '';
            document.getElementById('importForm').reset();
        } else {
            let errorMsg = data.message || 'Bulk upload failed';
            if (data.errors && Array.isArray(data.errors)) {
                errorMsg = 'Validation errors:\n' + data.errors.map(e => `Row ${e.row}: ` + e.errors.join(', ')).join('\n');
            }
            showToast(errorMsg, 'danger');
        }
    })
    .catch(err => {
        loader.style.display = 'none';
        showToast('Bulk upload failed: ' + err.message, 'danger');
        console.error(err);
    });
};
function fetchResults() {
    fetch(apiUrl, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            let html = `<table class='table table-bordered'><thead><tr><th>ID</th><th>Student Name</th><th>Guardian Name</th><th>Mobile</th><th>City</th><th>State</th><th>Class</th><th>Marks</th><th>Rank</th><th>Remarks</th></tr></thead><tbody>`;
            data.forEach(row => {
                html += `<tr>
                    <td>${row.id}</td>
                    <td>${row.Student_Name||''}</td>
                    <td>${row.Guardian_Name||''}</td>
                    <td>${row.Mobile||''}</td>
                    <td>${row.City||''}</td>
                    <td>${row.State||''}</td>
                    <td>${row.Class||''}</td>
                    <td>${row.Marks||''}</td>
                    <td>${row.Rank||''}</td>
                    <td>${row.Remarks||''}</td>
                </tr>`;
            });
            html += '</tbody></table>';
            document.getElementById('resultsTable').innerHTML = html;
        });
}
fetchResults();
</script>
</body>
</html>
@endsection
