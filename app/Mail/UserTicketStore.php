<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\Types\Null_;

class UserTicketStore extends Mailable
{
    use Queueable, SerializesModels;

    public $ticketdata;
    public $ticketmessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketdata, $ticketmessage = Null)
    {
        $this->ticketdata = $ticketdata;
        $this->ticketmessage = $ticketmessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch (request('action')) {
            case 'close':
            return $this->from(auth()->user()->email)
                ->subject($this->ticketdata->name)
                ->markdown('emails.tickets.ticketcomment');
            break;

            case 'send':
            return $this->from(auth()->user()->email)
                ->subject($this->ticketdata->name)
                ->markdown('emails.tickets.ticketcomment');
            break;

            default:
            if($this->ticketdata->user_id != Null) {
                return $this->from(auth()->user()->email)
                ->subject($this->ticketdata->name)
                ->markdown('emails.tickets.userstoreticket');
            } else {
                return $this->subject($this->ticketdata->name)
                ->markdown('emails.tickets.userstoreticket');
            }
            break;
        }
    }
}
