<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Subject;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as BaseTestCase;

class SubjectTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Testa se um assunto pode ser criado com sucesso.
     */
    public function test_subject_can_be_created()
    {
        $subjectData = [
            'description' => 'Ficção Científica'
        ];

        $subject = Subject::create($subjectData);

        $this->assertInstanceOf(Subject::class, $subject);
        $this->assertEquals('Ficção Científica', $subject->description);
        $this->assertDatabaseHas('subjects', $subjectData);
    }

    /**
     * Testa se a descrição do assunto é obrigatória.
     */
    public function test_subject_description_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Subject::create([]);
    }

    /**
     * Testa se um assunto pode ter múltiplos livros.
     */
    public function test_subject_can_have_multiple_books()
    {
        $subject = Subject::factory()->create();
        
        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();
        
        $subject->books()->attach([$book1->id, $book2->id]);
        
        $this->assertCount(2, $subject->books);
        $this->assertTrue($subject->books->contains($book1));
        $this->assertTrue($subject->books->contains($book2));
    }

    /**
     * Testa se o relacionamento com livros funciona corretamente.
     */
    public function test_subject_books_relationship()
    {
        $subject = Subject::factory()->create();
        $book = Book::factory()->create();
        
        $subject->books()->attach($book->id);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $subject->books);
        $this->assertEquals(1, $subject->books->count());
    }

    /**
     * Testa se um assunto pode ser atualizado.
     */
    public function test_subject_can_be_updated()
    {
        $subject = Subject::factory()->create(['description' => 'Descrição Original']);
        
        $subject->update(['description' => 'Descrição Atualizada']);
        
        $this->assertEquals('Descrição Atualizada', $subject->fresh()->description);
        $this->assertDatabaseHas('subjects', ['id' => $subject->id, 'description' => 'Descrição Atualizada']);
    }

    /**
     * Testa se um assunto pode ser excluído.
     */
    public function test_subject_can_be_deleted()
    {
        $subject = Subject::factory()->create();
        $subjectId = $subject->id;
        
        $subject->delete();
        
        $this->assertDatabaseMissing('subjects', ['id' => $subjectId]);
    }

    /**
     * Testa se os timestamps são preenchidos automaticamente.
     */
    public function test_subject_has_timestamps()
    {
        $subject = Subject::factory()->create();
        
        $this->assertNotNull($subject->created_at);
        $this->assertNotNull($subject->updated_at);
    }

    /**
     * Testa se a descrição pode ter até 255 caracteres.
     */
    public function test_subject_description_max_length()
    {
        $longDescription = str_repeat('a', 255);
        
        $subject = Subject::create(['description' => $longDescription]);
        
        $this->assertEquals($longDescription, $subject->description);
        $this->assertEquals(255, strlen($subject->description));
    }
}

