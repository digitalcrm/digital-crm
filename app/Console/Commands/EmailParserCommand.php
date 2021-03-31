<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MimeMailParser\Parser;
use Illuminate\Support\Facades\DB;
use Mail;

// Models

use App\Tbl_emails;


class EmailParserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email_parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses an incoming email.';

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
     * @return int
     */
    public function handle()
    {
        // return 0;
        $fd = fopen("php://stdin", "r");
        $rawEmail = "";
        while (!feof($fd)) {
            $rawEmail .= fread($fd, 1024);
        }
        fclose($fd);

        $parser = new Parser();
        $parser->setText($rawEmail);

        $to = $parser->getHeader('to');
        $from = $parser->getHeader('from');
        $subject = $parser->getHeader('subject');
        $text = $parser->getMessageBody('text');
        
        $msg = array(
            'to_address'=>$to,
            'from_address'=>$from,
            'subject'=>$subject,
            'message' =>$text
            );
        
        
        $query = DB::table('tbl_mails_sample')->insert($msg);
        
        // $email = new Tbl_emails;
        // $email->to = $parser->getHeader('to');
        // $email->from = $parser->getHeader('from');
        // $email->subject = $parser->getHeader('subject');
        // $email->text = $parser->getMessageBody('text');
        // $email->save();
    }
}
