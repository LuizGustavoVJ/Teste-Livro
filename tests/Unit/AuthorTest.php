<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Author;
use App\Models\Book;
use Tests\TestCase as BaseTestCase;

class AuthorTest extends BaseTestCase
{
    /**
     * Testa se um autor pode ser criado com sucesso.
     */
    public function test_author_can_be_created()
    {
        $authorData = [
            'name' => 'J.K. Rowling'
        ];

        $author = Author::create($authorData);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('J.K. Rowling', $author->name);
        $this->assertDatabaseHas('authors', $authorData);
    }

    /**
     * Testa se o nome do autor é obrigatório.
     */
    public function test_author_name_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Author::create([]);
    }

    /**
     * Testa se um autor pode ter múltiplos livros.
     */
    public function test_author_can_have_multiple_books()
    {
        $author = Author::factory()->create();

        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();

        $author->books()->attach([$book1->id, $book2->id]);

        $this->assertCount(2, $author->books);
        $this->assertTrue($author->books->contains($book1));
        $this->assertTrue($author->books->contains($book2));
    }

    /**
     * Testa se o relacionamento com livros funciona corretamente.
     */
    public function test_author_books_relationship()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();

        $author->books()->attach($book->id);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $author->books);
        $this->assertEquals(1, $author->books->count());
    }

    /**
     * Testa se um autor pode ser atualizado.
     */
    public function test_author_can_be_updated()
    {
        $author = Author::factory()->create(['name' => 'Nome Original']);

        $author->update(['name' => 'Nome Atualizado']);

        $this->assertEquals('Nome Atualizado', $author->fresh()->name);
        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Nome Atualizado']);
    }

    /**
     * Testa se um autor pode ser excluído.
     */
    public function test_author_can_be_deleted()
    {
        $author = Author::factory()->create();
        $authorId = $author->id;

        $author->delete();

        $this->assertDatabaseMissing('authors', ['id' => $authorId]);
    }

    /**
     * Testa se os timestamps são preenchidos automaticamente.
     */
    public function test_author_has_timestamps()
    {
        $author = Author::factory()->create();

        $this->assertNotNull($author->created_at);
        $this->assertNotNull($author->updated_at);
    }
}

