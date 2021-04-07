<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Admin;
use App\Tbl_deals;
use App\Tbl_forms;
//  Models
use App\Tbl_leads;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_products;
use App\Tbl_formleads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
//  Controllers
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\MailController;

class DailyReportsController extends Controller
{

    public function index()
    {

        $title = "Laravel Scheduler - CronJob Testing...";
        $subject = "CronJob Testing..." . date('d-m-Y h:i:s');
        $message = "CronJob Testing..." . date('d-m-Y h:i:s');
        $from = config('custom_appdetail.mail_info');
        $to = config('mail.from.address');

        Mail::send(['html' => 'emails.default'], ['title' => $title, 'content' => $message], function ($message) use ($from, $to, $subject) {
            $message->subject($subject);
            $message->from($from);   //'sandeepindana@yahoo.com'
            $message->to($to);   //'isandeep.1609@gmail.com'
        });

        if (count(Mail::failures()) > 0) {
            return 'error';
        } else {
            return 'success';
        }
    }

    public function getDailyReport()
    {
        $date = date('Y-m-d', strtotime("-1 days"));
        // $date = date('Y-m-d');
        $totalUsers = User::where(DB::raw('DATE(created_at)'), $date)->count();
        $totalAccounts = Tbl_Accounts::where(DB::raw('DATE(created_at)'), $date)->count();
        $totalContacts = Tbl_contacts::where(DB::raw('DATE(created_at)'), $date)->count();
        $totalForms = Tbl_forms::where(DB::raw('DATE(created_at)'), $date)->count();
        $totalFormleads = Tbl_formleads::where(DB::raw('DATE(created_at)'), $date)->count();
        $totalDeals = Tbl_deals::where(DB::raw('DATE(created_at)'), $date)->count();
        $totalProducts = Tbl_products::where('user_type', 2)->where(DB::raw('DATE(created_at)'), $date)->count();
        $totalLeads = Tbl_leads::where('leadtype', 1)->where(DB::raw('DATE(created_at)'), $date)->count();
        $totalProductLeads = Tbl_leads::where('leadtype', 2)->where(DB::raw('DATE(created_at)'), $date)->count();

        $message = '<table>';
        $message .= '<tr><td>Users</td><td>' . $totalUsers . '</td></tr>';
        $message .= '<tr><td>Accounts</td><td>' . $totalAccounts . '</td></tr>';
        $message .= '<tr><td>Contacts</td><td>' . $totalContacts . '</td></tr>';
        $message .= '<tr><td>Deals</td><td>' . $totalDeals . '</td></tr>';
        $message .= '<tr><td>Forms</td><td>' . $totalForms . '</td></tr>';
        $message .= '<tr><td>Form Leads</td><td>' . $totalFormleads . '</td></tr>';
        $message .= '<tr><td>Leads</td><td>' . $totalLeads . '</td></tr>';
        $message .= '<tr><td>Products</td><td>' . $totalProducts . '</td></tr>';
        $message .= '<tr><td>Product Leads</td><td>' . $totalProductLeads . '</td></tr>';
        $message .= '</table>';

        // $admin = Admin::where('user_type', 2)->first();
        // $from = $admin->email;
        // $to = $admin->email;

        $title = 'Daily reports to administrator on ' . date('d-m-Y', strtotime($date));
        $subject = 'Daily reports to administrator on ' . date('d-m-Y', strtotime($date));

        $data['title'] = $title;
        $data['subject'] = $subject;
        $data['message'] = $message;

        return $data;

        // $mailObj = new MailController();
        // $res = $mailObj->sendMail($from, $to, $message, $subject, $title);
        // if ($res) {
        //     return 'success';
        // } else {
        //     return 'failure';
        // }
    }
}
