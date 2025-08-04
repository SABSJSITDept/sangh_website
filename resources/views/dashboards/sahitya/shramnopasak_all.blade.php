@extends('includes.layouts.sahitya')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
    .cover-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .year-heading {
        background-color: #f5f5f5;
        font-size: 1.2rem;
        font-weight: bold;
        padding: 10px;
        border: 1px solid #ccc;
        margin-top: 30px;
        margin-bottom: 10px;
    }

    .shram-row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 20px;
        padding-bottom: 20px;
    }

    .shram-card {
        min-width: 180px;
        max-width: 180px;
        flex: 0 0 auto;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .shram-card:hover {
        transform: scale(1.03);
    }

    .shram-thumb {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .month-year {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }

    .no-pdf {
        font-size: 0.8rem;
        color: #888;
    }
</style>

<div class="container mt-4 mb-5">
    <h2 class="mb-4 text-center">📚  श्रमणोपासक </h2>

    <div id="shramListContainer">
        <!-- Cards will be rendered here -->
    </div>
</div>

<script>
function fetchAllShramData() {
    fetch('/api/shramnopasak')
        .then(res => res.json())
        .then(response => {
            const data = Array.isArray(response) ? response : response.data;
            const grouped = {};

            data.forEach(item => {
                if (!grouped[item.year]) grouped[item.year] = [];
                grouped[item.year].push(item);
            });

            const container = document.getElementById('shramListContainer');
            container.innerHTML = '';

            Object.keys(grouped).sort((a, b) => b - a).forEach(year => {
                const section = document.createElement('div');
                section.innerHTML = `<div class="year-heading">${year}</div>`;

                const row = document.createElement('div');
                row.className = 'shram-row';

                grouped[year]
                    .sort((a, b) => {
                        const order = [
                            'December', 'November', 'October', 'September', 'August', 'July',
                            'June', 'May', 'April', 'March', 'February', 'January'
                        ];
                        return order.indexOf(a.month) - order.indexOf(b.month);
                    })
                    .forEach(item => {
                        row.innerHTML += `
                            <div class="shram-card">
                                ${item.cover_photo 
                                    ? `<img src="/storage/${item.cover_photo}" alt="Cover" class="shram-thumb">`
                                    : `<img src="https://via.placeholder.com/150?text=No+Cover" class="shram-thumb">`}
                                <div class="month-year">${item.month} ${item.year}</div>
                                ${item.pdf 
                                    ? `<a href="/storage/${item.pdf}" class="btn btn-sm btn-outline-primary w-100" target="_blank">📄 Read</a>`
                                    : `<div class="no-pdf">No PDF</div>`}
                            </div>
                        `;
                    });

                section.appendChild(row);
                container.appendChild(section);
            });
        })
        .catch(error => {
            console.error('Error fetching Shramnopasak data:', error);
        });
}

window.onload = fetchAllShramData;
</script>
@endsection
