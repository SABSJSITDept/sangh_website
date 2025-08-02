@extends('includes.layouts.shree_sangh')


@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">🙏  JSP डैशबोर्ड 🙏</h2>

    <div class="row">
        <!-- Card 1 -->
      <div class="col-md-4 mb-4">
    <div class="card shadow border-primary">
        <div class="card-body text-center">
            <h5 class="card-title text-primary">📋 BASIC</h5>
            <p class="card-text">EDIT THE TEXT AND THE DTP<p>
            <a href="{{ route('jsp.basic') }}" class="btn btn-primary">View</a>
        </div>
    </div>
</div>


        <!-- Card 2 -->
       <div class="col-md-4 mb-4">
    <div class="card shadow border-success">
        <div class="card-body text-center">
            <h5 class="card-title text-success">📚 JSP EXAM</h5>
            <p class="card-text">JSP EXAM UPDATES ।</p>
            <a href="{{ route('jsp_exam.view') }}" class="btn btn-success">View</a>
        </div>
    </div>
</div>


     <!-- Card 3 -->
<div class="col-md-4 mb-4">
    <div class="card shadow border-danger">
        <div class="card-body text-center">
            <h5 class="card-title text-danger">🧘 JSP BIG EXAM </h5>
            <p class="card-text">JSP BIG EXAM UPDATES।</p>
            <a href="{{ route('jsp-bigexam') }}" class="btn btn-danger">View</a>
        </div>
    </div>
</div>


        <!-- Card 4 -->
        <div class="col-md-4 mb-4">
            <div class="card shadow border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">🕉️ आगम एवं तत्त्व</h5>
                    <p class="card-text">आगम और तत्त्व प्रकाशन से जुड़ी जानकारी।</p>
                    <a href="#" class="btn btn-warning text-white">View</a>
                </div>
            </div>
        </div>

        <!-- Add more cards as needed -->

    </div>
</div>
@endsection
