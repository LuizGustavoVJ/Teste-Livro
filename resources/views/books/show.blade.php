@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <h1 class="page-title">
            <i class="fas fa-book-open me-3"></i>Detalhes do Livro
        </h1>
        
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pb-0">
                <h4 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>{{ $book->title }}
                </h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('books.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Coluna da Imagem de Capa -->
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <h5 class="mb-3">
                                <i class="fas fa-image me-2"></i>Capa do Livro
                            </h5>
                            @if($book->cover_image_path)
                                <div class="book-cover-container">
                                    <img src="{{ asset('storage/' . $book->cover_image_path) }}" 
                                         alt="Capa do livro {{ $book->title }}" 
                                         class="img-fluid rounded shadow-lg book-cover-image">
                                </div>
                            @else
                                <div class="book-cover-placeholder d-flex align-items-center justify-content-center rounded shadow">
                                    <div class="text-center">
                                        <i class="fas fa-book fa-4x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Sem capa disponível</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Coluna das Informações -->
                    <div class="col-md-8">
                        <div class="row">
                            <!-- Informações Básicas -->
                            <div class="col-12 mb-4">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>Informações Básicas
                                </h5>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-hashtag me-2"></i>ID:
                                        </span>
                                        <span class="info-value">{{ $book->id }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-book me-2"></i>Título:
                                        </span>
                                        <span class="info-value fw-bold">{{ $book->title }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar me-2"></i>Ano de Publicação:
                                        </span>
                                        <span class="info-value">{{ $book->publication_year ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-barcode me-2"></i>ISBN:
                                        </span>
                                        <span class="info-value">{{ $book->isbn ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-dollar-sign me-2"></i>Valor:
                                        </span>
                                        <span class="info-value fw-bold text-success fs-5">R$ {{ number_format($book->price, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Autores -->
                            <div class="col-md-6 mb-4">
                                <h5 class="section-title">
                                    <i class="fas fa-users me-2"></i>Autores
                                </h5>
                                @if($book->authors->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($book->authors as $author)
                                            <span class="badge bg-primary fs-6 px-3 py-2">
                                                <i class="fas fa-user me-1"></i>{{ $author->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Nenhum autor associado</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Assuntos -->
                            <div class="col-md-6 mb-4">
                                <h5 class="section-title">
                                    <i class="fas fa-tags me-2"></i>Assuntos
                                </h5>
                                @if($book->subjects->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($book->subjects as $subject)
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                <i class="fas fa-tag me-1"></i>{{ $subject->description }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-tags fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Nenhum assunto associado</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Informações de Sistema -->
                            <div class="col-12">
                                <h5 class="section-title">
                                    <i class="fas fa-clock me-2"></i>Informações do Sistema
                                </h5>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-plus-circle me-2"></i>Criado em:
                                        </span>
                                        <span class="info-value">{{ $book->created_at->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-edit me-2"></i>Atualizado em:
                                        </span>
                                        <span class="info-value">{{ $book->updated_at->format('d/m/Y H:i:s') }}</span>
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
.book-cover-container {
    max-width: 250px;
    margin: 0 auto;
}

.book-cover-image {
    max-height: 350px;
    width: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.book-cover-image:hover {
    transform: scale(1.05);
}

.book-cover-placeholder {
    height: 350px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed #dee2e6;
}

.section-title {
    color: #495057;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 8px;
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.info-label {
    font-weight: 600;
    color: #495057;
    min-width: 150px;
}

.info-value {
    color: #212529;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection

