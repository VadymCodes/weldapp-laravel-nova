<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, Searchable, SoftDeletes;

    /**
     * Admin role type.
     *
     * @var string
     */
    public static $ROLE_ADMIN = 'admin';

    /**
     * Driver role type.
     *
     * @var string
     */
    public static $ROLE_DRIVER = 'driver';

    /**
     * Mechanic role type.
     *
     * @var string
     */
    public static $ROLE_MECHANIC = 'mechanic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
        'address',
        'latitude',
        'longitude'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = [
    //     'avg_job_price',
    // ];

    public static function boot()
    {
        parent::boot();

        // after a user is saved update the search index for the services
        static::saved(function($user) {
            $user->services->searchable();
        });
    }

    /**
     * Checks if the given model can be searchable.
     *
     * @return boolean
     */
    public function shouldBeSearchable()
    {
        return $this->role !== self::$ROLE_ADMIN;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'role' => $this->role,
            'address' => $this->address,
            '_geoloc' => [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ]
        ];
    }

    /**
     * Get the Services provided by the User.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the Jobs associated to a particular User.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the Bids made by a particular User.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get the documents made by a particular User.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Automatically hash the User's provided password.
     *
     * @param string $password
     * @return void
     */
    // public function setPasswordAttribute(string $password) : void
    // {
    //     if (trim($password) == '') {
    //         return;
    //     }

    //     $this->attributes['password'] = Hash::make($password);
    // }

    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }

    /**
     * Get average job price.
     */
    // public function getAvgJobPriceAttribute()
    // {
    //     return $this->bids()
    //         ->select(DB::raw('avg(cost) average'))
    //         ->first()->average;
    // }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified' => true,
        ])->save();
    }
}
