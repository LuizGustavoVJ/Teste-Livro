@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <h1 class="page-title">
            <i class="fas fa-book me-3"></i>Gerenciar Livros
        </h1>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pb-0">
                <h4 class="mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Livros
                </h4>
                <a href="{{ route('books.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Livro
                </a>
            </div>
            <div class="card-body">
                @if(session('sucesso'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('sucesso') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('erro'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('erro') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($books->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-image me-1"></i>Capa</th>
                                    <th><i class="fas fa-book me-1"></i>Título</th>
                                    <th><i class="fas fa-users me-1"></i>Autores</th>
                                    <th><i class="fas fa-tags me-1"></i>Assuntos</th>
                                    <th><i class="fas fa-calendar me-1"></i>Ano</th>
                                    <th><i class="fas fa-dollar-sign me-1"></i>Valor</th>
                                    <th><i class="fas fa-cogs me-1"></i>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $book)
                                    <tr>
                                        <td class="fw-bold">{{ $book->id }}</td>
                                        <td>
                                            @if($book->arquivo)
                                                <img src="{{ asset('storage/' . $book->arquivo) }}"
                                                     alt="Capa do livro"
                                                     class="rounded"
                                                     style="width: 40px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 50px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-semibold">{{ $book->title }}</td>
                                        <td>
                                            @foreach($book->authors as $author)
                                                <span class="badge bg-primary me-1">{{ $author->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($book->subjects as $subject)
                                                <span class="badge bg-success me-1">{{ $subject->description }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $book->publication_year ?? 'N/A' }}</td>
                                        <td class="fw-bold text-success">R$ {{ number_format($book->price, 2, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('books.show', $book) }}" class="btn btn-primary btn-sm" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este livro?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $books->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum livro encontrado</h5>
                        <p class="text-muted">Comece criando seu primeiro livro!</p>
                        <a href="{{ route('books.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Criar Primeiro Livro
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

