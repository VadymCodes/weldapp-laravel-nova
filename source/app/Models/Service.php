<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Service extends Model
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
            'cost' => $this->cost,
            'description' => $this->description,
            'mechanic' => [
                'name' => $this->user->name,
                'address' => $this->user->address,
                'phone' => $this->user->phone
            ],
            '_geoloc' => [
                'lat' => $this->user->latitude,
                'lng' => $this->user->longitude
            ]
        ];
    }

    /**
     * Get the User that owns the Service.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
