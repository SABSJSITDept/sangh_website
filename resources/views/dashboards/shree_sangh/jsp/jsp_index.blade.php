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

        <!-- Card 5 - JSP Gujarati Books -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center border-top border-4 border-success">
                    <h5 class="card-title text-success fw-bold">📘 JSP GUJARATI BOOKS</h5>
                    <p class="card-text">Read and manage Gujarati book PDFs.</p>
                    <a href="{{ route('jsp-gujrati-books.view') }}" class="btn btn-outline-success w-100 text-dark">View</a>
                </div>
            </div>
        </div>

        <!-- Card 6 - JSP Old Papers -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center border-top border-4 border-primary">
                    <h5 class="card-title text-primary fw-bold">📄 JSP OLD PAPERS</h5>
                    <p class="card-text">Upload and manage previous year exam papers.</p>
                    <a href="{{ route('jsp-old-papers.view') }}" class="btn btn-outline-primary w-100 text-dark">View</a>
                </div>
            </div>
        </div>

        <!-- Card 7 - JSP Add Results -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center border-top border-4 border-info">
                    <h5 class="card-title text-info fw-bold">📝 ADD RESULTS</h5>
                    <p class="card-text">Add and manage JSP exam results.</p>
                    <a href="{{ route('jsp.result') }}" class="btn btn-outline-info w-100">Add Results</a>
                </div>
            </div>
        </div>

        <!-- Card 8 - JSP Bulk Results -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center border-top border-4 border-secondary">
                    <h5 class="card-title text-secondary fw-bold">📊 BULK RESULTS</h5>
                    <p class="card-text">Upload and manage bulk JSP results.</p>
                    <a href="{{ route('jsp.bulk_results') }}" class="btn btn-outline-secondary w-100">Manage Bulk Results</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
