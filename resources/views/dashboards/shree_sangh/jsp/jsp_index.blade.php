@extends('includes.layouts.shree_sangh')

@section('content')
<div class="container mt-4">
    <h2 class="mb-5 text-center fw-bold">🙏 JSP डैशबोर्ड 🙏</h2>

    <div class="row g-4 justify-content-center">
        <!-- Card 1 - JSP Basic -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100 hover-shadow">
                <div class="card-body text-center border-top border-4 border-primary">
                    <h5 class="card-title text-primary fw-bold">📋 BASIC</h5>
                    <p class="card-text">Edit the text and DTP entries.</p>
                    <a href="{{ route('jsp.basic') }}" class="btn btn-outline-primary w-100">View</a>
                </div>
            </div>
        </div>

        <!-- Card 2 - JSP Exam -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center border-top border-4 border-success">
                    <h5 class="card-title text-success fw-bold">📚 JSP EXAM</h5>
                    <p class="card-text">Stay updated with JSP exam info.</p>
                    <a href="{{ route('jsp_exam.view') }}" class="btn btn-outline-success w-100">View</a>
                </div>
            </div>
        </div>

        <!-- Card 3 - JSP Big Exam -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center border-top border-4 border-danger">
                    <h5 class="card-title text-danger fw-bold">🧘 JSP BIG EXAM</h5>
                    <p class="card-text">Important updates for JSP Big Exams.</p>
                    <a href="{{ route('jsp-bigexam') }}" class="btn btn-outline-danger w-100">View</a>
                </div>
            </div>
        </div>

        <!-- Card 4 - JSP Hindi Books -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center border-top border-4 border-warning">
                    <h5 class="card-title text-warning fw-bold">📖 JSP HINDI BOOKS</h5>
                    <p class="card-text">Read and manage Hindi book PDFs.</p>
                    <a href="{{ route('jsp-hindi-books.view') }}" class="btn btn-outline-warning w-100 text-dark">View</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
