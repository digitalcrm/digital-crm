<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationMail extends Mailable {

    use Queueable,
        SerializesModels;

    public $content, $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $title) {
        //
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('emails.default');
    }

}