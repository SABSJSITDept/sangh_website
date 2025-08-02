@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h4 class="mb-4 fw-bold text-primary">📜 धार्मिक प्रवर्तियाँ</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="pravartiyaForm">
                <input type="hidden" id="pravartiya_id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">शीर्षक</label>
                        <input type="text" id="heading" class="form-control form-control-sm" placeholder="उदाहरण: संयम जागरण" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">विवरण</label>
                        <textarea id="content" class="form-control form-control-sm" rows="1" placeholder="विवरण दर्ज करें..." required></textarea>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-sm btn-success">
                        💾 सहेजें
                    </button>
                    <button type="reset" class="btn btn-sm btn-secondary" onclick="resetForm()">
                        🔄 रीसेट
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped table-hover mb-0" id="pravartiyaTable">
                <thead class="table-light">
                    <tr>
                        <th>शीर्षक</th>
                        <th>विवरण</th>
                        <th class="text-center">क्रिया</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function fetchPravartiya() {
        fetch('/api/dharmik-pravartiya')
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#pravartiyaTable tbody');
                tbody.innerHTML = '';
                data.forEach(item => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${item.heading}</td>
                            <td>${item.content}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editPravartiya(${item.id}, '${item.heading}', \`${item.content}\`)">✏️</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deletePravartiya(${item.id})">🗑️</button>
                            </td>
                        </tr>`;
                });
            });
    }

    document.querySelector('#pravartiyaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('pravartiya_id').value;
        const heading = document.getElementById('heading').value;
        const content = document.getElementById('content').value;
        const method = id ? 'PUT' : 'POST';
        const url = `/api/dharmik-pravartiya${id ? `/${id}` : ''}`;

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ heading, content }),
        }).then(() => {
            resetForm();
            fetchPravartiya();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    window.editPravartiya = (id, heading, content) => {
        document.getElementById('pravartiya_id').value = id;
        document.getElementById('heading').value = heading;
        document.getElementById('content').value = content;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.deletePravartiya = (id) => {
        if (confirm('क्या आप वाकई हटाना चाहते हैं?')) {
            fetch(`/api/dharmik-pravartiya/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => fetchPravartiya());
        }
    };

    window.resetForm = () => {
        document.getElementById('pravartiyaForm').reset();
        document.getElementById('pravartiya_id').value = '';
    };

    fetchPravartiya();
});
</script>
@endsection
