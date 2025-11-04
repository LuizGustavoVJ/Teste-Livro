<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class BoasVindasEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public $usuario;
    public $reportData;

    /**
     * Cria uma nova instância da mensagem.
     */
    public function __construct(User $usuario, array $reportData)
    {
        $this->usuario = $usuario;
        $this->reportData = $reportData;
    }

    /**
     * Obtém o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Bem-vindo ao Sistema de Livros, " . $this->usuario->name . "!",
        );
    }

    /**
     * Obtém a definição do conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            markdown: "emails.boas-vindas",
            with: [
                "nomeUsuario" => $this->usuario->name,
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
