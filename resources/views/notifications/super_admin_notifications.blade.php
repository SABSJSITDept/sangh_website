@extends('includes.layouts.super_admin')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">📜 Notifications Data</h2>

    <!-- Filter Options -->
    <div class="card p-4 mb-4">
        <div class="row g-3">
            <!-- Last 30 days -->
            <div class="col-md-3">
                <button id="btnLast30" class="btn btn-primary w-100">Last 30 Days</button>
            </div>

            <!-- Year -->
            <div class="col-md-3">
                <select id="filterYear" class="form-select">
                    <option value="">Select Year</option>
                    @for($y = date('Y'); $y >= 2025; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Month -->
            <div class="col-md-3">
                <select id="filterMonth" class="form-select">
                    <option value="">Select Month</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">{{ date("F", mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Group -->
            <div class="col-md-3">
                <select id="filterGroup" class="form-select">
                    <option value="">Select Group</option>
                    <option value="Shree Sangh">Shree Sangh</option>
                    <option value="Mahila Samiti">Mahila Samiti</option>
                    <option value="Yuva Sangh">Yuva Sangh</option>
                </select>
            </div>

            <!-- Fetch Buttons -->
            <div class="col-md-3">
                <button id="btnFetchYearMonth" class="btn btn-success w-100">Fetch Year/Month</button>
            </div>
            <div class="col-md-3">
                <button id="btnFetchGroup" class="btn btn-warning w-100">Fetch By Group</button>
            </div>
            <!-- Reset Filters -->
            <div class="col-md-3">
                <button id="btnReset" class="btn btn-secondary w-100" type="button">Reset Filters</button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Group</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Image</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="notificationTable">
                    <tr><td colspan="6" class="text-center">No records yet</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("notificationTable");

    // Function to render notifications
    function renderTable(data) {
        tableBody.innerHTML = "";
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center">No records found</td></tr>`;
        } else {
            data.forEach((n, i) => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${i+1}</td>
                        <td>${n.group}</td>
                        <td>${n.title}</td>
                        <td class="preview-body">${n.body}</td>
                        <td>${n.image ? `<img src="${n.image}" style="height:40px;border-radius:6px"/>` : '-'}</td>
                        <td>${new Date(n.created_at).toLocaleString()}</td>
                    </tr>`;
            });
        }
    }

    // Fetch last 30 days
    document.getElementById("btnLast30").addEventListener("click", () => {
        fetch(`/api/notifications/last-30-days`)
            .then(res => res.json())
            .then(data => renderTable(data));
    });

    // Fetch year + month
    document.getElementById("btnFetchYearMonth").addEventListener("click", () => {
        const year = document.getElementById("filterYear").value;
        const month = document.getElementById("filterMonth").value;

        fetch(`/api/notifications?year=${year}&month=${month}`)
            .then(res => res.json())
            .then(data => renderTable(data));
    });

    // Fetch group + year + month
    document.getElementById("btnFetchGroup").addEventListener("click", () => {
        const group = document.getElementById("filterGroup").value;
        const year = document.getElementById("filterYear").value;
        const month = document.getElementById("filterMonth").value;

        if (!group) {
            alert("Please select a group first!");
            return;
        }

        fetch(`/api/notifications/filter?group=${group}&year=${year}&month=${month}`)
            .then(res => res.json())
            .then(data => renderTable(data));
    });

    // Reset filters
    document.getElementById("btnReset").addEventListener("click", () => {
        document.getElementById("filterYear").value = "";
        document.getElementById("filterMonth").value = "";
        document.getElementById("filterGroup").value = "";
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center">No records yet</td></tr>`;
    });
});
</script>
@endsection
