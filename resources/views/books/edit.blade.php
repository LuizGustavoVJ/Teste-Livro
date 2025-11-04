@extends("layouts.app")

@section("content")
<div class="row justify-content-center">
    <div class="col-md-10">
        <h1 class="page-title">
            <i class="fas fa-edit me-3"></i>Editar Livro
        </h1>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pb-0">
                <h4 class="mb-0">
                    <i class="fas fa-book me-2"></i>{{ $book->title }}
                </h4>
                <a href="{{ route("books.index") }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção!</strong> Corrija os erros abaixo:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session("erro"))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session("erro") }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route("books.update", $book) }}" method="POST" enctype="multipart/form-data" class="fade-in-up">
                    @csrf
                    @method("PUT")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">
                                    <i class="fas fa-book me-1"></i>Título <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error("title") is-invalid @enderror"
                                       id="title" name="title" value="{{ old("title", $book->title) }}" required
                                       placeholder="Digite o título do livro">
                                @error("title")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-4">
                                <label for="publication_year" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i>Ano de Publicação
                                </label>
                                <input type="number" class="form-control @error("publication_year") is-invalid @enderror"
                                       id="publication_year" name="publication_year" value="{{ old("publication_year", $book->publication_year) }}"
                                       min="1000" max="{{ date("Y") }}" placeholder="Ex: {{ date("Y") }}">
                                @error("publication_year")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-4">
                                <label for="isbn" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-1"></i>ISBN
                                </label>
                                <input type="text" class="form-control @error("isbn") is-invalid @enderror"
                                       id="isbn" name="isbn" value="{{ old("isbn", $book->isbn) }}" maxlength="13"
                                       placeholder="Ex: 9788535902778">
                                @error("isbn")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="price" class="form-label fw-bold">
                                    <i class="fas fa-dollar-sign me-1"></i>Valor (R$) <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error("price") is-invalid @enderror"
                                       id="price" name="price" value="{{ old("price", $book->price) }}" step="0.01" min="0" required
                                       placeholder="Ex: 29.90">
                                @error("price")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="authors" class="form-label fw-bold">
                                    <i class="fas fa-users me-1"></i>Autores <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error("authors") is-invalid @enderror"
                                        id="authors" name="authors[]" multiple required size="5">
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}"
                                            {{ in_array($author->id, old("authors", $book->authors->pluck("id")->toArray())) ? "selected" : "" }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Segure Ctrl (ou Cmd) para selecionar múltiplos autores
                                </div>
                                @error("authors")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="subjects" class="form-label fw-bold">
                                    <i class="fas fa-tags me-1"></i>Assuntos <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error("subjects") is-invalid @enderror"
                                        id="subjects" name="subjects[]" multiple required size="5">
                                    @foreach($subjects as $assunto)
                                        <option value="{{ $assunto->id }}"
                                            {{ in_array($assunto->id, old("subjects", $book->subjects->pluck("id")->toArray())) ? "selected" : "" }}>
                                            {{ $assunto->description }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Segure Ctrl (ou Cmd) para selecionar múltiplos assuntos
                                </div>
                                @error("subjects")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="cover_image" class="form-label fw-bold">
                                    <i class="fas fa-image me-1"></i>Nova Imagem de Capa
                                </label>
                                <input class="form-control @error("cover_image") is-invalid @enderror"
                                       type="file" id="cover_image" name="cover_image" accept="image/*">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Formatos aceitos: JPG, PNG, GIF (máx. 5MB)
                                </div>
                                @error("cover_image")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-eye me-1"></i>Capa Atual
                                </label>
                                <div class="border rounded p-3 bg-light text-center" style="min-height: 120px;">
                                    @if($book->cover_image_path)
                                        <img src="{{ asset("storage/" . $book->cover_image_path) }}"
                                             alt="Capa Atual" class="img-fluid rounded" style="max-height: 120px;">
                                        <p class="text-muted mt-2 mb-0">Capa atual do livro</p>
                                    @else
                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Nenhuma capa definida</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route("books.index") }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Livro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const currentCover = document.querySelector('.col-md-6:last-child .border');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            currentCover.innerHTML = `
                <img src="${e.target.result}" alt="Nova Capa" class="img-fluid rounded" style="max-height: 120px;">
                <p class="text-success mt-2 mb-0">Nova capa selecionada: ${file.name}</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection

