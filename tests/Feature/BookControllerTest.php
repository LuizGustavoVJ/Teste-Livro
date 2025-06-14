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

class BookControllerTest extends TestCase
{
    use  DatabaseTransactions, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar um usuário para autenticação
        $this->user = User::factory()->create();

        // Configurar storage fake para testes de upload
        Storage::fake('public');
    }

    /**
     * Testa se a página de listagem de livros carrega corretamente.
     */
    public function test_pagina_listagem_livros_carrega_corretamente()
    {
        $this->actingAs($this->user);

        // Criar alguns livros para teste
        Book::factory()->count(3)->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertViewIs('books.index');
        $response->assertViewHas('livros');
    }

    /**
     * Testa se a página de criação de livro carrega corretamente.
     */
    public function test_pagina_criacao_livro_carrega_corretamente()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('books.create'));

        $response->assertStatus(200);
        $response->assertViewIs('books.create');
        $response->assertSee('Cadastrar Novo Livro');
    }

    /**
     * Testa se um livro pode ser criado via POST.
     */
    public function test_livro_pode_ser_criado_via_post()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create();
        $assunto = Subject::factory()->create();

        $dadosLivro = [
            'title' => 'Livro de Teste',
            'publication_year' => 2023,
            'isbn' => '9788535902792',
            'price' => 29.90,
            'authors' => [$autor->id],
            'subjects' => [$assunto->id]
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('sucesso');

        $this->assertDatabaseHas('books', [
            'title' => 'Livro de Teste',
            'price' => 29.90
        ]);
    }

    /**
     * Testa se um livro pode ser criado com upload de imagem.
     */
    public function test_livro_pode_ser_criado_com_upload_imagem()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create();
        $assunto = Subject::factory()->create();
        $imagemFake = UploadedFile::fake()->image('capa_teste.jpg', 300, 400);

        $dadosLivro = [
            'title' => 'Livro com Capa',
            'publication_year' => 2023,
            'isbn' => '9788535902799',
            'price' => 39.90,
            'authors' => [$autor->id],
            'subjects' => [$assunto->id],
            'imagem' => $imagemFake
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('sucesso');

        $livro = Book::where('title', 'Livro com Capa')->first();
        $this->assertNotNull($livro);
        $this->assertNotNull($livro->cover_image_path);

        // Verificar se o arquivo foi armazenado
        Storage::disk('public')->assertExists($livro->cover_image_path);
    }

    /**
     * Testa se a validação falha com dados inválidos.
     */
    public function test_validacao_falha_com_dados_invalidos()
    {
        $this->actingAs($this->user);

        $dadosInvalidos = [
            'title' => '', // Título vazio
            'price' => -10, // Preço negativo
            'publication_year' => 'abc' // Ano inválido
        ];

        $response = $this->post(route('books.store'), $dadosInvalidos);

        $response->assertSessionHasErrors(['title', 'price']);
    }

    /**
     * Testa se a página de visualização de livro carrega corretamente.
     */
    public function test_pagina_visualizacao_livro_carrega_corretamente()
    {
        $this->actingAs($this->user);

        $livro = Book::factory()->create();

        $response = $this->get(route('books.show', $livro));

        $response->assertStatus(200);
        $response->assertViewIs('books.show');
        $response->assertViewHas('book');
        $response->assertSee($livro->title);
    }

    /**
     * Testa se a página de edição de livro carrega corretamente.
     */
    public function test_pagina_edicao_livro_carrega_corretamente()
    {
        $this->actingAs($this->user);

        $livro = Book::factory()->create();

        $response = $this->get(route('books.edit', $livro));

        $response->assertStatus(200);
        $response->assertViewIs('books.edit');
        $response->assertViewHas('book');
        $response->assertSee($livro->title);
    }

    /**
     * Testa se um livro pode ser atualizado via PUT.
     */
    public function test_livro_pode_ser_atualizado_via_put()
    {
        $this->actingAs($this->user);

        $livro = Book::factory()->create();
        $autor = Author::factory()->create();
        $assunto = Subject::factory()->create();

        $dadosAtualizados = [
            'title' => 'Título Atualizado',
            'publication_year' => 2024,
            'isbn' => '9788535902806',
            'price' => 49.90,
            'authors' => [$autor->id],
            'subjects' => [$assunto->id]
        ];

        $response = $this->put(route('books.update', $livro), $dadosAtualizados);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('sucesso');

        $livro->refresh();
        $this->assertEquals('Título Atualizado', $livro->title);
        $this->assertEquals(49.90, $livro->price);
    }

    /**
     * Testa se um livro pode ser atualizado com nova imagem.
     */
    public function test_livro_pode_ser_atualizado_com_nova_imagem()
    {
        $this->actingAs($this->user);

        $livro = Book::factory()->create(['cover_image_path' => 'capas/imagem_antiga.jpg']);
        $novaImagem = UploadedFile::fake()->image('nova_capa.jpg', 300, 400);

        $dadosAtualizados = [
            'title' => $livro->title,
            'price' => $livro->price,
            'imagem' => $novaImagem
        ];

        $response = $this->put(route('books.update', $livro), $dadosAtualizados);

        $response->assertRedirect(route('books.index'));

        $livro->refresh();
        $this->assertNotEquals('capas/imagem_antiga.jpg', $livro->cover_image_path);
        $this->assertNotNull($livro->cover_image_path);

        // Verificar se a nova imagem foi armazenada
        Storage::disk('public')->assertExists($livro->cover_image_path);
    }

    /**
     * Testa se um livro pode ser excluído via DELETE.
     */
    public function test_livro_pode_ser_excluido_via_delete()
    {
        $this->actingAs($this->user);

        $livro = Book::factory()->create();
        $livroId = $livro->id;

        $response = $this->delete(route('books.destroy', $livro));

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('sucesso');

        $this->assertDatabaseMissing('books', ['id' => $livroId]);
    }

    /**
     * Testa se a exclusão de livro com imagem remove o arquivo.
     */
    public function test_exclusao_livro_com_imagem_remove_arquivo()
    {
        $this->actingAs($this->user);

        $caminhoImagem = 'capas/teste_exclusao.jpg';
        Storage::disk('public')->put($caminhoImagem, 'conteudo fake');

        $livro = Book::factory()->create(['cover_image_path' => $caminhoImagem]);

        $response = $this->delete(route('books.destroy', $livro));

        $response->assertRedirect(route('books.index'));

        // Verificar se o arquivo foi removido
        Storage::disk('public')->assertMissing($caminhoImagem);
    }

    /**
     * Testa se usuários não autenticados são redirecionados.
     */
    public function test_usuarios_nao_autenticados_sao_redirecionados()
    {
        $response = $this->get(route('books.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Testa se a busca de livros funciona corretamente.
     */
    public function test_busca_livros_funciona_corretamente()
    {
        $this->actingAs($this->user);

        $livro1 = Book::factory()->create(['title' => 'Dom Casmurro']);
        $livro2 = Book::factory()->create(['title' => 'O Cortiço']);
        $livro3 = Book::factory()->create(['title' => 'Iracema']);

        $response = $this->get(route('books.index', ['search' => 'Dom']));

        $response->assertStatus(200);
        $response->assertSee('Dom Casmurro');
        $response->assertDontSee('O Cortiço');
        $response->assertDontSee('Iracema');
    }

    /**
     * Testa se a paginação funciona corretamente.
     */
    public function test_paginacao_funciona_corretamente()
    {
        $this->actingAs($this->user);

        // Criar mais livros do que cabem em uma página
        Book::factory()->count(20)->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertViewHas('livros');

        $livros = $response->viewData('livros');
        $this->assertLessThanOrEqual(15, $livros->count()); // Assumindo 15 por página
    }

    /**
     * Testa se o upload de arquivo inválido falha.
     */
    public function test_upload_arquivo_invalido_falha()
    {
        $this->actingAs($this->user);

        $arquivoInvalido = UploadedFile::fake()->create('documento.pdf', 1000);

        $dadosLivro = [
            'title' => 'Livro Teste',
            'price' => 29.90,
            'imagem' => $arquivoInvalido
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertSessionHasErrors(['imagem']);
    }

    /**
     * Testa se o relacionamento many-to-many com autores funciona.
     */
    public function test_relacionamento_many_to_many_autores_funciona()
    {
        $this->actingAs($this->user);

        $autor1 = Author::factory()->create();
        $autor2 = Author::factory()->create();

        $dadosLivro = [
            'title' => 'Livro Múltiplos Autores',
            'price' => 35.90,
            'authors' => [$autor1->id, $autor2->id]
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertRedirect(route('books.index'));

        $livro = Book::where('title', 'Livro Múltiplos Autores')->first();
        $this->assertCount(2, $livro->authors);
        $this->assertTrue($livro->authors->contains($autor1));
        $this->assertTrue($livro->authors->contains($autor2));
    }

    /**
     * Testa se o relacionamento many-to-many com assuntos funciona.
     */
    public function test_relacionamento_many_to_many_assuntos_funciona()
    {
        $this->actingAs($this->user);

        $assunto1 = Subject::factory()->create();
        $assunto2 = Subject::factory()->create();

        $dadosLivro = [
            'title' => 'Livro Múltiplos Assuntos',
            'price' => 42.50,
            'subjects' => [$assunto1->id, $assunto2->id]
        ];

        $response = $this->post(route('books.store'), $dadosLivro);

        $response->assertRedirect(route('books.index'));

        $livro = Book::where('title', 'Livro Múltiplos Assuntos')->first();
        $this->assertCount(2, $livro->subjects);
        $this->assertTrue($livro->subjects->contains($assunto1));
        $this->assertTrue($livro->subjects->contains($assunto2));
    }
}

