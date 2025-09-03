@extends('includes.layouts.super_admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h2 class="mb-4">📱 App Version Management</h2>

    <!-- 🔹 Latest Version Card -->
    <div id="latestVersionCard" class="mb-4"></div>

    <!-- Add Version Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="versionForm">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="version_code" class="col-form-label">Version Code</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="version_code" name="version_code" class="form-control" placeholder="e.g. 1.0.5" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">➕ Add Version</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Versions Table -->
    <div class="card">
        <div class="card-body">
            <h5>All Versions</h5>
            <table class="table table-bordered mt-3" id="versionsTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Version Code</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic Data -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetchLatestVersion();
    fetchAllVersions();

    // 🔹 Add Version
    document.getElementById("versionForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let versionCode = document.getElementById("version_code").value;

        fetch("{{ url('/api/versions') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ version_code: versionCode }),
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire("✅ Success", data.message, "success");
            document.getElementById("versionForm").reset();
            fetchLatestVersion();
            fetchAllVersions();
        })
        .catch(err => Swal.fire("❌ Error", "Something went wrong!", "error"));
    });

    // 🔹 Fetch Latest Version
    function fetchLatestVersion() {
        fetch("{{ url('/api/latest-version') }}")
            .then(res => res.json())
            .then(data => {
                if (data.version_code) {
                    document.getElementById("latestVersionCard").innerHTML = `
                        <div class="card border-success shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-success">🚀 Latest Version</h5>
                                <p class="card-text">
                                    <strong>Version Code:</strong> ${data.version_code}<br>
                                    <strong>Checked At:</strong> ${new Date().toLocaleString()}
                                </p>
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById("latestVersionCard").innerHTML = `
                        <div class="alert alert-warning">⚠️ No latest version found</div>
                    `;
                }
            })
            .catch(() => {
                document.getElementById("latestVersionCard").innerHTML = `
                    <div class="alert alert-danger">❌ Failed to load latest version</div>
                `;
            });
    }

    // 🔹 Fetch All Versions
    function fetchAllVersions() {
        fetch("{{ url('/api/versions') }}")
            .then(res => res.json())
            .then(data => {
                let tbody = document.querySelector("#versionsTable tbody");
                tbody.innerHTML = "";

                if (data.length > 0) {
                    data.forEach(version => {
                        let row = `
                            <tr>
                                <td>${version.id}</td>
                                <td>${version.version_code}</td>
                                <td>${new Date(version.created_at).toLocaleString()}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="deleteVersion(${version.id})">🗑 Delete</button>
                                </td>
                            </tr>`;
                        tbody.innerHTML += row;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">No versions found</td></tr>`;
                }
            });
    }

    // 🔹 Delete Version
    window.deleteVersion = function (id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won’t be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ url('/api/versions') }}/" + id, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire("✅ Deleted", data.message, "success");
                    fetchLatestVersion();
                    fetchAllVersions();
                })
                .catch(err => Swal.fire("❌ Error", "Failed to delete!", "error"));
            }
        });
    }
});
</script>
@endsection
