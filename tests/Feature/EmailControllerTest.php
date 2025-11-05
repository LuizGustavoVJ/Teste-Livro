<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Jobs\SendEmailJob;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Mail;

class EmailControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Queue::fake();
    }

    /**
     * Testa se o formulário de email é exibido corretamente.
     */
    public function test_pode_visualizar_formulario_email()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('email.form'));

        $response->assertStatus(200);
        $response->assertViewIs('emails.send_form');
    }

    /**
     * Testa se o email pode ser enviado com sucesso.
     */
    public function test_pode_enviar_email_com_sucesso()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $subject = Subject::factory()->create();

        $book->authors()->attach($author);
        $book->subjects()->attach($subject);

        $dados = [
            'email' => 'teste@example.com',
            'subject' => 'Relatório de Teste',
        ];

        $response = $this->postJson('/api/v1/send-email-report', $dados);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'E-mail adicionado à fila de envio com sucesso',
        ]);

        Queue::assertPushed(SendEmailJob::class);
    }

    /**
     * Testa validação de email obrigatório.
     */
    public function test_validacao_email_obrigatorio()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/v1/send-email-report', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Testa validação de formato de email.
     */
    public function test_validacao_formato_email()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/v1/send-email-report', [
            'email' => 'email-invalido',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Testa se o subject padrão é usado quando não fornecido.
     */
    public function test_usar_subject_padrao_quando_nao_fornecido()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);

        $dados = [
            'email' => 'teste@example.com',
        ];

        $response = $this->postJson('/api/v1/send-email-report', $dados);

        $response->assertStatus(200);

        Queue::assertPushed(SendEmailJob::class);
    }

    /**
     * Testa se o subject personalizado é usado quando fornecido.
     */
    public function test_usar_subject_personalizado_quando_fornecido()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);

        $dados = [
            'email' => 'teste@example.com',
            'subject' => 'Meu Relatório Personalizado',
        ];

        $response = $this->postJson('/api/v1/send-email-report', $dados);

        $response->assertStatus(200);

        Queue::assertPushed(SendEmailJob::class);
    }

    /**
     * Testa se o relatório contém dados corretos.
     */
    public function test_relatorio_contem_dados_corretos()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create(['name' => 'Autor Teste']);
        $book = Book::factory()->create(['title' => 'Livro Teste']);
        $subject = Subject::factory()->create(['description' => 'Assunto Teste']);

        $book->authors()->attach($author);
        $book->subjects()->attach($subject);

        $dados = [
            'email' => 'teste@example.com',
        ];

        $response = $this->postJson('/api/v1/send-email-report', $dados);

        $response->assertStatus(200);

        Queue::assertPushed(SendEmailJob::class);
    }

    /**
     * Testa se usuários não autenticados são redirecionados.
     */
    public function test_usuarios_nao_autenticados_sao_redirecionados()
    {
        $response = $this->get(route('email.form'));

        $response->assertRedirect(route('login'));
    }
}

