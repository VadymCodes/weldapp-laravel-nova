<?php

namespace App\Providers;

use App\Nova\Dashboards\UserInsights;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Nova\Metrics\ActiveJobs;
use App\Nova\Metrics\AvgPrice;
use App\Nova\Metrics\CreatedJobs;
use App\Nova\Metrics\EnquiryValue;
use App\Nova\Metrics\FulfilledJobs;
use App\Nova\Metrics\MerchantValue;
use App\Nova\Metrics\JobsPerCategory;
use App\Nova\Metrics\Users;
use Mastani\NovaPasswordReset\NovaPasswordReset;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use SimonHamp\LaravelNovaCsvImport\LaravelNovaCsvImport;
use Coroowicaksono\ChartJsIntegration\StackedChart;
use Coroowicaksono\ChartJsIntegration\LineChart;
use Coroowicaksono\ChartJsIntegration\BarChart;
use Coroowicaksono\ChartJsIntegration\PieChart;
use Coroowicaksono\ChartJsIntegration\AreaChart;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                'faruk@weldapp.co'
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        $categories = Category::get();
        $series = array();
        foreach ($categories as $category) {
            $item = array();
            $item['label'] = $category->name;
            $item['filter']['key'] = 'category_id';
            $item['filter']['value'] = $category->id;
            array_push($series, $item);
        }

        return [
            new Users,
            new JobsPerCategory,
            new CreatedJobs,
            new ActiveJobs,
            new FulfilledJobs,
            // new Help,
            new AvgPrice,
            // new MerchantValue,
            // new EnquiryValue,

            (new AreaChart())
                ->title('Gross Mechant Value')
                ->model('\App\Models\Bid')
                ->join('fulfilled_jobs', 'bids.id', '=', 'fulfilled_jobs.bid_id')
                ->series(array([
                    'label' => 'Gross Mechant Value',
                    'filter' => [
                        'key' => 'fulfilled',
                        'value' => true
                    ],
                ]))
                ->options([
                    'btnFilter' => true,
                    'legend' => [
                        'display' => false
                    ],
                    'sum' => 'cost',
                    'showTotal' => false
                ])
                ->width('1/3'),

            (new AreaChart())
                ->title('Gross Enquiry Value')
                ->model('\App\Models\Bid')
                ->join('fulfilled_jobs', 'bids.id', '=', 'fulfilled_jobs.bid_id')
                ->options([
                    'btnFilter' => true,
                    'legend' => [
                        'display' => false
                    ],
                    'sum' => 'cost',
                    'showTotal' => false
                ])
                ->width('1/3'),

            (new PieChart())
                ->title('Users')
                ->model('\App\Models\User')
                ->series(array([
                    'label' => 'Driver',
                    'filter' => [
                        'key' => 'role',
                        'value' => 'driver'
                    ],
                ],[
                    'label' => 'Mechanic',
                    'filter' => [
                        'key' => 'role',
                        'value' => 'mechanic'
                    ],
                ]))
                ->options([
                    'showPercentage' => true,
                ])
                ->width('1/2'),

            (new AreaChart())
                ->title('Created Jobs')
                ->model('\App\Models\Job')
                ->options([
                    'btnFilter' => true,
                    'legend' => [
                        'display' => false
                    ]
                ])
                ->width('1/2'),

            (new AreaChart())
                ->title('Active Jobs')
                ->model('\App\Models\FulfilledJob')
                ->options([
                    'btnFilter' => true,
                    'legend' => [
                        'display' => false
                    ],
                    'queryFilter' => array([
                        'key' => 'fulfilled',
                        'operator' => 'IS NOT NULL',
                    ])
                ])
                ->width('1/2'),

            (new AreaChart())
                ->title('Fulfilled Jobs')
                ->model('\App\Models\FulfilledJob')
                ->options([
                    'btnFilter' => true,
                    'legend' => [
                        'display' => false
                    ],
                    'queryFilter' => array([
                        'key' => 'fulfilled',
                        'operator' => '=',
                        'value' => 1
                    ])
                ])
                ->width('1/2'),

            // (new PieChart())
            //     ->title('Jobs per category')
            //     ->model('\App\Models\Job')
            //     ->series($series)
            //     ->options([
            //         'showPercentage' => true,
            //     ])
            //     ->width('full'),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            // new UserInsights
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new LaravelNovaCsvImport,
            new NovaPasswordReset,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
