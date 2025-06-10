@extends("layouts.app")

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Editar Livro</h4>
                    <a href="{{ route("books.index") }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session("error"))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session("error") }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route("books.update", $book) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error("title") is-invalid @enderror" 
                                           id="title" name="title" value="{{ old("title", $book->title) }}" required>
                                    @error("title")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="publication_year" class="form-label">Ano de Publicação</label>
                                    <input type="number" class="form-control @error("publication_year") is-invalid @enderror" 
                                           id="publication_year" name="publication_year" value="{{ old("publication_year", $book->publication_year) }}" min="1000" max="{{ date("Y") }}">
                                    @error("publication_year")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" class="form-control @error("isbn") is-invalid @enderror" 
                                           id="isbn" name="isbn" value="{{ old("isbn", $book->isbn) }}" maxlength="13">
                                    @error("isbn")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error("price") is-invalid @enderror" 
                                           id="price" name="price" value="{{ old("price", $book->price) }}" step="0.01" min="0" required>
                                    @error("price")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="authors" class="form-label">Autores <span class="text-danger">*</span></label>
                                    <select class="form-select @error("authors") is-invalid @enderror" 
                                            id="authors" name="authors[]" multiple required>
                                        <option value="" disabled>Selecione um ou mais autores</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" 
                                                {{ in_array($author->id, old("authors", $book->authors->pluck("id")->toArray())) ? "selected" : "" }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("authors")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="subjects" class="form-label">Assuntos <span class="text-danger">*</span></label>
                                    <select class="form-select @error("subjects") is-invalid @enderror" 
                                            id="subjects" name="subjects[]" multiple required>
                                        <option value="" disabled>Selecione um ou mais assuntos</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                {{ in_array($subject->id, old("subjects", $book->subjects->pluck("id")->toArray())) ? "selected" : "" }}>
                                                {{ $subject->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("subjects")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Imagem de Capa</label>
                            <input class="form-control @error("cover_image") is-invalid @enderror" type="file" id="cover_image" name="cover_image">
                            @error("cover_image")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($book->cover_image_path)
                                <div class="mt-2">
                                    <img src="{{ asset("storage/" . $book->cover_image_path) }}" alt="Capa Atual" style="max-width: 150px; height: auto;">
                                    <small class="form-text text-muted">Capa atual</small>
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route("books.index") }}" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


