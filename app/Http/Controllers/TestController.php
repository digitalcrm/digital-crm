<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;
use PhpImap;

class TestController extends Controller {

    public function index() {
        $this->getMails();
    }

    public function getMails() {
        $host = 'mail.digitalcrm.com';
        $email = 'testone@digitalcrm.com';
        $password = 'Testone@#*';
        $port = '993';

        $mailbox = new PhpImap\Mailbox('{' . $host . ':' . $port . '/imap/ssl/novalidate-cert}INBOX', $email, $password, __DIR__);

// Read all messaged into an array:
        $mailsIds = $mailbox->searchMailbox('ALL');

        $latestmailsIds = array_reverse($mailsIds);
        $totalmailsIds = count($mailsIds);
        $mailArray = array();

        if ($totalmailsIds > 0) {
            for ($i = 0; $i < 10; $i++) {
                $mail = $mailbox->getMail($latestmailsIds[$i]);

                //------------------Mail Details--------------------------
                echo "Id : ";
                echo $mail->id . '<br>';
                echo "From Address/Name : ";
                echo $mail->fromAddress . '/' . $mail->fromName . '<br>';   // 
                echo "To Address : ";
                echo $mail->toString . '<br>';
                echo "Subject : ";
                echo $mail->subject . '<br>';
                echo "Message(textPlain) : ";
                echo ($mail->textPlain != null) ? $mail->textPlain : '' . '<br>';
                echo "Message(textHtml) : ";
                echo ($mail->textHtml != null) ? $mail->textHtml : '' . '<br>';

//                $attachments = $mail->getAttachments();
//
//                $filesystem = new Filesystem;
//                foreach ($attachments as $attachment) {
//
//                    $filePath = $attachment->filePath;
//                    $filePaths = explode('/', $filePath);
//                    $n = count($filePaths) - 1;
//
//                    $file = $filePaths[$n];
//                    if (!unlink($file)) {
////                        echo ("Error deleting $file");
//                    } else {
////                        echo ("Deleted $file");
//                    }
//
//                    $path = "downloads\mails\attachments\\";
//                    $filesystem->put(base_path($path) . $attachment->name, $attachment->filePath);
//                }
            }
        }
//        print_r($mailArray);
//        if (!$mailsIds) {
//            die('Mailbox is empty');
//        }
        //// Get the first message and save its attachment(s) to disk:
//        $mail = $mailbox->getMail($latestmailsIds[2]);
//
//        echo json_encode($mail);
//        $attachments = $mail->getAttachments();
////
//        $filesystem = new Filesystem;
//        foreach ($attachments as $attachment) {
//
//            $filePath = $attachment->filePath;
//            $filePaths = explode('/', $filePath);
//            $n = count($filePaths) - 1;
//
//            $file = $filePaths[$n];
//            if (!unlink($file)) {
//                echo ("Error deleting $file");
//            } else {
//                echo ("Deleted $file");
//            }
//
//            $path = "downloads\mails\attachments\\";
//            $filesystem->put(base_path($path) . $attachment->name, $attachment->filePath);
//        }
    }

}

/*
//------------------Mail Details--------------------------
        echo "Id : ";
        echo $mail->id . '<br>';
        echo "From Address/Name : ";
        echo $mail->fromAddress . '/' . $mail->fromName . '<br>';   // 
        echo "To Address : ";
        echo $mail->toString . '<br>';
        echo "Subject : ";
        echo $mail->subject . '<br>';
        echo "Message(textPlain) : ";
        echo ($mail->textPlain != null) ? $mail->textPlain : '' . '<br>';
        echo "Message(textHtml) : ";
        echo ($mail->textHtml != null) ? $mail->textHtml : '' . '<br>';
        */