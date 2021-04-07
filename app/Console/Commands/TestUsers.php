<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//  Import Mail
use Illuminate\Support\Facades\Mail;

class TestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is test mail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $title = "Digital CRM";
        $message = "Testing cron job with mail at " . date('d-m-Y h:i:s a');
        $subject = "Testing cron job at " . date('d-m-Y h:i:s a');
        $attachments = '';
        $from_email = config('custom_appdetail.mail_info');
        $to_email = config('mail.from.address');
        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use($from_email, $to_email, $subject, $attachments) {
                    $message->subject($subject);
                    $message->from($from_email);   //'sandeepindana@yahoo.com'

                    if ($attachments != '') {
                        $message->attach($attachments);
                    }

                    $message->to($to_email);   //'isandeep.1609@gmail.com'
                });


    }
}
