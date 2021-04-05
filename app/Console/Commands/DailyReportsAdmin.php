<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
//  Controllers
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\DailyReportsController;

class DailyReportsAdmin extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyreports:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily reports to administrator';

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

        // $calObj = new CalendarController();
        // $events = $calObj->getTodayEvents();
        //$toevents = $calObj->getTomorrowEvents();

        $drObj = new DailyReportsController();
        $message = $drObj->getDailyReport();

        $title = 'Digital CRM';
        $content = $message['title'] . '<br>';
        $content .= $message['message'];
        $fromMail = 'info@bigindia.com';
        $toMail = 'admin@bigindia.com';
        $subject = $message['subject'];
        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $content], function ($message) use ($fromMail, $toMail, $subject) {
            $message->subject($subject);
            $message->from($fromMail, 'Administrator');   //'sandeepindana@yahoo.com'
            $message->to($toMail);   //'isandeep.1609@gmail.com'
        });
    }
}
