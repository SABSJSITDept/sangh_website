@extends('includes.layouts.spf')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .event-header {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }
.event-photo {
    width: 100%;
    height: 150px; /* fixed height - हर image बराबर दिखेगी */
    object-fit: cover; /* image को container में fit करेगा और extra हिस्सा crop करेगा */
    border-radius: 18px;
    border: 8px solid #ddd;
    background-color: #f8f8f8; /* अगर image लोड न हो तो हल्का बैकग्राउंड दिखे */
    display: block;
}


.photo-actions {
    display: flex;
    justify-content: center;
    gap: 6px; /* gap थोड़ा कम */
    margin-top: 4px;
}
.event-card {
    background: #fff;
    border-radius: 18px;
    padding: 10px; /* padding कम */
    box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

</style>

<div class="container mt-4">
    <h4 class="mb-4 text-center">📸 संघ फोटो गैलरी</h4>
    <div id="photoGallery"></div>
</div>

<!-- Photo Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="photo_id" id="photo_id">
            <input type="hidden" name="old_photo" id="old_photo">
            <div class="modal-content">
                <div class="modal-header"><h5>Replace Photo</h5></div>
                <div class="modal-body">
                    <input type="file" class="form-control" name="new_photo" accept="image/*" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const category = "spf";
window.photoMap = {};

function loadGallery(){
    window.photoMap = {};
    fetch(`/api/photo-gallery/fetch/${category}`)
        .then(res => res.json())
        .then(events => {
            let html = '';
            events.forEach(event => {
                html += `
                    <div class="event-card">
                        <div class="event-header">
                            <h5 class="mb-0 text-center fw-bold">${event.event_name}</h5>
                            <button class="btn btn-sm btn-link text-primary p-0" onclick="enableEventEdit('${event.event_name}')">✏️</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteEvent('${event.event_name}')">🗑</button>
                        </div>
                        <div class="row">`;
                event.photos.forEach(photoObj => {
                    window.photoMap[photoObj.url] = photoObj.id;
                    html += `
                        <div class="col-md-4 col-sm-6 mb-3">
                            <img src="${photoObj.url}" class="event-photo">
                            <div class="photo-actions">
                                <button class="btn btn-sm btn-warning" onclick="openEdit('${photoObj.url}')">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deletePhoto('${photoObj.url}')">Delete</button>
                            </div>
                        </div>`;
                });
                html += `</div></div>`;
            });
            document.getElementById('photoGallery').innerHTML = html;
        });
}

function enableEventEdit(id, oldName) {
    let newName = prompt("Enter new event name:", oldName);
    if(newName && newName !== oldName){
       fetch(`/api/photo-gallery/update-event/${id}`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ event_name: newName })
})

        .then(res => res.json())
        .then(data => {
            alert(data.message);
            loadGallery();
        });
    }
}

function deleteEvent(eventName){
    if(!confirm(`Delete entire event "${eventName}"? This will remove all photos permanently.`)) return;
    fetch(`/api/photo-gallery/delete-event/${encodeURIComponent(eventName)}/${category}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        loadGallery();
    });
}

function openEdit(photoUrl){
    document.getElementById('old_photo').value = photoUrl;
    document.getElementById('photo_id').value = window.photoMap[photoUrl];
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

document.getElementById('editForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    let id = formData.get('photo_id');

    fetch(`/api/photo-gallery/update/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        loadGallery();
    });
});

function deletePhoto(photoUrl){
    if(!confirm("Are you sure you want to delete this photo?")) return;
    let id = window.photoMap[photoUrl];

    fetch(`/api/photo-gallery/delete-single/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ photo_url: photoUrl })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        loadGallery();
    });
}

loadGallery();
</script>
@endsection
