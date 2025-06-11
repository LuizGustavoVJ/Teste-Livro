<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use PDF;

class ReportController extends Controller
{
    /**
     * Exibe o relatório de livros por autor.
     *
     * @return \Illuminate\Http\Response
     */
    public function booksByAuthor()
    {
        $authors = Author::with('books.subjects')->get();

        return view('reports.books_by_author', compact('authors'));
    }

    /**
     * Gera um PDF do relatório de livros por autor.
     *
     * @return \Illuminate\Http\Response
     */
    public function booksByAuthorPdf()
    {
        $authors = Author::with('books.subjects')->get();

        // Aqui seria utilizado um pacote de geração de PDF como o DomPDF
        // Como exemplo, retorna a view que seria convertida para PDF
        return view('reports.books_by_author_pdf', compact('authors'));
    }

    /**
     * Obtém os dados do relatório de livros por autor da view do banco de dados.
     *
     * @return \Illuminate\Http\Response
     */
    public function booksByAuthorFromView()
    {
        $data = DB::table('books_by_author_view')
                  ->orderBy('author_name')
                  ->orderBy('book_title')
                  ->get();

        return view('reports.books_by_author_from_view', compact('data'));
    }
}

