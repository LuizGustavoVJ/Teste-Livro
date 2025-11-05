<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubjectWebControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Testa se a página de listagem de assuntos é exibida corretamente.
     */
    public function test_pode_visualizar_pagina_de_listagem_de_assuntos()
    {
        $this->actingAs($this->user);

        Subject::factory()->count(3)->create();

        $response = $this->get(route('subjects.index'));

        $response->assertStatus(200);
        $response->assertViewIs('subjects.index');
        $response->assertViewHas('subjects');
    }

    /**
     * Testa se a página de criação de assunto é exibida corretamente.
     */
    public function test_pode_visualizar_pagina_de_criacao_de_assunto()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('subjects.create'));

        $response->assertStatus(200);
        $response->assertViewIs('subjects.create');
    }

    /**
     * Testa se um assunto pode ser criado com sucesso.
     */
    public function test_pode_criar_assunto_com_sucesso()
    {
        $this->actingAs($this->user);

        $dadosAssunto = [
            'description' => 'Ficção Científica',
        ];

        $response = $this->post(route('subjects.store'), $dadosAssunto);

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('success', 'Assunto criado com sucesso!');

        $this->assertDatabaseHas('subjects', $dadosAssunto);
    }

    /**
     * Testa validação de campos obrigatórios na criação.
     */
    public function test_validacao_campos_obrigatorios_na_criacao()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('subjects.store'), []);

        $response->assertSessionHasErrors(['description']);
    }

    /**
     * Testa se a página de visualização de assunto é exibida corretamente.
     */
    public function test_pode_visualizar_detalhes_do_assunto()
    {
        $this->actingAs($this->user);

        $subject = Subject::factory()->create();
        $books = Book::factory()->count(2)->create();

        $subject->books()->attach($books);

        $response = $this->get(route('subjects.show', $subject));

        $response->assertStatus(200);
        $response->assertViewIs('subjects.show');
        $response->assertViewHas('subject');
        $response->assertSeeText($subject->description);
    }

    /**
     * Testa se a página de edição de assunto é exibida corretamente.
     */
    public function test_pode_visualizar_pagina_de_edicao_de_assunto()
    {
        $this->actingAs($this->user);

        $subject = Subject::factory()->create();

        $response = $this->get(route('subjects.edit', $subject));

        $response->assertStatus(200);
        $response->assertViewIs('subjects.edit');
        $response->assertViewHas('subject');
    }

    /**
     * Testa se um assunto pode ser atualizado com sucesso.
     */
    public function test_pode_atualizar_assunto_com_sucesso()
    {
        $this->actingAs($this->user);

        $subject = Subject::factory()->create();

        $dadosAtualizados = [
            'description' => 'Descrição Atualizada',
        ];

        $response = $this->put(route('subjects.update', $subject), $dadosAtualizados);

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('success', 'Assunto atualizado com sucesso!');

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'description' => 'Descrição Atualizada',
        ]);
    }

    /**
     * Testa validação de campos obrigatórios na atualização.
     */
    public function test_validacao_campos_obrigatorios_na_atualizacao()
    {
        $this->actingAs($this->user);

        $subject = Subject::factory()->create();

        $response = $this->put(route('subjects.update', $subject), []);

        $response->assertSessionHasErrors(['description']);
    }

    /**
     * Testa se um assunto pode ser excluído com sucesso.
     */
    public function test_pode_excluir_assunto_com_sucesso()
    {
        $this->actingAs($this->user);

        $subject = Subject::factory()->create();
        $subjectId = $subject->id;

        $response = $this->delete(route('subjects.destroy', $subject));

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('success', 'Assunto excluído com sucesso!');

        $this->assertSoftDeleted('subjects', ['id' => $subjectId]);
    }

    /**
     * Testa se retorna 404 para assunto inexistente.
     */
    public function test_retorna_404_para_assunto_inexistente()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('subjects.show', 999));
        $response->assertStatus(404);

        $response = $this->get(route('subjects.edit', 999));
        $response->assertStatus(404);

        $response = $this->put(route('subjects.update', 999), []);
        $response->assertStatus(404);

        $response = $this->delete(route('subjects.destroy', 999));
        $response->assertStatus(404);
    }

    /**
     * Testa se usuários não autenticados são redirecionados.
     */
    public function test_usuarios_nao_autenticados_sao_redirecionados()
    {
        $response = $this->get(route('subjects.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Testa se a paginação funciona corretamente.
     */
    public function test_paginacao_funciona_corretamente()
    {
        $this->actingAs($this->user);

        // Criar 15 assuntos com descrições únicas
        for ($i = 1; $i <= 15; $i++) {
            Subject::factory()->create(['description' => 'Assunto ' . $i . ' ' . time() . $i]);
        }

        $response = $this->get(route('subjects.index'));

        $response->assertStatus(200);
        $response->assertViewHas('subjects');

        $subjects = $response->viewData('subjects');
        $this->assertLessThanOrEqual(10, $subjects->count());
    }
}

