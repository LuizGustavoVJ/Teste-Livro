@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h1 class="page-title mb-4">
                    <i class="fas fa-book me-2"></i>Relatório de Livros por Autor
                </h1>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('reports.books-by-author.pdf') }}" class="btn btn-warning" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Exportar PDF
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print me-1"></i> Imprimir
                        </button>
                    </div>
                    @foreach($authors as $author)
                        <h3>{{ $author->name }}</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Ano de Publicação</th>
                                    <th>ISBN</th>
                                    <th>Preço (R$)</th>
                                    <th>Valor (R$)</th>
                                    <th>Assuntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($author->books as $book)
                                    <tr>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->publication_year }}</td>
                                        <td>{{ $book->isbn }}</td>
                                        <td>{{ $book->price ? 'R$ ' . number_format($book->price, 2, ',', '.') : '-' }}</td>
                                        <td>{{ $book->valor ? 'R$ ' . number_format($book->valor, 2, ',', '.') : '-' }}</td>
                                        <td>{{ $book->subjects ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

