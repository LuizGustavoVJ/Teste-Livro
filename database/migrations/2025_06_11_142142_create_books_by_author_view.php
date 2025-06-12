<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Criando a view de livros por autor para SQLite
        DB::statement("
            CREATE VIEW books_by_author_view AS
            SELECT 
                a.id AS author_id,
                a.name AS author_name,
                b.id AS book_id,
                b.title AS book_title,
                b.publication_year,
                b.isbn,
                b.price,
                GROUP_CONCAT(s.description) AS subjects
            FROM 
                authors a
            JOIN 
                book_author ba ON a.id = ba.author_id
            JOIN 
                books b ON ba.book_id = b.id
            LEFT JOIN 
                book_subject bs ON b.id = bs.book_id
            LEFT JOIN 
                subjects s ON bs.subject_id = s.id
            WHERE 
                a.deleted_at IS NULL AND
                b.deleted_at IS NULL
            GROUP BY 
                a.id, a.name, b.id, b.title, b.publication_year, b.isbn, b.price
            ORDER BY 
                a.name, b.title
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS books_by_author_view');
    }
};

