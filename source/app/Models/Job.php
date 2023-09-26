<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Job extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = [];

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
            'description' => $this->description,
            'vehicle' => $this->vehicle,
            'category' => $this->category->name,
            'driver' => [
                'name' => $this->user->name
            ],
            '_geoloc' => [
                'lat' => $this->user->latitude,
                'lng' => $this->user->longitude
            ]
        ];
    }

    /**
     * Get the Category associated to a particular Job.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the User associated to a particular Job.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Bids that have been made on a particular Job.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get the Jobs' Fulfilled Job.
     */
    public function fulfilledJob()
    {
        return $this->hasOne(FulfilledJob::class);
    }
}
