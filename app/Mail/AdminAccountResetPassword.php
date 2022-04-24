<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAccountResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $loginData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($loginData){
        $this->loginData = $loginData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->view('mails.AdminAccountUpdate')
                    ->subject('Novos Dados de Acesso')
                    ->from('fadergscomunidade@gmail.com', 'Fadergs Comunidade')
                    ->with('loginData', $this->loginData);
        
    }
}
