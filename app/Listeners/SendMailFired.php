<?php

namespace App\Listeners;

use App\Events\SendMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
//---------------Models--------------------------
use App\User;

//use App\Tbl_smtpsettings;
//---------------Controllers---------------------
class SendMailFired {

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
     * @param  SendMail  $event
     * @return void
     */
    public function handle(SendMail $event) {

//        dd($event);
//        exit(0);
        $user = User::find($event->user_id)->toArray();
//        echo json_encode($user);
//        exit();
        $title = 'Digital CRM';
        $content = 'Testing Events and Listeners';
        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $content], function($message) use ($user) {
            $message->to($user['email']);
            $message->subject('Event Testing');
        });
    }

}
