<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookReportEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $dadosRelatorio;

    /**
     * Cria uma nova instância da mensagem.
     */
    public function __construct(array $dadosRelatorio)
    {
        $this->dadosRelatorio = $dadosRelatorio;
    }

    /**
     * Obtém o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Relatório de Livros - " . now()->format("d/m/Y H:i"),
        );
    }

    /**
     * Obtém a definição do conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            markdown: "emails.book-report",
            with: [
                "totalLivros" => $this->dadosRelatorio["totalLivros"],
                "livrosPorAutor" => $this->dadosRelatorio["livrosPorAutor"],
            ],
        );
    }

    /**
     * Obtém os anexos para a mensagem.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}


