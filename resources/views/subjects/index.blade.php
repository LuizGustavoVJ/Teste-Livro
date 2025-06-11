@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <h1 class="page-title">
            <i class="fas fa-tags me-3"></i>Gerenciar Assuntos
        </h1>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pb-0">
                <h4 class="mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Assuntos
                </h4>
                <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Assunto
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

                @if($subjects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-tag me-1"></i>Descrição</th>
                                    <th><i class="fas fa-book me-1"></i>Livros Relacionados</th>
                                    <th><i class="fas fa-calendar me-1"></i>Data de Criação</th>
                                    <th><i class="fas fa-cogs me-1"></i>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $subject)
                                    <tr>
                                        <td class="fw-bold">{{ $subject->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="subject-icon me-3">
                                                    <i class="fas fa-tag fa-2x text-success"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold">{{ $subject->description }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($subject->books->count() > 0)
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-book me-1"></i>{{ $subject->books->count() }} livro(s)
                                                </span>
                                            @else
                                                <span class="badge bg-secondary fs-6">
                                                    <i class="fas fa-book me-1"></i>Nenhum livro
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $subject->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('subjects.show', $subject) }}" class="btn btn-primary btn-sm" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este assunto?')">
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
                        {{ $subjects->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum assunto encontrado</h5>
                        <p class="text-muted">Comece criando seu primeiro assunto!</p>
                        <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Criar Primeiro Assunto
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.subject-icon {
    transition: transform 0.3s ease;
}

.subject-icon:hover {
    transform: scale(1.1);
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
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

