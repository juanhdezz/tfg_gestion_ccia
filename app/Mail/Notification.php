<?php
// filepath: c:\xampp\htdocs\laravel\tfg_gestion_ccia\app\Mail\Notification.php

namespace App\Mail;

use App\Models\ReservaSala;
use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Información de la reserva y el usuario
     */
    public $usuario;
    public $reserva;
    public $estado;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param Usuario $usuario El usuario que realizó la reserva
     * @param ReservaSala $reserva Los datos de la reserva
     * @param string $estado Estado de la reserva ('Validada' o 'Rechazada')
     */
    public function __construct(Usuario $usuario, ReservaSala $reserva, string $estado)
    {
        $this->usuario = $usuario;
        $this->reserva = $reserva;
        $this->estado = $estado;
        
        // Configurar el asunto según el estado
        $this->subject = $estado === 'Validada' 
            ? 'Su reserva de sala ha sido aprobada' 
            : 'Su reserva de sala ha sido rechazada';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'reserva_salas.email',
            with: [
                'usuario' => $this->usuario,
                'reserva' => $this->reserva,
                'estado' => $this->estado
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}