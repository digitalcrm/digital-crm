<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\PasswordResetNotification;
use App\Traits\DefaultProfile;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasApiTokens, Notifiable, DefaultProfile;
    //use HasRoles;

    // use HasApitokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //Table Name
    protected $table = 'users';
    //Primary key
    public $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'picture',
        'active',
        'cr_id',
        'mobile',
        'jobtitle',
        'daily_reports',
        'newsletter',
        'user_type',
        'user',
        'verified',
        'country',
        'state',
        'city',
        'zip',
        'quota'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['profile_img'];

    public function getProfileImgAttribute()
    {
        return $this->picture ? asset($this->picture) : $this->defaultProfilePhotoUrl($this->name);
    }

    public function currency()
    {
        return $this->belongsTo('App\currency', 'cr_id');
    }

    public function tbl_leads()
    {
        return $this->hasMany('App\Tbl_leads', 'uid');
    }

    public function tbl_accounts()
    {
        return $this->hasMany('App\Tbl_Accounts', 'uid');
    }

    public function tbl_contacts()
    {
        return $this->hasMany('App\Tbl_contacts', 'uid');
    }

    public function tbl_deals()
    {
        return $this->hasMany('App\Tbl_deals', 'uid');
    }

    public function tbl_newsletter()
    {
        return $this->hasMany('App\Tbl_newsletter', 'uid');
    }

    public function tbl_territory()
    {
        return $this->hasMany('App\Tbl_territory', 'uid');
    }

    public function tbl_territory_users()
    {
        return $this->hasMany('App\Tbl_territory_users', 'uid', 'suid');
    }

    public function tbl_documents()
    {
        return $this->hasMany('App\Tbl_documents', 'uid');
    }

    public function tbl_events()
    {
        return $this->hasMany('App\Tbl_events', 'uid');
    }

    public function tbl_products()
    {
        return $this->hasMany('App\Tbl_products', 'uid');
    }

    public function tbl_tax()
    {
        return $this->hasMany('App\Tbl_tax', 'uid');
    }

    public function tbl_invoice()
    {
        return $this->hasMany('App\Tbl_invoice', 'uid');
    }

    public function tbl_forms()
    {
        return $this->hasMany('App\Tbl_forms', 'uid');
    }

    public function tbl_formleads()
    {
        return $this->hasMany('App\Tbl_formleads', 'uid');
    }

    public function tbl_forecast()
    {
        return $this->hasMany('App\Tbl_forecast', 'uid');
    }

    public function tbl_features()
    {
        return $this->hasOne('App\Tbl_features', 'uid');
    }

    public function tbl_mails()
    {
        return $this->hasMany('App\Tbl_mails', 'uid');
    }

    public function tbl_verifyuser()
    {
        return $this->hasOne('App\tbl_verifyuser', 'uid');
    }

    public function tbl_smtpsettings()
    {
        return $this->hasOne('App\tbl_smtpsettings', 'uid');
    }

    public function tbl_countries()
    {
        return $this->belongsTo('App\Tbl_countries', 'country');
    }

    public function tbl_states()
    {
        return $this->belongsTo('App\Tbl_states', 'state');
    }

    public function tbl_campaigns()
    {
        return $this->belongsTo('App\Tbl_campaigns', 'uid');
    }

    public function tbl_camp_mail_templates()
    {
        return $this->hasMany('App\Tbl_camp_mail_templates', 'uid');
    }

    public function tbl_campaign_mails()
    {
        return $this->hasMany('App\Tbl_campaign_mails', 'uid');
    }

    public function tbl_group_users()
    {
        return $this->hasMany('App\Tbl_group_users', 'uid');
    }

    public function tbl_project_members()
    {
        return $this->hasMany('App\Tbl_project_members', 'uid');
    }

    public function tbl_company()
    {
        return $this->hasMany('App\Tbl_company', 'uid');
    }

    public function tbl_user_types()
    {
        return $this->belongsTo('App\Tbl_user_types', 'user_type');
    }

    public function role()
    {
        return $this->belongsTo('Role', 'role_id', 'id');
    }

    // public function can($perm = null)
    // {
    //     if(is_null($perm)) return false;
    //     $perms = $this->role->permissions->fetch('name');
    //     return in_array($perm, $perms->toArray());
    // }

    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPasswordNotification($token));
        $this->notify(new PasswordResetNotification($token));
    }

    public function currentuser()
    { #This is temporary for current user you can use scopeQuery in each Model

        return $this->hasMany('App\Todo', 'user_id');
    }

    public function todos()
    {
        return $this->morphMany('App\Todo', 'todoable');
    }

    public function ticket()
    {
        return $this->hasMany('App\Ticket', 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function bookingEvents()
    {
        return $this->hasMany('App\BookingEvent', 'user_id');
    }

    public function bookingCustomers()
    {
        return $this->hasMany('App\BookingCustomer', 'user_id');
    }

    public function company()
    {
        return $this->hasMany('App\Company', 'user_id');
    }

    public function rfqs()
    {
        return $this->hasMany(Rfq::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            Tbl_features::create([
                'uid' => $model->id
            ]);
        });
    }
}
