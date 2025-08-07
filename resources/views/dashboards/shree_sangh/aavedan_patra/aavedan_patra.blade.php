@extends('includes.layouts.shree_sangh')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
<style>
    h5 {
        background-color: #f0f0f0;
        padding: 10px;
        border-left: 5px solid #0d6efd;
    }

    table th, table td {
        vertical-align: middle !important;
    }
</style>

<div class="container mt-4">
    <h4 class="mb-4 text-center">📋 आवेदन पत्र</h4>

    <!-- 🔽 Add/Edit Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">➕ Add / Edit Aavedan Patra</div>
        <div class="card-body">
            <form id="aavedanForm">
                <input type="hidden" id="edit_id">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label>नाम</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>

                    <div class="col-md-6">
                        <label>कैटेगरी</label>
                       <select class="form-select" id="category" required>
    <option value="">चुनें</option>
    <option value="sangh_membership">संघ सदस्यता आवेदन-पत्र</option>
    <option value="vishisht_membership">अन्य विशिष्ट सदस्यता आवेदन-पत्र</option>
    <option value="anya_membership">अन्य सदस्यता आवेदन-पत्र</option>
    <option value="pathshala">पाठशाला आवेदन-पत्र</option>
    <option value="shivir">शिविर आवेदन-पत्र</option>
    <option value="swadhyayee_registration">स्वाध्यायी पंजीकरण आवेदन-पत्र</option>
    <option value="ganesh_jain_hostel">गणेश जैन छात्रावास</option>
    <option value="samata_trust">श्री समता जनकल्याण प्रन्यास</option>
    <option value="samata_scholarship">समता छात्रवृत्ति आवेदन-पत्र</option>
    <option value="acharya_shrilal_yojana">पूज्य आचार्य श्री श्रीलाल उच्च शिक्षा योजना आवेदन-पत्र</option>
    <option value="acharya_nanesh_award">आचार्य श्री नानेश समता पुरस्कार हेतु प्रविष्टियाँ आमंत्रित</option>
    <option value="champalal_award">सेठ श्री चम्पालाल सांड स्मृति उच्च प्रशासनिक पुरस्कार</option>
    <option value="pradeep_rampuria_award">स्व. श्री प्रदीप कुमार रामपुरिया स्मृति साहित्य पुरस्कार प्रतियोगिता आवेदन प्रपत्र</option>
    <option value="exam">परीक्षा आवेदन-पत्र</option>
    <option value="other">अन्य आवेदन-पत्र</option>
    <option value="report">प्रतिवेदन</option>
  </select>

                    </div>

                    <div class="col-md-6">
                        <label>प्राथमिकता</label>
                        <input type="number" class="form-control" id="preference" min="0" value="0">
                    </div>

                    <div class="col-md-6">
                        <label>फ़ाइल का प्रकार</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_type" id="fileTypePdf" value="pdf" checked>
                            <label class="form-check-label" for="fileTypePdf">Offline</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_type" id="fileTypeGoogle" value="google_form">
                            <label class="form-check-label" for="fileTypeGoogle">Online</label>
                        </div>
                    </div>

                    <div class="col-md-6" id="pdfInputGroup">
                        <label>PDF फ़ाइल</label>
                        <input type="file" class="form-control" id="fileInput" accept=".pdf">
                    </div>

                    <div class="col-md-6 d-none" id="googleInputGroup">
                        <label>Google Form लिंक</label>
                        <input type="text" class="form-control" id="googleFormLink" placeholder="https://...">
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success mt-3">💾 Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 📋 Filter and Table -->
   <!-- Filter Dropdown for Listing -->
  <div class="mt-4">
    <label>कैटेगरी अनुसार आवेदन पत्र दिखाएं:</label>
    <select id="filterCategory" class="form-select w-auto d-inline-block ms-2">
        <option value="">सभी</option>
        <option value="sangh_membership">संघ सदस्यता आवेदन‑पत्र</option>
        <option value="vishisht_membership">अन्य विशिष्ट सदस्यता आवेदन‑पत्र</option>
        <option value="anya_membership">अन्य सदस्यता आवेदन‑पत्र</option>
        <option value="pathshala">पाठशाला आवेदन‑पत्र</option>
        <option value="shivir">शिविर आवेदन‑पत्र</option>
        <option value="swadhyayee_registration">स्वाध्यायी पंजीकरण आवेदन‑पत्र</option>
        <option value="ganesh_jain_hostel">गणेश जैन छात्रावास</option>
        <option value="samata_trust">श्री समता जनकल्याण प्रन्यास</option>
        <option value="samata_scholarship">समता छात्रवृत्ति आवेदन‑पत्र</option>
        <option value="acharya_shrilal_yojana">पूज्य आचार्य श्री श्रीलाल उच्च शिक्षा योजना आवेदन‑पत्र</option>
        <option value="acharya_nanesh_award">आचार्य श्री नानेश समता पुरस्कार हेतु प्रविष्टियाँ आमंत्रित</option>
        <option value="champalal_award">सेठ श्री चम्पालाल सांड स्मृति उच्च प्रशासनिक पुरस्कार</option>
        <option value="pradeep_rampuria_award">स्व. श्री प्रदीप कुमार रामपुरिया स्मृति साहित्य पुरस्कार प्रतियोगिता आवेदन प्रपत्र</option>
        <option value="exam">परीक्षा आवेदन‑पत्र</option>
        <option value="other">अन्य आवेदन‑पत्र</option>
        <option value="report">प्रतिवेदन</option>
    </select>
    <label class="mt-3">प्रकार अनुसार आवेदन पत्र दिखाएं:</label>
  <select id="filterFileType" class="form-select w-auto d-inline-block ms-2">
    <option value="">सभी</option>
    <option value="online">केवल Online</option>
    <option value="offline">केवल Offline</option>
   </select>
  </div>


    <div class="card mt-3">
        <div class="card-header bg-secondary text-white">🗂️ आवेदन पत्र सूची</div>
        <div class="card-body" id="groupedTablesContainer">
            <p class="text-muted">कैटेगरी अनुसार सूची नीचे देखें:</p>
        </div>
        
    </div>
    
</div>

{{-- Dependencies --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    toggleFileInputs();
    fetchData();

    document.getElementById('filterCategory').addEventListener('change', function() {
        const selectedCategory = this.value;
        fetchData(selectedCategory);
    });
});

document.querySelectorAll('input[name="file_type"]').forEach(input => {
    input.addEventListener('change', toggleFileInputs);
});

function toggleFileInputs() {
    const type = document.querySelector('input[name="file_type"]:checked').value;
    document.getElementById('pdfInputGroup').classList.toggle('d-none', type !== 'pdf');
    document.getElementById('googleInputGroup').classList.toggle('d-none', type !== 'google_form');
}

function fetchData(category = '') {
    const url = category ? `/api/aavedan-patra/${category}` : '/api/aavedan-patra';
    axios.get(url).then(res => {
        const data = res.data;
        const container = document.getElementById('groupedTablesContainer');
        container.innerHTML = '';

        if (!data.length) {
            container.innerHTML = '<p class="text-danger">इस कैटेगरी में कोई आवेदन पत्र नहीं मिला।</p>';
            return;
        }

        let rows = '';
        data.forEach(item => {
            const fileLink = item.file_type === 'pdf'
                ? `<a href="/storage/aavedan_patra/${item.file}" target="_blank">📎 PDF</a>`
                : `<a href="${item.file}" target="_blank">🔗 Google Form</a>`;

            rows += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.file_type}</td>
                    <td>${fileLink}</td>
                    <td>${item.preference}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editItem(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                </tr>`;
        });

        container.innerHTML = `
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>नाम</th>
                        <th>प्रकार</th>
                        <th>फ़ाइल</th>
                        <th>प्राथमिकता</th>
                        <th>एक्शन</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
            </table>`;
    });
}

document.getElementById('aavedanForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('edit_id').value;
    const fileType = document.querySelector('input[name="file_type"]:checked').value;
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('category', document.getElementById('category').value);
    formData.append('preference', document.getElementById('preference').value || 0);
    formData.append('file_type', fileType);

    const fileInput = document.getElementById('fileInput').files[0];
    const googleFormLink = document.getElementById('googleFormLink').value;

    if (fileType === 'pdf') {
        if (!fileInput && !id) {
    Swal.fire({
        icon: 'warning',
        title: 'ध्यान दें!',
        text: 'कृपया PDF चुनें।'
    });
    return;
}

        if (fileInput) formData.append('file', fileInput);
    } else {
      if (!googleFormLink) {
    Swal.fire({
        icon: 'warning',
        title: 'ध्यान दें!',
        text: 'Google Form लिंक डालें।'
    });
    return;
}

        formData.append('file', googleFormLink);
    }

    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
    };

    const method = 'post';
    const url = id ? `/api/aavedan-patra/${id}` : '/api/aavedan-patra';
    if (id) formData.append('_method', 'PUT');

   axios({ method, url, data: formData, headers }).then(() => {
    document.getElementById('aavedanForm').reset();
    document.getElementById('edit_id').value = '';
    toggleFileInputs();
    document.getElementById('filterCategory').value = ''; // reset filter
    fetchData(); // reload all

    // ✅ Success Alert
    Swal.fire({
        icon: 'success',
        title: 'सफलता!',
        text: 'आवेदन पत्र सफलतापूर्वक सहेजा गया।',
        timer: 2000,
        showConfirmButton: false
    });
}).catch(error => {
    // ✅ Error Alert
    Swal.fire({
        icon: 'error',
        title: 'त्रुटि!',
        text: 'कुछ गलत हो गया। कृपया पुनः प्रयास करें।',
    });
});

});

function editItem(id) {
    axios.get('/api/aavedan-patra').then(res => {
        const data = res.data.find(i => i.id === id);
        if (!data) return alert("डेटा नहीं मिला");

        document.getElementById('edit_id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('category').value = data.category;
        document.getElementById('preference').value = data.preference;

        if (data.file_type === 'google_form') {
            document.getElementById('fileTypeGoogle').checked = true;
            document.getElementById('googleFormLink').value = data.file;
        } else {
            document.getElementById('fileTypePdf').checked = true;
        }

        toggleFileInputs();
    });
}
document.addEventListener('DOMContentLoaded', () => {
    toggleFileInputs();
    fetchData();

    document.getElementById('filterCategory').addEventListener('change', function() {
        fetchData();
    });

    document.getElementById('filterFileType').addEventListener('change', function() {
        fetchData();
    });
});

function fetchData() {
    const selectedCategory = document.getElementById('filterCategory').value;
    const selectedFileType = document.getElementById('filterFileType').value;

    let url = '/api/aavedan-patra';

    if (selectedFileType === 'online') {
        url = '/api/aavedan-patra-online';
    } else if (selectedFileType === 'offline') {
        url = '/api/aavedan-patra-offline';
    }

    if (selectedCategory && selectedFileType === '') {
        url = `/api/aavedan-patra/${selectedCategory}`;
    }

    axios.get(url).then(res => {
        let data = res.data;

        // अगर कैटेगरी और फाइल-टाइप दोनों चुने गए हैं
        if (selectedCategory && selectedFileType !== '') {
            data = data.filter(item => item.category === selectedCategory);
        }

        const container = document.getElementById('groupedTablesContainer');
        container.innerHTML = '';

        if (!data.length) {
            container.innerHTML = '<p class="text-danger">इस चयन के अनुसार कोई आवेदन पत्र नहीं मिला।</p>';
            return;
        }

        let rows = '';
        data.forEach(item => {
            const fileLink = item.file_type === 'pdf'
                ? `<a href="/storage/aavedan_patra/${item.file}" target="_blank">📎 PDF</a>`
                : `<a href="${item.file}" target="_blank">🔗 Google Form</a>`;

            rows += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.file_type}</td>
                    <td>${fileLink}</td>
                    <td>${item.preference}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editItem(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                </tr>`;
        });

        container.innerHTML = `
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>नाम</th>
                        <th>प्रकार</th>
                        <th>फ़ाइल</th>
                        <th>प्राथमिकता</th>
                        <th>एक्शन</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
            </table>`;
    });
}

function deleteItem(id) {
    Swal.fire({
        title: 'क्या आप वाकई हटाना चाहते हैं?',
        text: "यह क्रिया पूर्ववत नहीं की जा सकती!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'हाँ, हटाएं!',
        cancelButtonText: 'नहीं'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/aavedan-patra/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                const selected = document.getElementById('filterCategory').value;
                fetchData(selected);

                Swal.fire({
                    icon: 'success',
                    title: 'हटा दिया गया!',
                    text: 'आवेदन पत्र सफलतापूर्वक हटा दिया गया।',
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'त्रुटि!',
                    text: 'हटाने में समस्या आई। कृपया पुनः प्रयास करें।'
                });
            });
        }
    });
}

</script>
@endsection
