@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">स्थायी संपत्ति संवर्धन समिति सदस्य जोड़ें</h2>

    {{-- ✅ Form Section --}}
    <div class="card shadow-sm border-success mb-5">
        <div class="card-body">
            <form id="samitiForm" enctype="multipart/form-data">
                <input type="hidden" id="editId" value="">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">नाम</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">शहर</label>
                        <input type="text" name="city" id="city" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">मोबाइल</label>
                        <input type="text" name="mobile" id="mobile" class="form-control" required maxlength="10">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">फोटो</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100" id="submitBtn">सदस्य जोड़ें</button>
            </form>
        </div>
    </div>

    {{-- ✅ Cards Section --}}
    <div class="row" id="cardContainer"></div>
</div>

{{-- ✅ Scripts --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('samitiForm');
    const cardContainer = document.getElementById('cardContainer');
    const submitBtn = document.getElementById('submitBtn');
    const editId = document.getElementById('editId');

    // 👉 Fetch and render all members
    function fetchMembers() {
        fetch('/api/sthayi_sampati_sanwardhan_samiti')
            .then(res => res.json())
            .then(data => {
                cardContainer.innerHTML = '';
                data.forEach(member => {
                    cardContainer.innerHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-primary">
                                <img src="${member.photo ?? 'https://via.placeholder.com/300x200?text=No+Image'}" class="card-img-top" height="200" style="object-fit:cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${member.name}</h5>
                                    <p class="card-text mb-1"><strong>City:</strong> ${member.city}</p>
                                    <p class="card-text mb-1"><strong>Mobile:</strong> ${member.mobile}</p>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <button class="btn btn-sm btn-warning" onclick="editMember(${member.id})">
                                        ✏️ Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteMember(${member.id})">
                                        🗑️ Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
    }

    // 👉 Form Submit (Add/Update)
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        let url = '/api/sthayi_sampati_sanwardhan_samiti';
        let method = 'POST';

        if (editId.value) {
            url += '/' + editId.value;
            method = 'POST';
            formData.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            alert(editId.value ? 'Updated successfully' : 'Saved successfully');
            form.reset();
            editId.value = '';
            submitBtn.textContent = 'सदस्य जोड़ें';
            fetchMembers();
        } else {
            alert("त्रुटि: " + JSON.stringify(data.errors));
        }
    });

    // 👉 Delete Member
    window.deleteMember = async (id) => {
        if (!confirm('क्या आप वाकई हटाना चाहते हैं?')) return;
        const response = await fetch(`/api/sthayi_sampati_sanwardhan_samiti/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        if (response.ok) {
            alert('हटा दिया गया');
            fetchMembers();
        }
    };

    // 👉 Edit Member (load data to form)
    window.editMember = async (id) => {
        const response = await fetch(`/api/sthayi_sampati_sanwardhan_samiti/${id}`);
        const data = await response.json();
        document.getElementById('name').value = data.name;
        document.getElementById('city').value = data.city;
        document.getElementById('mobile').value = data.mobile;
        editId.value = data.id;
        submitBtn.textContent = 'अपडेट करें';
        document.getElementById('photo').value = ''; // Clear file input
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    // ✅ Initial Load
    fetchMembers();
});
</script>
@endsection
