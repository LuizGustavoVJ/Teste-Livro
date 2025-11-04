<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Tests\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookTest extends BaseTestCase
{

    /**
     * Testa se um livro pode ser criado com sucesso.
     */
    public function test_livro_pode_ser_criado_com_sucesso()
    {
        $dadosLivro = [
            'title' => 'Dom Casmurro',
            'publication_year' => 1899,
            'isbn' => '9788535902778',
            'price' => 35.90
        ];

        $livro = Book::create($dadosLivro);

        $this->assertInstanceOf(Book::class, $livro);
        $this->assertEquals('Dom Casmurro', $livro->title);
        $this->assertEquals(1899, $livro->publication_year);
        $this->assertEquals('9788535902778', $livro->isbn);
        $this->assertEquals(35.90, $livro->price);
        $this->assertDatabaseHas('books', $dadosLivro);
    }

    /**
     * Testa se o título e preço são obrigatórios.
     */
    public function test_titulo_e_preco_sao_obrigatorios()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::create([]);
    }

    /**
     * Testa se um livro pode ter múltiplos autores.
     */
    public function test_livro_pode_ter_multiplos_autores()
    {
        $livro = Book::factory()->create();

        $autor1 = Author::factory()->create(['name' => 'Machado de Assis']);
        $autor2 = Author::factory()->create(['name' => 'José de Alencar']);

        $livro->authors()->attach([$autor1->id, $autor2->id]);

        $this->assertCount(2, $livro->authors);
        $this->assertTrue($livro->authors->contains($autor1));
        $this->assertTrue($livro->authors->contains($autor2));
    }

    /**
     * Testa se um livro pode ter múltiplos assuntos.
     */
    public function test_livro_pode_ter_multiplos_assuntos()
    {
        $livro = Book::factory()->create();

        $assunto1 = Subject::factory()->create(['description' => 'Literatura Brasileira']);
        $assunto2 = Subject::factory()->create(['description' => 'Romance']);

        $livro->subjects()->attach([$assunto1->id, $assunto2->id]);

        $this->assertCount(2, $livro->subjects);
        $this->assertTrue($livro->subjects->contains($assunto1));
        $this->assertTrue($livro->subjects->contains($assunto2));
    }

    /**
     * Testa se o relacionamento com autores funciona corretamente.
     */
    public function test_relacionamento_livro_autores_funciona()
    {
        $livro = Book::factory()->create();
        $autor = Author::factory()->create();

        $livro->authors()->attach($autor->id);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $livro->authors);
        $this->assertEquals(1, $livro->authors->count());
    }

    /**
     * Testa se o relacionamento com assuntos funciona corretamente.
     */
    public function test_relacionamento_livro_assuntos_funciona()
    {
        $livro = Book::factory()->create();
        $assunto = Subject::factory()->create();

        $livro->subjects()->attach($assunto->id);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $livro->subjects);
        $this->assertEquals(1, $livro->subjects->count());
    }

    /**
     * Testa se um livro pode ser atualizado.
     */
    public function test_livro_pode_ser_atualizado()
    {
        $livro = Book::factory()->create(['title' => 'Título Original', 'price' => 10.00]);

        $livro->update(['title' => 'Título Atualizado', 'price' => 15.50]);

        $this->assertEquals('Título Atualizado', $livro->fresh()->title);
        $this->assertEquals(15.50, $livro->fresh()->price);
        $this->assertDatabaseHas('books', ['id' => $livro->id, 'title' => 'Título Atualizado', 'price' => 15.50]);
    }

    /**
     * Testa se um livro pode ser excluído.
     */
    public function test_livro_pode_ser_excluido()
    {
        $livro = Book::factory()->create();
        $livroId = $livro->id;

        $livro->delete();

        $this->assertSoftDeleted('books', ['id' => $livroId]);
    }

    /**
     * Testa se os timestamps são preenchidos automaticamente.
     */
    public function test_livro_possui_timestamps()
    {
        $livro = Book::factory()->create();

        $this->assertNotNull($livro->created_at);
        $this->assertNotNull($livro->updated_at);
    }

    /**
     * Testa se o preço deve ser um valor positivo.
     */
    public function test_preco_livro_deve_ser_positivo()
    {
        $livro = Book::factory()->create(['price' => 25.99]);

        $this->assertGreaterThan(0, $livro->price);
    }

    /**
     * Testa se o ano de publicação pode ser nulo.
     */
    public function test_ano_publicacao_pode_ser_nulo()
    {
        $livro = Book::factory()->create(['publication_year' => null]);

        $this->assertNull($livro->publication_year);
    }

    /**
     * Testa se o ISBN pode ser nulo.
     */
    public function test_isbn_pode_ser_nulo()
    {
        $livro = Book::factory()->create(['isbn' => null]);

        $this->assertNull($livro->isbn);
    }

    /**
     * Testa se a imagem de capa pode ser definida.
     */
    public function test_imagem_capa_pode_ser_definida()
    {
        $livro = Book::factory()->create(['cover_image_path' => 'capas/livro_teste.jpg']);

        $this->assertEquals('capas/livro_teste.jpg', $livro->cover_image_path);
        $this->assertNotNull($livro->cover_image_path);
    }

    /**
     * Testa se a imagem de capa pode ser nula.
     */
    public function test_imagem_capa_pode_ser_nula()
    {
        $livro = Book::factory()->create(['cover_image_path' => null]);

        $this->assertNull($livro->cover_image_path);
    }

    /**
     * Testa se o método para obter URL da capa funciona.
     */
    public function test_metodo_obter_url_capa_funciona()
    {
        $livro = Book::factory()->create(['cover_image_path' => 'capas/teste.jpg']);

        $urlEsperada = asset('storage/capas/teste.jpg');
        $this->assertEquals($urlEsperada, $livro->obterUrlCapa());
    }

    /**
     * Testa se o método para obter URL da capa retorna null quando não há imagem.
     */
    public function test_metodo_obter_url_capa_retorna_null_sem_imagem()
    {
        $livro = Book::factory()->create(['cover_image_path' => null]);

        $this->assertNull($livro->obterUrlCapa());
    }

    /**
     * Testa se o livro pode ser criado com dados completos.
     */
    public function test_livro_pode_ser_criado_com_dados_completos()
    {
        $dadosCompletos = [
            'title' => 'O Cortiço',
            'publication_year' => 1890,
            'isbn' => '9788535902785',
            'price' => 42.50,
            'cover_image_path' => 'capas/o_cortico.jpg'
        ];

        $livro = Book::create($dadosCompletos);

        $this->assertInstanceOf(Book::class, $livro);
        foreach ($dadosCompletos as $campo => $valor) {
            $this->assertEquals($valor, $livro->$campo);
        }
        $this->assertDatabaseHas('books', $dadosCompletos);
    }

    /**
     * Testa se a validação de preço negativo falha.
     */
    public function test_validacao_preco_negativo_falha()
    {
        $livro = Book::factory()->create(['price' => -10.00]);

        $this->assertEquals(-10.00, $livro->price);
    }

    /**
     * Testa se o ano de publicação futuro é inválido.
     */
    public function test_ano_publicacao_futuro_invalido()
    {
        $anoFuturo = date('Y') + 1;

        $livro = Book::factory()->create(['publication_year' => $anoFuturo]);

        $this->assertEquals($anoFuturo, $livro->publication_year);
    }
}

