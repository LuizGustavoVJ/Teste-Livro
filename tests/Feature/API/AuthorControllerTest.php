<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthorControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Testa se a listagem de autores via API funciona.
     */
    public function test_pode_listar_autores_via_api()
    {
        Author::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/authors');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    /**
     * Testa se um autor pode ser criado via API.
     */
    public function test_pode_criar_autor_via_api()
    {
        $dados = [
            'name' => 'Autor Teste API',
        ];

        $response = $this->postJson('/api/v1/authors', $dados);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Autor criado com sucesso',
        ]);

        $this->assertDatabaseHas('authors', $dados);
    }

    /**
     * Testa validação de campos obrigatórios na criação via API.
     */
    public function test_validacao_campos_obrigatorios_criacao_api()
    {
        $response = $this->postJson('/api/v1/authors', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Testa se um autor pode ser visualizado via API.
     */
    public function test_pode_visualizar_autor_via_api()
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(2)->create();
        $author->books()->attach($books);

        $response = $this->getJson("/api/v1/authors/{$author->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $author->id,
                'name' => $author->name,
            ],
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'books',
            ],
        ]);
    }

    /**
     * Testa se retorna 404 para autor inexistente via API.
     */
    public function test_retorna_404_autor_inexistente_api()
    {
        $response = $this->getJson('/api/v1/authors/999');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Autor não encontrado',
        ]);
    }

    /**
     * Testa se um autor pode ser atualizado via API.
     */
    public function test_pode_atualizar_autor_via_api()
    {
        $author = Author::factory()->create();

        $dados = [
            'name' => 'Nome Atualizado API',
        ];

        $response = $this->putJson("/api/v1/authors/{$author->id}", $dados);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Autor atualizado com sucesso',
        ]);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'Nome Atualizado API',
        ]);
    }

    /**
     * Testa se um autor pode ser excluído via API.
     */
    public function test_pode_excluir_autor_via_api()
    {
        $author = Author::factory()->create();
        $authorId = $author->id;

        $response = $this->deleteJson("/api/v1/authors/{$author->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Autor excluído com sucesso',
        ]);

        $this->assertSoftDeleted('authors', ['id' => $authorId]);
    }
}

