<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BookControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Testa se a listagem de livros via API funciona.
     */
    public function test_pode_listar_livros_via_api()
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/books');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'publication_year',
                    'isbn',
                    'price',
                    'authors',
                    'subjects',
                ],
            ],
        ]);
    }

    /**
     * Testa se um livro pode ser criado via API.
     */
    public function test_pode_criar_livro_via_api()
    {
        $authors = Author::factory()->count(2)->create();
        $subjects = Subject::factory()->count(2)->create();

        $dados = [
            'title' => 'Livro Teste API',
            'publication_year' => 2023,
            'isbn' => '1234567891123',
            'price' => 29.99,
            'authors' => $authors->pluck('id')->toArray(),
            'subjects' => $subjects->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/v1/books', $dados);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Livro criado com sucesso',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Livro Teste API',
            'price' => 29.99,
        ]);
    }

    /**
     * Testa validação de campos obrigatórios na criação via API.
     */
    public function test_validacao_campos_obrigatorios_criacao_api()
    {
        $response = $this->postJson('/api/v1/books', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'price', 'authors', 'subjects']);
    }

    /**
     * Testa se um livro pode ser visualizado via API.
     */
    public function test_pode_visualizar_livro_via_api()
    {
        $book = Book::factory()->create();
        $authors = Author::factory()->count(2)->create();
        $subjects = Subject::factory()->count(2)->create();

        $book->authors()->attach($authors);
        $book->subjects()->attach($subjects);

        $response = $this->getJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
            ],
        ]);
    }

    /**
     * Testa se retorna 404 para livro inexistente via API.
     */
    public function test_retorna_404_livro_inexistente_api()
    {
        $response = $this->getJson('/api/v1/books/999');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Livro não encontrado',
        ]);
    }

    /**
     * Testa se um livro pode ser atualizado via API.
     */
    public function test_pode_atualizar_livro_via_api()
    {
        $book = Book::factory()->create();
        $authors = Author::factory()->count(2)->create();
        $subjects = Subject::factory()->count(2)->create();

        $dados = [
            'title' => 'Título Atualizado API',
            'publication_year' => 2024,
            'isbn' => '9876543210987',
            'price' => 39.99,
            'authors' => $authors->pluck('id')->toArray(),
            'subjects' => $subjects->pluck('id')->toArray(),
        ];

        $response = $this->putJson("/api/v1/books/{$book->id}", $dados);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Livro atualizado com sucesso',
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Título Atualizado API',
        ]);
    }

    /**
     * Testa se um livro pode ser excluído via API.
     */
    public function test_pode_excluir_livro_via_api()
    {
        $book = Book::factory()->create();
        $bookId = $book->id;

        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Livro excluído com sucesso',
        ]);

        $this->assertSoftDeleted('books', ['id' => $bookId]);
    }
}

