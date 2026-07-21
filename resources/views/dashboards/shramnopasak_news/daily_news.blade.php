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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State ID</label>
                            <input type="number" name="state_id" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City ID</label>
                            <input type="number" name="city_id" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Anchal ID</label>
                            <input type="number" name="anchal_id" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Local Sangh ID</label>
                            <input type="number" name="local_sangh_id" class="form-control">
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
@endsection
