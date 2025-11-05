<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Subject;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubjectControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Testa se a listagem de assuntos via API funciona.
     */
    public function test_pode_listar_assuntos_via_api()
    {
        Subject::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/subjects');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'description',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    /**
     * Testa se um assunto pode ser criado via API.
     */
    public function test_pode_criar_assunto_via_api()
    {
        $dados = [
            'description' => 'Assunto Teste API',
        ];

        $response = $this->postJson('/api/v1/subjects', $dados);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Assunto criado com sucesso',
        ]);

        $this->assertDatabaseHas('subjects', $dados);
    }

    /**
     * Testa validação de campos obrigatórios na criação via API.
     */
    public function test_validacao_campos_obrigatorios_criacao_api()
    {
        $response = $this->postJson('/api/v1/subjects', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description']);
    }

    /**
     * Testa se um assunto pode ser visualizado via API.
     */
    public function test_pode_visualizar_assunto_via_api()
    {
        $subject = Subject::factory()->create();
        $books = Book::factory()->count(2)->create();
        $subject->books()->attach($books);

        $response = $this->getJson("/api/v1/subjects/{$subject->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $subject->id,
                'description' => $subject->description,
            ],
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'description',
                'books',
            ],
        ]);
    }

    /**
     * Testa se retorna 404 para assunto inexistente via API.
     */
    public function test_retorna_404_assunto_inexistente_api()
    {
        $response = $this->getJson('/api/v1/subjects/999');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Assunto não encontrado',
        ]);
    }

    /**
     * Testa se um assunto pode ser atualizado via API.
     */
    public function test_pode_atualizar_assunto_via_api()
    {
        $subject = Subject::factory()->create();

        $dados = [
            'description' => 'Descrição Atualizada API',
        ];

        $response = $this->putJson("/api/v1/subjects/{$subject->id}", $dados);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Assunto atualizado com sucesso',
        ]);

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'description' => 'Descrição Atualizada API',
        ]);
    }

    /**
     * Testa se um assunto pode ser excluído via API.
     */
    public function test_pode_excluir_assunto_via_api()
    {
        $subject = Subject::factory()->create();
        $subjectId = $subject->id;

        $response = $this->deleteJson("/api/v1/subjects/{$subject->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Assunto excluído com sucesso',
        ]);

        $this->assertSoftDeleted('subjects', ['id' => $subjectId]);
    }
}

