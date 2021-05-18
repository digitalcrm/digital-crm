<?php

namespace App\Providers;

use App\Company;
use App\Tbl_deals;
use App\Tbl_forms;
use App\Tbl_leads;
use App\Tbl_mails;
use App\Tbl_orders;
use App\Tbl_invoice;
use App\Tbl_Accounts;
use App\Tbl_contacts;
use App\Tbl_products;
use App\Tbl_projects;
use App\Tbl_campaigns;
use App\Tbl_documents;
use App\Policies\DealPolicy;
use App\Policies\FormPolicy;
use App\Policies\LeadPolicy;
use App\Policies\MailPolicy;
use App\Policies\OrderPolicy;
use Laravel\Passport\Passport;
use App\Policies\AccountPolicy;
use App\Policies\ContactPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\CampaignPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\ServicePolicy;
use App\Service;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Tbl_Accounts::class => AccountPolicy::class,
        Tbl_contacts::class => ContactPolicy::class,
        Tbl_leads::class => LeadPolicy::class,
        Tbl_products::class => ProductPolicy::class,
        Tbl_deals::class => DealPolicy::class,
        Tbl_orders::class => OrderPolicy::class,
        Tbl_invoice::class => InvoicePolicy::class,
        Tbl_forms::class => FormPolicy::class,
        Tbl_documents::class => DocumentPolicy::class,
        Tbl_projects::class => ProjectPolicy::class,
        Tbl_mails::class => MailPolicy::class,
        Tbl_campaigns::class => CampaignPolicy::class,
        Company::class => CompanyPolicy::class,
        Service::class => ServicePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //------------------------------
        Gate::define('isAdmin', function ($user) {
            return $user->user_type == 2;
        });

        Gate::define('isSubAdmin', function ($user) {
            return $user->user_type == 3;
        });

        //------------------------------
        Gate::define('isUser', function ($user) {
            return $user->user_type == 1;
        });

        Gate::define('isSubUser', function ($user) {
            return $user->user_type == 0;
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });
        //------------------------------------------------------
        Passport::routes();
    }
}
