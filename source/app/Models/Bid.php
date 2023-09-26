<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = [];

    /**
     * Get the User associated to a particular Bid.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Job associated to a particular Bid.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the Bids' Fulfilled Job.
     */
    public function fulfilledJob()
    {
        return $this->hasOne(FulfilledJob::class);
    }
}
