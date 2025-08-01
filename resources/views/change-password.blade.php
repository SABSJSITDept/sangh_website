@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">🔐 Change Password</h3>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @elseif (session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

   <form action="{{ route('password.update') }}" method="POST">
    @csrf

    {{-- Hidden user id field --}}
    <input type="hidden" name="user_id" value="1"> {{-- hardcoded ya dynamic --}}

    <div class="mb-3">
        <label>Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="new_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Confirm New Password</label>
        <input type="password" name="new_password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Password</button>
</form>

</div>
@endsection
