<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookValorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_livro_com_valor()
    {
        $book = Book::create([
            'title' => 'Livro Teste',
            'publication_year' => 2023,
            'isbn' => '9788535902778',
            'price' => 29.90,
            'valor' => 35.50,
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Livro Teste',
            'valor' => 35.50,
        ]);

        $this->assertEquals(35.50, $book->valor);
    }

    /** @test */
    public function valor_pode_ser_nulo()
    {
        $book = Book::create([
            'title' => 'Livro Teste',
            'publication_year' => 2023,
            'isbn' => '9788535902778',
            'price' => 29.90,
            'valor' => null,
        ]);

        $this->assertNull($book->valor);
        $this->assertDatabaseHas('books', [
            'title' => 'Livro Teste',
            'valor' => null,
        ]);
    }

    /** @test */
    public function valor_e_convertido_para_decimal()
    {
        $book = Book::create([
            'title' => 'Livro Teste',
            'publication_year' => 2023,
            'price' => 40.00,
            'valor' => '45.99',
        ]);

        $this->assertIsNumeric($book->valor);
        $this->assertEquals(45.99, $book->valor);
    }

    /** @test */
    public function pode_atualizar_valor_do_livro()
    {
        $book = Book::create([
            'title' => 'Livro Teste',
            'publication_year' => 2023,
            'price' => 25.00,
            'valor' => 29.90,
        ]);

        $book->update(['valor' => 39.90]);

        $this->assertEquals(39.90, $book->fresh()->valor);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'valor' => 39.90,
        ]);
    }

    /** @test */
    public function valor_aceita_valores_decimais_com_duas_casas()
    {
        $book = Book::create([
            'title' => 'Livro Teste',
            'publication_year' => 2023,
            'price' => 100.00,
            'valor' => 123.45,
        ]);

        $this->assertEquals(123.45, $book->valor);
    }

    /** @test */
    public function valor_zero_e_valido()
    {
        $book = Book::create([
            'title' => 'Livro Teste',
            'publication_year' => 2023,
            'price' => 15.00,
            'valor' => 0.00,
        ]);

        $this->assertEquals(0.00, $book->valor);
        $this->assertDatabaseHas('books', [
            'title' => 'Livro Teste',
            'valor' => 0.00,
        ]);
    }
}
