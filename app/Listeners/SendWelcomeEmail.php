<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoasVindasEmail;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Cria o ouvinte do evento.
     */
    public function __construct()
    {
        //
    }

    /**
     * Lida com o evento.
     */
    public function handle(UserRegistered $evento): void
    {
        Mail::to($evento->usuario->email)->send(new BoasVindasEmail($evento->usuario, $evento->reportData));
    }
}
