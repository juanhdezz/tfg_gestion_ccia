<?php

namespace App\Mail;

use App\Models\ReservaSala;
use App\Models\Usuario;
use App\Models\Libro;
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
     * Información del usuario y entidad (reserva o libro)
     */
    public $usuario;
    public $entidad;
    public $estado;
    public $subject;
    public $tipoEntidad;

    /**
     * Create a new message instance.
     *     * @param Usuario $usuario El usuario que realizó la solicitud
     * @param mixed $entidad La entidad relacionada (ReservaSala o Libro)
     * @param string $estado Estado de la solicitud ('Validada', 'Rechazada', 'Aceptado', 'Denegado', 'Biblioteca', etc.)
     */
    public function __construct(Usuario $usuario, $entidad, string $estado)
    {
        $this->usuario = $usuario;
        $this->entidad = $entidad;
        $this->estado = $estado;
        
        // Determinar el tipo de entidad
        $this->tipoEntidad = $entidad instanceof ReservaSala ? 'reserva' : 'libro';
          // Configurar el asunto según el tipo de entidad y estado
        if ($this->tipoEntidad === 'reserva') {
            $this->subject = $estado === 'Validada' 
                ? 'Su reserva de sala ha sido aprobada' 
                : 'Su reserva de sala ha sido rechazada';
        } else {
            if ($estado === 'Aceptado') {
                $this->subject = 'Su solicitud de libro ha sido aprobada';
            } elseif ($estado === 'Biblioteca') {
                $this->subject = 'Su libro está disponible en biblioteca';
            } else {
                $this->subject = 'Su solicitud de libro ha sido denegada';
            }
        }
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
        // Elegir la vista según el tipo de entidad
        $view = $this->tipoEntidad === 'reserva' ? 'reserva_salas.email' : 'libros.email';
        
        return new Content(
            view: $view,
            with: [
                'usuario' => $this->usuario,
                $this->tipoEntidad => $this->entidad, // Pasa la entidad con el nombre adecuado (reserva o libro)
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