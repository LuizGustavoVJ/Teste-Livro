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
        DB::statement("
            CREATE VIEW books_by_author_report AS
            SELECT 
                a.id as author_id,
                a.name as author_name,
                b.id as book_id,
                b.title as book_title,
                b.publication_year,
                b.isbn,
                b.price,
                b.valor,
                GROUP_CONCAT(DISTINCT s.description, ', ') as subjects
            FROM authors a
            INNER JOIN book_author ba ON a.id = ba.author_id
            INNER JOIN books b ON ba.book_id = b.id
            LEFT JOIN book_subject bs ON b.id = bs.book_id
            LEFT JOIN subjects s ON bs.subject_id = s.id
            WHERE b.deleted_at IS NULL
            GROUP BY a.id, a.name, b.id, b.title, b.publication_year, b.isbn, b.price, b.valor
            ORDER BY a.name, b.title
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS books_by_author_report");
    }
};
