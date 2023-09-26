<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Service extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Service::class;

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
        'id', 'name'
    ];

    /**
     * The value whether the resource should be imported or not.
     *
     * @var boolean
     */
    public static $canImportResource = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            Number::make('Cost')
                ->sortable()
                ->min(1)->step(0.01)
                ->rules('required')
                ->resolveUsing(function ($cost) {
                    return '£' . $cost;
                }),
            Text::make('Description')
                ->sortable()
                ->rules('required')
                ->onlyOnIndex()
                ->displayUsing(function ($name) {
                    return Str::limit($name, 20);
                }),
            Text::make('Description')
                ->hideFromIndex(),
            BelongsTo::make('User'),
            Text::make('Address', function() {
                    return $this->user->address;
                })
                ->onlyOnIndex()
                ->displayUsing(function ($name) {
                    return Str::limit($name, 15);
                }),
            Text::make('Address', function() {
                    return $this->user->address;
                })
                ->hideFromIndex()
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
        return [];
    }

    public static function relatableUsers(NovaRequest $request, $query)
    {
        return $query->where('role', 'mechanic');
    }
}
