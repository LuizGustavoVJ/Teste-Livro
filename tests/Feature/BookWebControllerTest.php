<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BookWebControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Testa se a página de listagem de livros é exibida corretamente.
     */
    public function test_pode_visualizar_pagina_de_listagem_de_livros()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $livros = Book::factory()->count(3)->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertViewIs('books.index');
        $response->assertViewHas('books');
    }

    /**
     * Testa se a página de criação de livro é exibida corretamente.
     */
    public function test_pode_visualizar_pagina_de_criacao_de_livro()
    {
                // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $autores = Author::factory()->count(2)->create();
        $assuntos = Subject::factory()->count(2)->create();

        $response = $this->get(route('books.create'));

        $response->assertStatus(200);
        $response->assertViewIs('books.create');
        $response->assertViewHas(['authors', 'subjects']);
    }

    /**
     * Testa se um livro pode ser criado com sucesso.
     */
    public function test_pode_criar_livro_com_sucesso()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $autores = Author::factory()->count(2)->create();
        $assuntos = Subject::factory()->count(2)->create();

        $dadosLivro = [
            'title' => 'Livro de Teste',
            'publication_year' => 2023,
            'isbn' => '1234567891123',
            'price' => 29.99,
            'authors' => $autores->pluck('id')->toArray(),
            'subjects' => $assuntos->pluck('id')->toArray(),
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Livro criado com sucesso!');

        $this->assertDatabaseHas('books', [
            'title' => 'Livro de Teste',
            'publication_year' => 2023,
            'isbn' => '1234567891123',
            'price' => 29.99,
        ]);

        $livro = Book::where('title', 'Livro de Teste')->first();
        $this->assertCount(2, $livro->authors);
        $this->assertCount(2, $livro->subjects);
    }

    /**
     * Testa se um livro pode ser criado com imagem de capa.
     */
    public function test_pode_criar_livro_com_imagem_de_capa()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $autores = Author::factory()->count(1)->create();
        $assuntos = Subject::factory()->count(1)->create();
        $imagemFake = UploadedFile::fake()->image('capa.jpg', 300, 400);

        $dadosLivro = [
            'title' => 'Livro com Capa',
            'publication_year' => 2023,
            'isbn' => '1234567891123',
            'price' => 39.99,
            'authors' => $autores->pluck('id')->toArray(),
            'subjects' => $assuntos->pluck('id')->toArray(),
            'cover_image' => $imagemFake,
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Livro criado com sucesso!');

        $livro = Book::where('title', 'Livro com Capa')->first();
        $this->assertNotNull($livro->cover_image_path);
        Storage::disk('public')->assertExists($livro->cover_image_path);
    }

    /**
     * Testa validação de campos obrigatórios na criação.
     */
    public function test_validacao_campos_obrigatorios_na_criacao()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $response = $this->post(route('books.store'), []);

        $response->assertSessionHasErrors(['title', 'price']);
    }

    /**
     * Testa se a página de visualização de livro é exibida corretamente.
     */
    public function test_pode_visualizar_detalhes_do_livro()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $livro = Book::factory()->create();
        $autores = Author::factory()->count(2)->create();
        $assuntos = Subject::factory()->count(2)->create();

        $livro->authors()->attach($autores);
        $livro->subjects()->attach($assuntos);

        $response = $this->get(route('books.show', $livro));

        $response->assertStatus(200);
        $response->assertViewIs('books.show');
        $response->assertViewHas('book');
        $response->assertSeeText($livro->title);

        foreach ($autores as $autor) {
            $response->assertSeeText($autor->name);
        }

        foreach ($assuntos as $assunto) {
            $response->assertSeeText($assunto->description);
        }
    }

    /**
     * Testa se a página de edição de livro é exibida corretamente.
     */
    public function test_pode_visualizar_pagina_de_edicao_de_livro()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $livro = Book::factory()->create();
        $autores = Author::factory()->count(2)->create();
        $assuntos = Subject::factory()->count(2)->create();

        $response = $this->get(route('books.edit', $livro));

        $response->assertStatus(200);
        $response->assertViewIs('books.edit');
        $response->assertViewHas(['book', 'authors', 'subjects']);
        $response->assertSeeText($livro->title);
    }

    /**
     * Testa se um livro pode ser atualizado com sucesso.
     */
    public function test_pode_atualizar_livro_com_sucesso()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $livro = Book::factory()->create();
        $autores = Author::factory()->count(2)->create();
        $assuntos = Subject::factory()->count(2)->create();

        $dadosAtualizados = [
            'title' => 'Título Atualizado',
            'publication_year' => 2024,
            'isbn' => '1234567891123',
            'price' => 49.99,
            'authors' => $autores->pluck('id')->toArray(),
            'subjects' => $assuntos->pluck('id')->toArray(),
        ];

        $response = $this->put(route('books.update', $livro), $dadosAtualizados);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Livro atualizado com sucesso!');

        $this->assertDatabaseHas('books', [
            'id' => $livro->id,
            'title' => 'Título Atualizado',
            'publication_year' => 2024,
            'isbn' => '1234567891123',
            'price' => 49.99,
        ]);
    }

    /**
     * Testa se um livro pode ser excluído com sucesso.
     */
    public function test_pode_excluir_livro_com_sucesso()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $livro = Book::factory()->create();
        $livroId = $livro->id;

        $response = $this->delete(route('books.destroy', $livro));

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Livro excluído com sucesso!');

        $this->assertSoftDeleted('books', ['id' => $livroId]);
    }

    /**
     * Testa se a imagem de capa é excluída quando o livro é removido.
     */
    public function test_imagem_de_capa_e_excluida_quando_livro_e_removido()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $livro = Book::factory()->create();
        $imagemFake = UploadedFile::fake()->image('capa.jpg');

        // Simula o upload da imagem
        $caminhoImagem = $imagemFake->store('book-covers', 'public');
        $livro->update(['cover_image_path' => $caminhoImagem]);

        Storage::disk('public')->assertExists($caminhoImagem);

        $response = $this->delete(route('books.destroy', $livro));

        $response->assertRedirect(route('books.index'));
        Storage::disk('public')->assertMissing($caminhoImagem);
    }

    /**
     * Testa se retorna 404 para livro inexistente.
     */
    public function test_retorna_404_para_livro_inexistente()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $response = $this->get(route('books.show', 999));
        $response->assertStatus(404);

        $response = $this->get(route('books.edit', 999));
        $response->assertStatus(404);

        $response = $this->put(route('books.update', 999), []);
        $response->assertStatus(404);

        $response = $this->delete(route('books.destroy', 999));
        $response->assertStatus(404);
    }

    /**
     * Testa validação de formato de imagem.
     */
    public function test_validacao_formato_imagem()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        $autores = Author::factory()->count(1)->create();
        $assuntos = Subject::factory()->count(1)->create();
        $arquivoInvalido = UploadedFile::fake()->create('documento.pdf', 1000);

        $dadosLivro = [
            'title' => 'Livro Teste',
            'price' => 29.99,
            'authors' => $autores->pluck('id')->toArray(),
            'subjects' => $assuntos->pluck('id')->toArray(),
            'cover_image' => $arquivoInvalido,
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertSessionHasErrors(['cover_image']);
    }

    /**
     * Testa se a paginação funciona corretamente.
     */
    public function test_paginacao_funciona_corretamente()
    {
        // Cria um usuário para autenticação
        $user = User::factory()->create();
        // Autentique-se com esse usuário
        $this->actingAs($user);

        Book::factory()->count(25)->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertViewHas('books');

        $livros = $response->viewData('books');
        $this->assertLessThanOrEqual(config('pagination.books', 15), $livros->count());
    }
}

