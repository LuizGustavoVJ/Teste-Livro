@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <h1 class="page-title">
            <i class="fas fa-users me-3"></i>Gerenciar Autores
        </h1>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pb-0">
                <h4 class="mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Autores
                </h4>
                <a href="{{ route('authors.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Autor
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($authors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-user me-1"></i>Nome</th>
                                    <th><i class="fas fa-book me-1"></i>Livros Publicados</th>
                                    <th><i class="fas fa-calendar me-1"></i>Data de Criação</th>
                                    <th><i class="fas fa-cogs me-1"></i>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($authors as $author)
                                    <tr>
                                        <td class="fw-bold">{{ $author->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="author-avatar me-3">
                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold">{{ $author->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($author->books->count() > 0)
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-book me-1"></i>{{ $author->books->count() }} livro(s)
                                                </span>
                                            @else
                                                <span class="badge bg-secondary fs-6">
                                                    <i class="fas fa-book me-1"></i>Nenhum livro
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $author->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('authors.show', $author) }}" class="btn btn-primary btn-sm" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('authors.edit', $author) }}" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este autor?')">
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
                        {{ $authors->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-plus fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum autor encontrado</h5>
                        <p class="text-muted">Comece criando seu primeiro autor!</p>
                        <a href="{{ route('authors.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Criar Primeiro Autor
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.author-avatar {
    transition: transform 0.3s ease;
}

.author-avatar:hover {
    transform: scale(1.1);
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    transform: translateX(5px);
}

.btn-group .btn {
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection

