<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Testa se o relatório de livros por autor é exibido corretamente.
     */
    public function test_pode_visualizar_relatorio_livros_por_autor()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $subject = Subject::factory()->create();

        $book->authors()->attach($author);
        $book->subjects()->attach($subject);

        $response = $this->get(route('reports.books-by-author'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.books_by_author');
        $response->assertViewHas('authors');
        $response->assertSeeText($author->name);
        $response->assertSeeText($book->title);
    }

    /**
     * Testa se o PDF do relatório pode ser gerado.
     */
    public function test_pode_gerar_pdf_relatorio_livros_por_autor()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $subject = Subject::factory()->create();

        $book->authors()->attach($author);
        $book->subjects()->attach($subject);

        $response = $this->get(route('reports.books-by-author.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('relatorio-livros-por-autor.pdf', $response->headers->get('Content-Disposition'));
    }

    /**
     * Testa se o relatório exibe autores sem livros.
     */
    public function test_relatorio_exibe_autores_sem_livros()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create();

        $response = $this->get(route('reports.books-by-author'));

        $response->assertStatus(200);
        $response->assertSeeText($author->name);
    }

    /**
     * Testa se o relatório exibe múltiplos autores com seus livros.
     */
    public function test_relatorio_exibe_multiplos_autores()
    {
        $this->actingAs($this->user);

        $author1 = Author::factory()->create(['name' => 'Autor 1']);
        $author2 = Author::factory()->create(['name' => 'Autor 2']);

        $book1 = Book::factory()->create(['title' => 'Livro 1']);
        $book2 = Book::factory()->create(['title' => 'Livro 2']);

        $book1->authors()->attach($author1);
        $book2->authors()->attach($author2);

        $response = $this->get(route('reports.books-by-author'));

        $response->assertStatus(200);
        $response->assertSeeText('Autor 1');
        $response->assertSeeText('Autor 2');
        $response->assertSeeText('Livro 1');
        $response->assertSeeText('Livro 2');
    }

    /**
     * Testa se o relatório carrega relacionamentos corretamente.
     */
    public function test_relatorio_carrega_relacionamentos()
    {
        $this->actingAs($this->user);

        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $subject = Subject::factory()->create();

        $book->authors()->attach($author);
        $book->subjects()->attach($subject);

        $response = $this->get(route('reports.books-by-author'));

        $response->assertStatus(200);
        $authors = $response->viewData('authors');

        $this->assertNotEmpty($authors, 'A coleção de autores não deve estar vazia');

        $firstAuthor = $authors->first();
        $this->assertNotNull($firstAuthor, 'O primeiro autor não deve ser null');
        $this->assertTrue($firstAuthor->relationLoaded('books'));

        $firstBook = $firstAuthor->books->first();
        if ($firstBook) {
            $this->assertTrue($firstBook->relationLoaded('subjects'));
        }
    }

    /**
     * Testa se usuários não autenticados são redirecionados.
     */
    public function test_usuarios_nao_autenticados_sao_redirecionados()
    {
        $response = $this->get(route('reports.books-by-author'));

        $response->assertRedirect(route('login'));
    }
}

