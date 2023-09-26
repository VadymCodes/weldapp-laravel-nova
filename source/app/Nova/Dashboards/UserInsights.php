<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\ActiveJobs;
use App\Nova\Metrics\AvgPrice;
use App\Nova\Metrics\CreatedJobs;
use App\Nova\Metrics\EnquiryValue;
use App\Nova\Metrics\FulfilledJobs;
use App\Nova\Metrics\MerchantValue;
use App\Nova\Metrics\JobsPerCategory;
use App\Nova\Metrics\Users;
use Laravel\Nova\Dashboard;

class UserInsights extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new ActiveJobs,
            new FulfilledJobs,
            new CreatedJobs,
            new Users,
            new AvgPrice,
            new MerchantValue,
            new EnquiryValue,
            new JobsPerCategory,
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'user-insights';
    }
}
