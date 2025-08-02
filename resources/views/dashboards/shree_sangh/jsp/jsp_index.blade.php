@extends('includes.layouts.shree_sangh')


@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">🙏 श्री संघ JSP डैशबोर्ड 🙏</h2>

    <div class="row">
        <!-- Card 1 -->
      <div class="col-md-4 mb-4">
    <div class="card shadow border-primary">
        <div class="card-body text-center">
            <h5 class="card-title text-primary">📋 प्रवृत्तियाँ</h5>
            <p class="card-text">संघ की सभी प्रवृत्तियों की जानकारी देखें।</p>
            <a href="{{ route('jsp.basic') }}" class="btn btn-primary">View</a>
        </div>
    </div>
</div>


        <!-- Card 2 -->
       <div class="col-md-4 mb-4">
    <div class="card shadow border-success">
        <div class="card-body text-center">
            <h5 class="card-title text-success">📚 साहित्य</h5>
            <p class="card-text">उपलब्ध साहित्य की सूची देखें और प्रबंध करें।</p>
            <a href="{{ route('jsp_exam.view') }}" class="btn btn-success">View</a>
        </div>
    </div>
</div>


     <!-- Card 3 -->
<div class="col-md-4 mb-4">
    <div class="card shadow border-danger">
        <div class="card-body text-center">
            <h5 class="card-title text-danger">🧘 श्रमणोपासक</h5>
            <p class="card-text">श्रमणोपासक से जुड़ी जानकारी प्रबंधित करें।</p>
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
