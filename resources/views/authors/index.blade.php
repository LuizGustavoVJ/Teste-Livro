@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header com gradiente -->
            <div class="card border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-users me-2"></i>
                                Gerenciar Autores
                            </h2>
                            <p class="mb-0 opacity-75">Visualize e gerencie todos os autores cadastrados</p>
                        </div>
                        <a href="{{ route('authors.create') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-1"></i> Novo Autor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Conteúdo principal -->
            <div class="card border-0 shadow-sm">
                @if($authors->count() > 0)
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-list me-2"></i>
                                Lista de Autores
                            </h5>
                            <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                Total: {{ $authors->total() }} autor(es)
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">
                                            <i class="fas fa-hashtag text-muted me-1"></i>
                                            ID
                                        </th>
                                        <th class="px-4 py-3">
                                            <i class="fas fa-user text-muted me-1"></i>
                                            Nome
                                        </th>
                                        <th class="px-4 py-3">
                                            <i class="fas fa-calendar text-muted me-1"></i>
                                            Data de Criação
                                        </th>
                                        <th class="px-4 py-3 text-center">
                                            <i class="fas fa-cogs text-muted me-1"></i>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($authors as $author)
                                        <tr class="author-row">
                                            <td class="px-4 py-3">
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    #{{ $author->id }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $author->name }}</div>
                                                        <small class="text-muted">Autor</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-dark">{{ $author->created_at->format('d/m/Y H:i') }}</div>
                                                <small class="text-muted">{{ $author->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('authors.show', $author) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('authors.edit', $author) }}" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('authors.destroy', $author) }}" 
                                                          method="POST" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Tem certeza que deseja excluir este autor?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger btn-sm" 
                                                                title="Excluir">
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
                    </div>
                    
                    @if($authors->hasPages())
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-center">
                                {{ $authors->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">Nenhum autor encontrado</h4>
                            <p class="text-muted mb-4">Comece criando o primeiro autor do sistema</p>
                            <a href="{{ route('authors.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Criar Primeiro Autor
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.author-row {
    transition: all 0.3s ease;
}

.author-row:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.btn-group .btn {
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
}

.empty-state {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    transition: all 0.3s ease;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}
</style>
@endsection

