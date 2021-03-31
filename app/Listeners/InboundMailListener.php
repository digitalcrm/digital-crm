<?php

namespace App\Listeners;

use App\Events\InboundMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
//---------------------Models--------------------
//use App\User;
//---------------------Controller--------------------
use App\Http\Controllers\MailController;

class InboundMailListener {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InboundMail  $event
     * @return void
     */
    public function handle(InboundMail $event) {
        //
        $uid = $event->user_id;
        $mail = new MailController();
        $mail->getInboundMails($uid);
    }

}
