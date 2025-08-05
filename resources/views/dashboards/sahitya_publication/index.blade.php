@extends('includes.layouts.sahitya_publication')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">🏛️ Welcome to Sahitya Dashboard</h1>

    <div class="row justify-content-center">
        <!-- Card 1: Upload & Manage Shramnopasak -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-primary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">📤 Upload & Manage</h5>
                    <p class="card-text">Shramnopasak file upload, edit & delete panel.</p>
                    <a href="{{ route('sahitya.publication') }}" class="btn btn-primary">Go to Upload Panel</a>
                </div>
            </div>
        </div>

        <!-- Card 2: View All Shramnopasak Records -->
        <!-- <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-success h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">📚 All Records</h5>
                    <p class="card-text">See all published Shramnopasak entries without pagination.</p>
                    <a href="{{ url('/shramnopasak/all-view') }}" class="btn btn-success">View All</a>
                </div>
            </div>
        </div> -->
    </div>
</div>
@endsection
