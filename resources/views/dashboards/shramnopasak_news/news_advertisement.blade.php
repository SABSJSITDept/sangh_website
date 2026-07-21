@extends('includes.layouts.sahitya')

@section('content')
<div class="container mt-4 mb-5">
    <h2 class="mb-4 text-center">📢 News Advertisement Management</h2>

    <div class="row mb-5">
        <!-- Form Section -->
        <div class="col-md-12">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Add New Advertisement</h5>
                <form id="newsAdForm" enctype="multipart/form-data">
                    <input type="hidden" id="editId">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Advertisement Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" id="submitBtn">Save Advertisement</button>
                    <button type="button" class="btn btn-secondary ms-2 d-none" id="cancelEdit">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm p-3">
        <h5 class="mb-3">Advertisements List</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="adsTableBody">
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
