@extends('includes.layouts.shree_sangh')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h2 class="mb-4">न्यूज़ अपडेट</h2>

   <form id="newsForm" enctype="multipart/form-data" class="row g-3">
    <input type="hidden" name="news_id" id="news_id">

    <div class="col-md-6">
        <input type="text" class="form-control" name="title" id="title" placeholder="Title" required>
    </div>

    <div class="col-md-3">
        <input type="date" class="form-control" name="date" id="date">
    </div>

    <div class="col-md-3">
        <input type="text" class="form-control" name="time" id="time" placeholder="10 am से 11 am" >
    </div>

    <div class="col-md-6">
        <input type="text" class="form-control" name="location" id="location" placeholder="Location">
    </div>

    <div class="col-md-6">
        <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
        <small class="text-muted">Only image under 200KB</small>
    </div>

    <div class="col-12">
        <textarea class="form-control" name="description" id="description" placeholder="Description" rows="3" required></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="submitBtn">Add News</button>
    </div>
</form>


    <div class="row mt-4" id="newsList"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('newsForm');
    const newsList = document.getElementById('newsList');
    const submitBtn = document.getElementById('submitBtn');

    function fetchNews() {
        fetch('/api/news')
            .then(res => res.json())
            .then(data => {
                newsList.innerHTML = '';
                data.forEach(item => {
                    newsList.innerHTML += `
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="/storage/${item.photo}" class="card-img-top" style="height:200px; object-fit:cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${item.title}</h5>
                                    <p class="card-text">${item.description}</p>
                                    <p class="card-text"><small>${item.location}, ${item.date} ${item.time}</small></p>
                                    <button class="btn btn-sm btn-warning me-2" onclick='editNews(${JSON.stringify(item)})'>Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteNews(${item.id})">Delete</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const photo = formData.get('photo');
        const newsId = document.getElementById('news_id').value;

        if (photo && photo.size > 204800) {
            alert('Image must be under 200KB!');
            return;
        }

        const url = newsId ? `/api/news/${newsId}` : '/api/news';
        const method = newsId ? 'POST' : 'POST'; // Laravel uses POST for both (PUT with _method override)

        if (newsId) {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            form.reset();
            document.getElementById('news_id').value = '';
            submitBtn.innerText = 'Add News';
            fetchNews();
        });
    });

    window.editNews = function(data) {
        document.getElementById('news_id').value = data.id;
        document.getElementById('title').value = data.title;
        document.getElementById('date').value = data.date;
        document.getElementById('time').value = data.time;
        document.getElementById('location').value = data.location;
        document.getElementById('description').value = data.description;
        submitBtn.innerText = 'Update News';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.deleteNews = function(id) {
        fetch(`/api/news/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(() => fetchNews());
    };

    fetchNews();
});
</script>
@endsection
