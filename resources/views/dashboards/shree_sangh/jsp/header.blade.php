@extends('includes.layouts.shree_sangh')

@section('content')

<!-- ✅ JSP Header Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">🙏 JSP Dashboard 🙏</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#jspNavbar" aria-controls="jspNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="jspNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-primary fw-bold" href="{{ route('jsp.basic') }}">BASIC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success fw-bold" href="{{ route('jsp_exam.view') }}">EXAM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="{{ route('jsp-bigexam') }}">BIG EXAM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="{{ route('jsp-hindi-books.view') }}">HINDI BOOKS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success fw-bold" href="{{ route('jsp-gujrati-books.view') }}">GUJARATI BOOKS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary fw-bold" href="{{ route('jsp-old-papers.view') }}">OLD PAPERS</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


@endsection
