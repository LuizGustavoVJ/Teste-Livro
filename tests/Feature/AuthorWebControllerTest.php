<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Author;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthorWebControllerTest extends TestCase
{
    use  DatabaseTransactions, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar um usuário para autenticação
        $this->user = User::factory()->create();
    }

    /**
     * Testa se a página de listagem de autores carrega corretamente.
     */
    public function test_pagina_listagem_autores_carrega_corretamente()
    {
        $this->actingAs($this->user);

        // Criar alguns autores para teste
        Author::factory()->count(3)->create();

        $response = $this->get(route('authors.index'));

        $response->assertStatus(200);
        $response->assertViewIs('authors.index');
        $response->assertViewHas('authors');
    }

    /**
     * Testa se a página de criação de autor carrega corretamente.
     */
    public function test_pagina_criacao_autor_carrega_corretamente()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('authors.create'));

        $response->assertStatus(200);
        $response->assertViewIs('authors.create');
        $response->assertSeeText('Criar Novo Autor');
    }

    /**
     * Testa se um autor pode ser criado via POST.
     */
    public function test_autor_pode_ser_criado_via_post()
    {
        $this->actingAs($this->user);

        // Gera um nome único com base no timestamp
        $nomeUnico = 'LLLLGGGGFFFF' . uniqid();

        $dadosAutor = [
            'name' => $nomeUnico
        ];

        $response = $this->post(route('authors.store'), $dadosAutor);

        $response->assertRedirect(route('authors.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('authors', [
            'name' => $nomeUnico
        ]);
    }

    /**
     * Testa se a validação falha com nome vazio.
     */
    public function test_validacao_falha_com_nome_vazio()
    {
        $this->actingAs($this->user);

        $dadosInvalidos = [
            'name' => ''
        ];

        $response = $this->post(route('authors.store'), $dadosInvalidos);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Testa se a página de visualização de autor carrega corretamente.
     */
    public function test_pagina_visualizacao_autor_carrega_corretamente()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create();

        $response = $this->get(route('authors.show', $autor));

        $response->assertStatus(200);
        $response->assertViewIs('authors.show');
        $response->assertViewHas('author');
        $response->assertSeeText($autor->name);
    }

    /**
     * Testa se a página de edição de autor carrega corretamente.
     */
    public function test_pagina_edicao_autor_carrega_corretamente()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create();

        $response = $this->get(route('authors.edit', $autor));

        $response->assertStatus(200);
        $response->assertViewIs('authors.edit');
        $response->assertViewHas('author');
        $response->assertSee('value="' . e($autor->name) . '"', false);
    }

    /**
     * Testa se um autor pode ser atualizado via PUT.
     */
    public function test_autor_pode_ser_atualizado_via_put()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create(['name' => 'Nome Original']);

        $nomeNovo = 'Nome Atualizado ' . uniqid();

        $dadosAtualizados = [
            'name' => $nomeNovo
        ];

        $response = $this->put(route('authors.update', $autor), $dadosAtualizados);

        $response->assertRedirect(route('authors.index'));
        $response->assertSessionHas('success');

        $autor->refresh();
        $this->assertEquals($nomeNovo, $autor->name);
    }

    /**
     * Testa se um autor pode ser excluído via DELETE.
     */
    public function test_autor_pode_ser_excluido_via_delete()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create();
        $autorId = $autor->id;

        $response = $this->delete(route('authors.destroy', $autor));

        $response->assertRedirect(route('authors.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('authors', ['id' => $autorId]);
    }

    /**
     * Testa se usuários não autenticados são redirecionados.
     */
    public function test_usuarios_nao_autenticados_sao_redirecionados()
    {
        $response = $this->get(route('authors.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Testa se a busca de autores funciona corretamente.
     */
    public function test_busca_autores_funciona_corretamente()
    {
        $this->actingAs($this->user);

        $autor1 = Author::factory()->create(['name' => 'Machado de Assis']);
        $autor2 = Author::factory()->create(['name' => 'José de Alencar']);
        $autor3 = Author::factory()->create(['name' => 'Clarice Lispector']);

        $response = $this->get(route('authors.index', ['search' => 'Machado']));

        $response->assertStatus(200);
        $response->assertSeeText('Machado de Assis');
        $response->assertDontSee('José de Alencar');
        $response->assertDontSee('Clarice Lispector');
    }

    /**
     * Testa se a paginação funciona corretamente.
     */
    public function test_paginacao_funciona_corretamente()
    {
        $this->actingAs($this->user);

        // Criar mais autores do que cabem em uma página
        Author::factory()->count(20)->create();

        $response = $this->get(route('authors.index'));

        $response->assertStatus(200);
        $response->assertViewHas('authors');

        $autores = $response->viewData('authors');
        $this->assertLessThanOrEqual(config('pagination.authors', 10), $autores->count());
    }

    /**
     * Testa se não é possível criar autor com nome duplicado.
     */
    public function test_nao_pode_criar_autor_com_nome_duplicado()
    {
        $this->actingAs($this->user);

        // Criar primeiro autor
        Author::factory()->create(['name' => 'Machado de Assis']);

        // Tentar criar segundo autor com mesmo nome
        $dadosAutor = [
            'name' => 'Machado de Assis'
        ];

        $response = $this->post(route('authors.store'), $dadosAutor);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Testa se o nome do autor é obrigatório.
     */
    public function test_nome_autor_e_obrigatorio()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('authors.store'), []);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Testa se o nome do autor deve ter pelo menos 2 caracteres.
     */
    public function test_nome_autor_deve_ter_minimo_caracteres()
    {
        $this->actingAs($this->user);

        $dadosAutor = [
            'name' => 'A' // Apenas 1 caractere
        ];

        $response = $this->post(route('authors.store'), $dadosAutor);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Testa se o nome do autor não pode exceder o limite máximo.
     */
    public function test_nome_autor_nao_pode_exceder_limite_maximo()
    {
        $this->actingAs($this->user);

        $dadosAutor = [
            'name' => str_repeat('A', 256) // Nome muito longo
        ];

        $response = $this->post(route('authors.store'), $dadosAutor);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Testa se a contagem de livros do autor é exibida corretamente.
     */
    public function test_contagem_livros_autor_exibida_corretamente()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create();

        // Criar alguns livros para o autor
        $livros = \App\Models\Book::factory()->count(3)->create();
        $autor->books()->attach($livros->pluck('id'));

        $response = $this->get(route('authors.show', $autor));

        $response->assertStatus(200);
        $response->assertSeeText((string) $autor->books->count());
    }

    /**
     * Testa se a exclusão de autor com livros associados é tratada corretamente.
     */
    public function test_exclusao_autor_com_livros_associados()
    {
        $this->actingAs($this->user);

        $autor = Author::factory()->create();
        $livro = \App\Models\Book::factory()->create();
        $autor->books()->attach($livro->id);

        $response = $this->delete(route('authors.destroy', $autor));

        // Deve permitir a exclusão, mas manter os livros
        $response->assertRedirect(route('authors.index'));
        $this->assertSoftDeleted('authors', ['id' => $autor->id]);
        $this->assertDatabaseHas('books', ['id' => $livro->id]);
    }
}

