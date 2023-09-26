<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FulfilledJob extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = [];

    /**
     * Get the Fulfilled Jobs' Job.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the Fulfilled Jobs' Bid.
     */
    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }
}
