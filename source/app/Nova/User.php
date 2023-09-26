<?php

namespace App\Nova;

use App\Nova\Actions\MarkAccredited;
use App\Nova\Actions\MarkVerfied;
use App\Nova\Actions\SuspendUser;
use App\Nova\Actions\UnsuspendUser;
use App\Nova\Filters\DateRange;
use App\Nova\Filters\MechanicWithService;
use App\Nova\Filters\UserAccredited;
use App\Nova\Filters\UserByJob;
use App\Nova\Filters\UserType;
use App\Nova\Filters\UserVerified;
use App\Nova\Filters\UserSuspended;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Naif\AddressAutocomplete\AddressAutocomplete;
use EmilianoTisato\GoogleAutocomplete\AddressMetadata;
use EmilianoTisato\GoogleAutocomplete\GoogleAutocomplete;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\PasswordConfirmation;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email', 'phone'
    ];

    /**
     * The value whether the resource should be imported or not.
     *
     * @var boolean
     */
    public static $canImportResource = true;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Select::make('Role')
                ->sortable()
                ->rules('required')
                ->options([
                    'admin' => 'Administrator',
                    'driver' => 'Driver',
                    'mechanic' => 'Mechanic',
                ]),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            // PasswordConfirmation::make('Password Confirmation')
                // ->onlyOnForms(),

            Text::make('Phone')->sortable(),
            GoogleAutocomplete::make('Address')
                ->withValues(['latitude', 'longitude'])
                ->onlyOnIndex()
                ->displayUsing(function ($name) {
                    return Str::limit($name, 15);
                }),
            GoogleAutocomplete::make('Address')
                ->withValues(['latitude', 'longitude'])
                ->hideFromIndex(),
            AddressMetadata::make('latitude')->hideFromIndex()->fromValue('latitude')->invisible(),
            AddressMetadata::make('longitude')->hideFromIndex()->fromValue('longitude')->invisible(),
            Boolean::make('ID Uploaded', function() {
                return count($this->documents) > 0;
            }),
            Boolean::make('Verified'),
            Boolean::make('Suspended'),
            Boolean::make('Accredited'),
            Number::make('Created Jobs')
                ->sortable()
                ->onlyOnIndex(),
            Number::make('Active Jobs', function() {
                    if ($this->role === 'driver') {
                        return $this->active_jobs_driver;
                    }
                    return $this->active_jobs_mechanic;
                })
                ->sortable()
                ->onlyOnIndex(),
            Number::make('Fulfilled Jobs', function() {
                    if ($this->role === 'driver') {
                        return $this->fulfilled_jobs_driver;
                    }
                    return $this->fulfilled_jobs_mechanic;
                })
                ->sortable()
                ->onlyOnIndex(),
            Number::make('Avg Job Price', function() {
                    if ($this->role === 'driver') {
                        return round($this->avg_job_price_driver, 2);
                    }
                    return round($this->avg_job_price_mechanic, 2);
                })
                ->sortable()
                ->onlyOnIndex(),
            Number::make('Gross Merchant Value', function() {
                    if ($this->role === 'driver') {
                        return round($this->gross_merchant_value_driver, 2);
                    }
                    return round($this->gross_merchant_value_mechanic, 2);
                })
                ->sortable()
                ->onlyOnIndex(),
            Number::make('Gross Enquiry Value', function() {
                    if ($this->role === 'driver') {
                        return round($this->gross_enquiry_value_driver, 2);
                    }
                    return round($this->gross_enquiry_value_mechanic, 2);
                })
                ->sortable()
                ->onlyOnIndex(),
            DateTime::make('Created At')->sortable(),

            HasMany::make('Jobs')->canSee(function ($request) {
                return $this->resource->role == 'driver';
            }),

            HasMany::make('Uploaded ID', 'Documents', 'App\Nova\Document')->canSee(function ($request) {
                return $this->resource->role == 'mechanic';
            }),
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
        return [
            new UserType,
            new DateRange('created_at'),
            new UserVerified,
            new UserSuspended,
            new UserByJob,
            new MechanicWithService,
            new UserAccredited,
        ];
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
            new SuspendUser,
            new UnsuspendUser,
            new MarkVerfied,
            new MarkAccredited,
            (new DownloadExcel)->withWriterType(\Maatwebsite\Excel\Excel::CSV)
                               ->only('name', 'address', 'phone', 'email')
                               ->withHeadings('Name', 'Address', 'Phone Number', 'Email')
                               ->withFilename('users-' . time() . '.csv'),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->whereIn('role', ['driver', 'mechanic']);
        $query->when(empty($request->get('orderBy')), function(\Illuminate\Database\Eloquent\Builder $q) {
            $q->getQuery()->orders = [];

            return $q->orderBy(static::DEFAULT_INDEX_ORDER);
        });

        $query->withCount('jobs as created_jobs');

        // Get KPI for driver
        $query->withCount(['jobs as active_jobs_driver' => function($q) {
                $q->where('role', 'driver')->doesnthave('fulfilledJob')->orWhereHas('fulfilledJob', function($sq) {
                    return $sq->where('fulfilled', false);
                });
            }, 'jobs as fulfilled_jobs_driver' => function($q) {
                $q->where('role', 'driver')->whereHas('fulfilledJob', function($sq) {
                    return $sq->where('fulfilled', true);
                });
            }]);

        $query->addSelect(DB::raw('(select avg(cost) from bids join jobs on bids.job_id=jobs.id where users.id = jobs.user_id) as avg_job_price_driver'));

        $query->addSelect(DB::raw('(select sum(cost) from bids JOIN `fulfilled_jobs` ON bids.id = fulfilled_jobs.bid_id join jobs on jobs.id=bids.job_id where users.id = jobs.user_id AND fulfilled_jobs.fulfilled=1) as gross_merchant_value_driver'));

        $query->addSelect(DB::raw('(select sum(cost) from bids JOIN `fulfilled_jobs` ON bids.id = fulfilled_jobs.bid_id join jobs on jobs.id=bids.job_id where users.id = jobs.user_id AND (fulfilled_jobs.fulfilled=1 or fulfilled_jobs.fulfilled=0)) as gross_enquiry_value_driver'));

        // Get KPI for mechanic
        $query->withCount(['bids as active_jobs_mechanic' => function($q) {
                $q->where('role', 'mechanic')->doesnthave('fulfilledJob')->orWhereHas('fulfilledJob', function($sq) {
                    return $sq->where('fulfilled', false);
                });
            }, 'bids as fulfilled_jobs_mechanic' => function($q) {
                $q->where('role', 'mechanic')->whereHas('fulfilledJob', function($sq) {
                    return $sq->where('fulfilled', true);
                });
            }]);

        $query->addSelect(DB::raw('(select avg(cost) from bids where users.id = bids.user_id) as avg_job_price_mechanic'));

        $query->addSelect(DB::raw('(select sum(cost) from bids JOIN `fulfilled_jobs` ON bids.id = fulfilled_jobs.bid_id where users.id = bids.user_id AND fulfilled_jobs.fulfilled=1) as gross_merchant_value_mechanic'));

        $query->addSelect(DB::raw('(select sum(cost) from bids JOIN `fulfilled_jobs` ON bids.id = fulfilled_jobs.bid_id where users.id = bids.user_id AND (fulfilled_jobs.fulfilled=1 or fulfilled_jobs.fulfilled=0)) as gross_enquiry_value_mechanic'));
    }

    // public static function canImportResource(Request $request)
    // {
    //     return true;//$request->user()->can("create", self::$model);
    // }

    public static function excludeAttributesFromImport()
    {
        return ['verified', 'suspended', 'accredited', 'created_at'];
    }
}
