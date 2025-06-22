<?php

namespace App\Console\Commands;

use App\Mail\BookReportEmail;
use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendBookReport extends Command
{
    /**
     * O nome e a assinatura do comando do console.
     *
     * @var string
     */
    protected $signature = "app:enviar-relatorio-livros {email}";

    /**
     * A descrição do comando do console.
     *
     * @var string
     */
    protected $description = "Envia um relatório de livros por e-mail.";

    /**
     * Executa o comando do console.
     */
    public function handle()
    {
        $emailDestinatario = $this->argument("email");

        // Lógica para coletar dados do relatório
        $totalLivros = Book::count();
        $livrosPorAutor = DB::table("books")
            ->join("book_author", "books.id", "=", "book_author.book_id")
            ->join("authors", "authors.id", "=", "book_author.author_id")
            ->select("authors.name as autor", DB::raw("count(books.id) as total_livros"))
            ->groupBy("authors.name")
            ->orderBy("total_livros", "desc")
            ->get();

        $dadosRelatorio = [
            "totalLivros" => $totalLivros,
            "livrosPorAutor" => $livrosPorAutor,
        ];

        // Envia o e-mail
        Mail::to($emailDestinatario)->queue(new BookReportEmail($dadosRelatorio));

        $this->info("Relatório de livros enviado com sucesso para " . $emailDestinatario);
    }
}
