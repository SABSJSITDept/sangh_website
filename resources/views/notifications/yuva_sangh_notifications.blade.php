@extends('includes.layouts.super_admin')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">📜 Yuva Sangh Notifications</h2>

    <!-- Filter Options -->
    <div class="card p-4 mb-4">
        <div class="row g-3">
            <!-- Year -->
            <div class="col-md-3">
                <select id="filterYear" class="form-select">
                    <option value="">Select Year</option>
                    @for($y = date('Y'); $y >= 2020; $y--)
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

            <!-- Fetch Button -->
            <div class="col-md-3">
                <button id="btnFetchYearMonth" class="btn btn-success w-100">Fetch Data</button>
            </div>

            <!-- Reset Button -->
            <div class="col-md-3">
                <button id="btnReset" class="btn btn-secondary w-100" type="button">Reset</button>
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
                    <tr><td colspan="6" class="text-center">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("notificationTable");
    const yearSelect = document.getElementById("filterYear");
    const monthSelect = document.getElementById("filterMonth");

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
                        <td>${n.body}</td>
                        <td>${n.image ? `<img src="${n.image}" style="height:40px;border-radius:6px"/>` : '-'}</td>
                        <td>${new Date(n.created_at).toLocaleString()}</td>
                    </tr>`;
            });
        }
    }

    function loadLast30Days() {
        fetch(`/api/notifications/filter?group=Yuva Sangh`)
            .then(res => res.json())
            .then(data => {
                const last30 = data.filter(n => new Date(n.created_at) >= new Date(Date.now() - 30*24*60*60*1000));
                renderTable(last30);
            });
    }

    loadLast30Days();

    document.getElementById("btnFetchYearMonth").addEventListener("click", () => {
        const year = yearSelect.value;
        const month = monthSelect.value;

        fetch(`/api/notifications/filter?group=Yuva Sangh&year=${year}&month=${month}`)
            .then(res => res.json())
            .then(data => renderTable(data));
    });

    document.getElementById("btnReset").addEventListener("click", () => {
        yearSelect.value = "";
        monthSelect.value = "";
        loadLast30Days();
    });
});
</script>
@endsection
    