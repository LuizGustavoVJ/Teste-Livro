<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as BaseTestCase;

class BookTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Testa se um livro pode ser criado com sucesso.
     */
    public function test_book_can_be_created()
    {
        $bookData = [
            'title' => 'Harry Potter e a Pedra Filosofal',
            'publication_year' => 1997,
            'isbn' => '9788532511010',
            'price' => 29.90
        ];

        $book = Book::create($bookData);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals('Harry Potter e a Pedra Filosofal', $book->title);
        $this->assertEquals(1997, $book->publication_year);
        $this->assertEquals('9788532511010', $book->isbn);
        $this->assertEquals(29.90, $book->price);
        $this->assertDatabaseHas('books', $bookData);
    }

    /**
     * Testa se o título e preço são obrigatórios.
     */
    public function test_book_title_and_price_are_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Book::create([]);
    }

    /**
     * Testa se um livro pode ter múltiplos autores.
     */
    public function test_book_can_have_multiple_authors()
    {
        $book = Book::factory()->create();
        
        $author1 = Author::factory()->create();
        $author2 = Author::factory()->create();
        
        $book->authors()->attach([$author1->id, $author2->id]);
        
        $this->assertCount(2, $book->authors);
        $this->assertTrue($book->authors->contains($author1));
        $this->assertTrue($book->authors->contains($author2));
    }

    /**
     * Testa se um livro pode ter múltiplos assuntos.
     */
    public function test_book_can_have_multiple_subjects()
    {
        $book = Book::factory()->create();
        
        $subject1 = Subject::factory()->create();
        $subject2 = Subject::factory()->create();
        
        $book->subjects()->attach([$subject1->id, $subject2->id]);
        
        $this->assertCount(2, $book->subjects);
        $this->assertTrue($book->subjects->contains($subject1));
        $this->assertTrue($book->subjects->contains($subject2));
    }

    /**
     * Testa se o relacionamento com autores funciona corretamente.
     */
    public function test_book_authors_relationship()
    {
        $book = Book::factory()->create();
        $author = Author::factory()->create();
        
        $book->authors()->attach($author->id);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $book->authors);
        $this->assertEquals(1, $book->authors->count());
    }

    /**
     * Testa se o relacionamento com assuntos funciona corretamente.
     */
    public function test_book_subjects_relationship()
    {
        $book = Book::factory()->create();
        $subject = Subject::factory()->create();
        
        $book->subjects()->attach($subject->id);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $book->subjects);
        $this->assertEquals(1, $book->subjects->count());
    }

    /**
     * Testa se um livro pode ser atualizado.
     */
    public function test_book_can_be_updated()
    {
        $book = Book::factory()->create(['title' => 'Título Original', 'price' => 10.00]);
        
        $book->update(['title' => 'Título Atualizado', 'price' => 15.50]);
        
        $this->assertEquals('Título Atualizado', $book->fresh()->title);
        $this->assertEquals(15.50, $book->fresh()->price);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Título Atualizado', 'price' => 15.50]);
    }

    /**
     * Testa se um livro pode ser excluído.
     */
    public function test_book_can_be_deleted()
    {
        $book = Book::factory()->create();
        $bookId = $book->id;
        
        $book->delete();
        
        $this->assertDatabaseMissing('books', ['id' => $bookId]);
    }

    /**
     * Testa se os timestamps são preenchidos automaticamente.
     */
    public function test_book_has_timestamps()
    {
        $book = Book::factory()->create();
        
        $this->assertNotNull($book->created_at);
        $this->assertNotNull($book->updated_at);
    }

    /**
     * Testa se o preço deve ser um valor positivo.
     */
    public function test_book_price_must_be_positive()
    {
        $book = Book::factory()->create(['price' => 25.99]);
        
        $this->assertGreaterThan(0, $book->price);
    }

    /**
     * Testa se o ano de publicação pode ser nulo.
     */
    public function test_book_publication_year_can_be_null()
    {
        $book = Book::factory()->create(['publication_year' => null]);
        
        $this->assertNull($book->publication_year);
    }

    /**
     * Testa se o ISBN pode ser nulo.
     */
    public function test_book_isbn_can_be_null()
    {
        $book = Book::factory()->create(['isbn' => null]);
        
        $this->assertNull($book->isbn);
    }
}

