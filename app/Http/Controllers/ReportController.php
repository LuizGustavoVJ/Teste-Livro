<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Exibe o relatório de livros por autor.
     *
     * @return \Illuminate\View\View
     */
    public function booksByAuthor()
    {
        $reportData = DB::table('books_by_author_report')->get();
        
        // Agrupa os dados por autor
        $authors = $reportData->groupBy('author_name')->map(function ($books, $authorName) {
            return (object) [
                'name' => $authorName,
                'books' => $books->map(function ($book) {
                    return (object) [
                        'title' => $book->book_title,
                        'publication_year' => $book->publication_year,
                        'isbn' => $book->isbn,
                        'price' => $book->price,
                        'valor' => $book->valor,
                        'subjects' => $book->subjects
                    ];
                })
            ];
        });

        return view('reports.books_by_author', compact('authors'));
    }

    /**
     * Gera um PDF do relatório de livros por autor.
     *
     * @return \Illuminate\Http\Response
     */
    public function booksByAuthorPdf()
    {
        $reportData = DB::table('books_by_author_report')->get();
        
        // Agrupa os dados por autor
        $authors = $reportData->groupBy('author_name')->map(function ($books, $authorName) {
            return (object) [
                'name' => $authorName,
                'books' => $books->map(function ($book) {
                    return (object) [
                        'title' => $book->book_title,
                        'publication_year' => $book->publication_year,
                        'isbn' => $book->isbn,
                        'price' => $book->price,
                        'valor' => $book->valor,
                        'subjects' => $book->subjects
                    ];
                })
            ];
        });

        $pdf = Pdf::loadView('reports.books_by_author_pdf', compact('authors'))->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-livros-por-autor.pdf');
    }

    /**
     * Obtém os dados do relatório de livros por autor da view do banco de dados via Report Builder.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function booksByAuthorFromView()
    {
        return redirect()->away('http://NOTEDELLLG:80/ReportServer?/Relatorio_Autores');
    }
}
