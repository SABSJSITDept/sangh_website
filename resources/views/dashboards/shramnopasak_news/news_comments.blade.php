@extends('includes.layouts.sahitya')

@section('content')
<div class="container mt-4 mb-5">
    <h2 class="mb-4 text-center">💬 News Comments Management</h2>

    <div class="row mb-5">
        <!-- Form Section -->
        <div class="col-md-12">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Add New Comment</h5>
                <form id="newsCommentForm">
                    <input type="hidden" id="editId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">News ID</label>
                            <input type="number" name="news_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Member ID (MID)</label>
                            <input type="number" name="mid" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" id="submitBtn">Save Comment</button>
                    <button type="button" class="btn btn-secondary ms-2 d-none" id="cancelEdit">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm p-3">
        <h5 class="mb-3">Comments List</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>News ID</th>
                        <th>Member ID</th>
                        <th>Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="commentsTableBody">
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
