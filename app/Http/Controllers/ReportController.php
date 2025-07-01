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
        $authors = Author::with('books.subjects')->get();

        return view('reports.books_by_author', compact('authors'));
    }

    /**
     * Gera um PDF do relatório de livros por autor.
     *
     * @return \Illuminate\View\View
     */
    public function booksByAuthorPdf()
    {
        $authors = Author::with('books.subjects')->get();

        $pdf = Pdf::loadView('reports.books_by_author_pdf', compact('authors'))->setPaper('a4', 'portrait');

        return $pdf->download('relatorio-livros-por-autor.pdf');
    }

    /**
     * Obtém os dados do relatório de livros por autor da view do banco de dados via Report Builder.
     *
     * @return \Illuminate\View\View
     */
    public function booksByAuthorFromView()
    {
        return redirect()->away('http://NOTEDELLLG:80/ReportServer?/Relatorio_Autores');
    }
}
