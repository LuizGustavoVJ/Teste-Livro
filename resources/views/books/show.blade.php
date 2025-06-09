@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detalhes do Livro</h4>
                    <div>
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informações do Livro</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">ID:</th>
                                    <td>{{ $book->id }}</td>
                                </tr>
                                <tr>
                                    <th>Título:</th>
                                    <td>{{ $book->title }}</td>
                                </tr>
                                <tr>
                                    <th>Ano de Publicação:</th>
                                    <td>{{ $book->publication_year ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>ISBN:</th>
                                    <td>{{ $book->isbn ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Valor:</th>
                                    <td>R$ {{ number_format($book->price, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Criado em:</th>
                                    <td>{{ $book->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Atualizado em:</th>
                                    <td>{{ $book->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <h5>Autores</h5>
                                    @if($book->authors->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($book->authors as $author)
                                                <span class="badge bg-primary fs-6">{{ $author->name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">Nenhum autor associado.</p>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <h5>Assuntos</h5>
                                    @if($book->subjects->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($book->subjects as $subject)
                                                <span class="badge bg-secondary fs-6">{{ $subject->description }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">Nenhum assunto associado.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

