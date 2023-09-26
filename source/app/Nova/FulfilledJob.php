<?php

namespace App\Nova;

use App\Nova\Actions\RemoveDriverReview;
use App\Nova\Actions\RemoveMechanicReview;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nikaia\Rating\Rating;

class FulfilledJob extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\FulfilledJob::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $displayInNavigation = false;

    /**
     * The value whether the resource should be imported or not.
     *
     * @var boolean
     */
    public static $canImportResource = false;

    /**
     * Get the custom resouce name.
     *
     * @return string
     */
    public static function label() {
        return 'Reviews';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),
            // Boolean::make('Fulfilled')
            //     ->sortable()
            //     ->rules('required'),
            Rating::make(__('Mechanic Rating'), 'driver_rating')
                ->min(0)->max(5)->increment(1)
                ->withStyles([
                    'star-size' => 20,
                    'active-color' => '#ffd055',
                    'rounded-corners' => true,
                ]),
            Text::make(__('Mechanic Comment'), 'driver_comment'),
            Rating::make(__('Driver Rating'), 'mechanic_rating')
                ->min(0)->max(5)->increment(1)
                ->withStyles([
                    'star-size' => 20,
                    'active-color' => '#ffd055',
                    'rounded-corners' => true,
                ]),
            Text::make(__('Driver Comment'), 'mechanic_comment'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new RemoveDriverReview,
            new RemoveMechanicReview,
        ];
    }

    /**
     * Get name displayed.
     *
     * @return string
     */
    public function reviews()
    {
        return 'Reviews';
    }
}
