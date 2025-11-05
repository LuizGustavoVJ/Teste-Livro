<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Testa se a página home é exibida corretamente para usuário autenticado.
     */
    public function test_pode_visualizar_pagina_home_autenticado()
    {
        $this->actingAs($this->user);

        Book::factory()->count(3)->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('books');
    }

    /**
     * Testa se usuário não autenticado é redirecionado para login.
     */
    public function test_usuario_nao_autenticado_e_redirecionado()
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Testa se a lista de livros é paginada corretamente.
     */
    public function test_lista_livros_e_paginada()
    {
        $this->actingAs($this->user);

        Book::factory()->count(25)->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('books');

        $books = $response->viewData('books');
        $this->assertLessThanOrEqual(10, $books->count());
    }

    /**
     * Testa se os relacionamentos são carregados (authors e subjects).
     */
    public function test_relacionamentos_sao_carregados()
    {
        $this->actingAs($this->user);

        $book = Book::factory()->create();
        $authors = Author::factory()->count(2)->create();
        $subjects = Subject::factory()->count(2)->create();

        $book->authors()->attach($authors);
        $book->subjects()->attach($subjects);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $books = $response->viewData('books');

        // Verifica se os relacionamentos estão carregados
        $firstBook = $books->first();
        $this->assertTrue($firstBook->relationLoaded('authors'));
        $this->assertTrue($firstBook->relationLoaded('subjects'));
    }

    /**
     * Testa se a página home exibe corretamente quando não há livros.
     */
    public function test_exibe_corretamente_quando_nao_ha_livros()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('books');

        $books = $response->viewData('books');
        $this->assertIsObject($books);
        $this->assertLessThanOrEqual(10, $books->count());
    }
}

