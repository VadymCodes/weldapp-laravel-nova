<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserByJob extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'With Jobs';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        switch ($value) {
            case 0:
                return $query->where('role', 'driver')->whereHas('jobs', function($jobQuery) {
                    $jobQuery->doesnthave('fulfilledJob')->orWhereHas('fulfilledJob', function($subQuery) {
                        return $subQuery->where('fulfilled', false);
                    });
                });
                break;

            case 1:
                return $query->where('role', 'driver')->whereHas('jobs', function($jobQuery) {
                    $jobQuery->whereHas('fulfilledJob', function($subQuery) {
                        return $subQuery->where('fulfilled', true);
                    });
                });
                break;

            case 2:
                return $query->where('role', 'mechanic')->whereHas('bids', function($jobQuery) {
                    $jobQuery->whereHas('fulfilledJob', function($subQuery) {
                        return $subQuery->where('fulfilled', false);
                    });
                });
                break;

            case 3:
                return $query->where('role', 'mechanic')->whereHas('bids', function($jobQuery) {
                    $jobQuery->whereHas('fulfilledJob', function($subQuery) {
                        return $subQuery->where('fulfilled', true);
                    });
                });
                break;
            
            default:
                return $query;
                break;
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'drivers with active jobs' => 0,
            'drivers with fulfilled jobs' => 1,
            'mechanics with active jobs' => 2,
            'mechanics with fulfilled jobs' => 3
        ];
    }
}
