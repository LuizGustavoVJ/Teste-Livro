@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detalhes do Autor</h4>
                    <div>
                        <a href="{{ route('authors.edit', $author) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('authors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informações do Autor</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">ID:</th>
                                    <td>{{ $author->id }}</td>
                                </tr>
                                <tr>
                                    <th>Nome:</th>
                                    <td>{{ $author->name }}</td>
                                </tr>
                                <tr>
                                    <th>Criado em:</th>
                                    <td>{{ $author->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Atualizado em:</th>
                                    <td>{{ $author->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Livros do Autor</h5>
                            @if($author->books->count() > 0)
                                <div class="list-group">
                                    @foreach($author->books as $book)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $book->title }}</h6>
                                                <small>R$ {{ number_format($book->price, 2, ',', '.') }}</small>
                                            </div>
                                            <p class="mb-1">
                                                <strong>Ano:</strong> {{ $book->publication_year ?? 'N/A' }} |
                                                <strong>ISBN:</strong> {{ $book->isbn ?? 'N/A' }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Este autor ainda não possui livros cadastrados.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

