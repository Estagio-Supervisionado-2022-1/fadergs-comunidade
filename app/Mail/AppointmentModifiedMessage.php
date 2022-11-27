<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentModifiedMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($appointment, $user, $fullAddress, $service)
    {
        $this->appointment = $appointment;
        $this->user = $user;
        $this->fullAddress = $fullAddress;
        $this->service = $service;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.AppointmentModifiedMessage')
                    ->subject('Fadergs Comunidade - Novas informações sobre o seu agendamento')
                    ->from(env('MAIL_USERNAME'), 'Fadergs Comunidade')
                    ->with([
                        'appointment' => $this->appointment,
                        'user' => $this->user,
                        'fullAddress' => $this->fullAddress,
                        'service' => $this->service
                    ]);
    }
}
