<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvisoProcesoMailable extends Mailable {
	
	use Queueable, SerializesModels;
	
	public string $titulo;
	public string $mensaje;
	
	public function __construct($titulo, $mensaje) {
		$this->titulo = $titulo;
		$this->mensaje = $mensaje;
	}
	
	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope {
		return new Envelope(
            subject: "🔔 {$this->titulo}", 
        );
	}
	
	/**
	 * Get the message content definition.
	 */
	  public function content(): Content
    {
        return new Content(
            markdown: 'emails.proceso.aviso',
            with: [
                'titulo'  => $this->titulo,
                'mensaje' => $this->mensaje,
            ],
        );
    }
	
	/**
	 * Get the attachments for the message.
	 *
	 * @return array<int, \Illuminate\Mail\Mailables\Attachment>
	 */
	public function attachments(): array {
		return [];
	}
}
