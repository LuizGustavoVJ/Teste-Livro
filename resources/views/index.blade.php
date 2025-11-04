@extends('layouts.app')

@section('content')
<style>
    .card-title {
        text-align: center;
        font-style: italic;
        font-weight: bold;
        margin-top: 1rem;
    }
    .book-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        font-size: 0.95rem;
    }
    .book-details p {
        margin: 0;
    }
    .book-price {
        text-align: center;
        margin-top: 1rem;
        font-weight: bold;
        color: #6b1987; /* Bootstrap's success color */
        font-size: 1.1rem;
    }
</style>

<div class="container">
    <h1 class="page-title mb-4">
        <i class="fas fa-book me-2"></i>Livros em Destaque
    </h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($books as $livro)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if ($livro->cover_image_path)
                        <img src="{{ asset('storage/' . $livro->cover_image_path) }}"
                            class="card-img-top img-fluid"
                            style="max-height: 250px; object-fit: contain;"
                            alt="Capa do livro {{ $livro->title }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light"
                            style="height: 250px;">
                            <i class="fas fa-book fa-4x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title">{{ $livro->title }}</h5>

                        <div class="book-details">
                            <p><strong>Autor{{ count($livro->authors) > 1 ? 'es' : '' }}:</strong> {{ $livro->authors->pluck('name')->join(', ') }}</p>
                            <p><strong>Ano:</strong> {{ $livro->publication_year }}</p>
                        </div>

                        <div class="book-price">
                            R$ {{ number_format($livro->price, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
