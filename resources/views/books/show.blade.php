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
                                <i class="fas fa-book-open me-2"></i>
                                {{ $book->title }}
                            </h2>
                            <p class="mb-0 opacity-75">Detalhes completos do livro</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                            <a href="{{ route('books.index') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Coluna da imagem -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-image text-primary me-2"></i>
                                Capa do Livro
                            </h5>
                            @if($book->cover_image_path)
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $book->cover_image_path) }}" 
                                         alt="Capa do livro {{ $book->title }}" 
                                         class="img-fluid rounded shadow-sm"
                                         style="max-height: 400px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Com capa
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="text-center p-5 bg-light rounded">
                                    <i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
                                    <p class="text-muted mt-3 mb-0">Nenhuma capa disponível</p>
                                    <small class="text-muted">Adicione uma capa editando o livro</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Coluna das informações -->
                <div class="col-lg-8">
                    <div class="row">
                        <!-- Informações básicas -->
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informações Básicas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <label class="form-label text-muted small">ID</label>
                                                <div class="fw-bold">#{{ $book->id }}</div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <label class="form-label text-muted small">Título</label>
                                                <div class="fw-bold text-primary">{{ $book->title }}</div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <label class="form-label text-muted small">Ano de Publicação</label>
                                                <div class="fw-bold">
                                                    @if($book->publication_year)
                                                        <i class="fas fa-calendar-alt text-info me-1"></i>
                                                        {{ $book->publication_year }}
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <label class="form-label text-muted small">ISBN</label>
                                                <div class="fw-bold">
                                                    @if($book->isbn)
                                                        <i class="fas fa-barcode text-secondary me-1"></i>
                                                        {{ $book->isbn }}
                                                    @else
                                                        <span class="text-muted">Não informado</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <label class="form-label text-muted small">Valor</label>
                                                <div class="fw-bold text-success fs-5">
                                                    <i class="fas fa-dollar-sign me-1"></i>
                                                    R$ {{ number_format($book->price, 2, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Autores -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-users me-2"></i>
                                        Autores
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($book->authors->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($book->authors as $author)
                                                <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $author->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Total: {{ $book->authors->count() }} autor(es)
                                            </small>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-user-slash fa-2x mb-2"></i>
                                            <p class="mb-0">Nenhum autor associado</p>
                                            <small>Adicione autores editando o livro</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Assuntos -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tags me-2"></i>
                                        Assuntos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($book->subjects->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($book->subjects as $subject)
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning rounded-pill px-3 py-2">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ $subject->description }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Total: {{ $book->subjects->count() }} assunto(s)
                                            </small>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-tags fa-2x mb-2"></i>
                                            <p class="mb-0">Nenhum assunto associado</p>
                                            <small>Adicione assuntos editando o livro</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Informações de sistema -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        Informações do Sistema
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="form-label text-muted small">Criado em</label>
                                                <div class="fw-bold">
                                                    <i class="fas fa-plus-circle text-success me-1"></i>
                                                    {{ $book->created_at->format('d/m/Y H:i:s') }}
                                                </div>
                                                <small class="text-muted">{{ $book->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <label class="form-label text-muted small">Última atualização</label>
                                                <div class="fw-bold">
                                                    <i class="fas fa-edit text-warning me-1"></i>
                                                    {{ $book->updated_at->format('d/m/Y H:i:s') }}
                                                </div>
                                                <small class="text-muted">{{ $book->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-item {
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-2px);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}
</style>
@endsection

