@extends('includes.layouts.sahitya')

@section('content')
<div class="container mt-4 mb-5">
    <h2 class="mb-4 text-center">📰 Daily News Management</h2>

    <div class="row mb-5">
        <!-- Form Section -->
        <div class="col-md-12">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Add New Entry</h5>
                <form id="dailyNewsForm" enctype="multipart/form-data">
                    <input type="hidden" id="editId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Select Branch (Local Sangh)</label>
                            <select id="branchSelect" class="form-select" required>
                                <option value="">Loading branches...</option>
                            </select>
                            
                            <!-- Hidden inputs for saving to DB -->
                            <input type="hidden" name="local_sangh_id" id="local_sangh_id">
                            <input type="hidden" name="state_id" id="state_id">
                            <input type="hidden" name="anchal_id" id="anchal_id">
                            <input type="hidden" name="city_id" id="city_id">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Anchal</label>
                            <input type="text" id="display_anchal" class="form-control" readonly placeholder="Auto-filled">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" id="display_state" class="form-control" readonly placeholder="Auto-filled">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" id="display_city" class="form-control" readonly placeholder="Auto-filled">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" id="submitBtn">Save Entry</button>
                    <button type="button" class="btn btn-secondary ms-2 d-none" id="cancelEdit">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm p-3">
        <h5 class="mb-3">Daily News List</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Likes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="newsTableBody">
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const branchSelect = document.getElementById('branchSelect');
    let citiesMap = {}; // Maps lowercase city name to city_id
    
    // Fetch cities to map city_name -> city_id
    fetch('https://mrm.sadhumargi.org/api/cities')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cities) {
                data.cities.forEach(city => {
                    // Normalize to lowercase for robust matching
                    citiesMap[city.city_name.toLowerCase().trim()] = city.city_id;
                });
            }
        })
        .catch(error => console.error('Error fetching cities:', error));
    
    // Fetch branches from external API
    fetch('https://mrm.sadhumargi.org/api/branches')
        .then(response => response.json())
        .then(data => {
            if(data && data.branches) {
                branchSelect.innerHTML = '<option value="">-- Select a Branch --</option>';
                data.branches.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = `${branch.branch_name} (${branch.branch_name_hin})`;
                    
                    // Store extra data in dataset for quick access
                    option.dataset.stateId = branch.state_id || '';
                    option.dataset.stateName = branch.state_name || '';
                    option.dataset.anchalId = branch.anchal_id || '';
                    option.dataset.anchalName = branch.anchal_name || '';
                    option.dataset.city = branch.city || '';
                    
                    branchSelect.appendChild(option);
                });
            } else {
                branchSelect.innerHTML = '<option value="">No branches found</option>';
            }
        })
        .catch(error => {
            console.error('Error fetching branches:', error);
            branchSelect.innerHTML = '<option value="">Error loading branches</option>';
        });
        
    // Update hidden inputs and read-only displays when a branch is selected
    branchSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        
        if (selected.value) {
            document.getElementById('local_sangh_id').value = selected.value;
            document.getElementById('state_id').value = selected.dataset.stateId;
            document.getElementById('anchal_id').value = selected.dataset.anchalId;
            
            const cityName = selected.dataset.city || "";
            // Look up city_id from the map
            const cityId = cityName ? citiesMap[cityName.toLowerCase().trim()] : "";
            document.getElementById('city_id').value = cityId || "";
            
            document.getElementById('display_anchal').value = selected.dataset.anchalName;
            document.getElementById('display_state').value = selected.dataset.stateName;
            document.getElementById('display_city').value = cityName;
        } else {
            document.getElementById('local_sangh_id').value = '';
            document.getElementById('state_id').value = '';
            document.getElementById('anchal_id').value = '';
            document.getElementById('city_id').value = '';
            
            document.getElementById('display_anchal').value = '';
            document.getElementById('display_state').value = '';
            document.getElementById('display_city').value = '';
        }
    });

    // --- NEW: AJAX Logic for CRUD ---
    
    function fetchNewsTable() {
        fetch('/shramnopasak/daily-news/fetch')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('newsTableBody');
                tbody.innerHTML = '';
                if(data.data) {
                    data.data.forEach(news => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${news.id}</td>
                            <td>${news.photo ? `<img src="/${news.photo}" width="50" height="50" style="object-fit: cover;">` : 'No Image'}</td>
                            <td>${news.title}</td>
                            <td>${news.date}</td>
                            <td>${news.description ? news.description.substring(0, 50) + '...' : ''}</td>
                            <td>${news.like_count}</td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${news.id}">Delete</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            });
    }

    // Load table on page load
    fetchNewsTable();

    // Handle form submit via AJAX
    document.getElementById('dailyNewsForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent page refresh!
        
        let formData = new FormData(this);
        let editId = document.getElementById('editId').value;
        
        let url = '/shramnopasak/daily-news/store';
        if (editId) {
            url = `/shramnopasak/daily-news/update/${editId}`;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                this.reset(); // Clear the form
                document.getElementById('editId').value = '';
                document.getElementById('branchSelect').selectedIndex = 0; // Reset dropdown
                
                // Reset display fields
                document.getElementById('display_anchal').value = '';
                document.getElementById('display_state').value = '';
                document.getElementById('display_city').value = '';

                fetchNewsTable(); // Refresh the table
            } else {
                alert('Error saving news');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong!');
        });
    });

    // Handle delete via AJAX event delegation
    document.getElementById('newsTableBody').addEventListener('click', function(e) {
        if(e.target.classList.contains('delete-btn')) {
            if(confirm('Are you sure you want to delete this news?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/shramnopasak/daily-news/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        alert(data.message);
                        fetchNewsTable(); // Refresh table
                    }
                })
                .catch(err => console.error('Delete error:', err));
            }
        }
    });
});
</script>
@endsection
