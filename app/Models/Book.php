<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "title",
        "publication_year",
        "isbn",
        "price",
        "cover_image_path", // Adicionado o campo para o caminho da imagem
    ];

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "publication_year" => "integer",
        "price" => "decimal:2",
    ];

    /**
     * Obtém os autores para o livro.
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, "book_author");
    }

    /**
     * Obtém os assuntos para o livro.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    /**
     * O método "boot" do modelo.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($livro) {
            // Excluir a imagem de capa associada, se existir
            if ($livro->cover_image_path) {
                Storage::disk("public")->delete($livro->cover_image_path);
            }
        });
    }
}


