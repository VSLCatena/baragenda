<?php
namespace App\Helpers\GoogleCalendarResource;

use Illuminate\Support\ServiceProvider;
use Spatie\GoogleCalendar\Exceptions\InvalidConfiguration;
###
### GoogleCalendarServiceProvider
###
class GoogleCalendarResourceServiceProvider extends GoogleCalendarServiceProvider {
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/google-calendar.php', 'google-calendar');

        $this->app->bind(GoogleCalendar::class, function () {
            $config = config('google-calendar');

            $this->guardAgainstInvalidConfiguration($config);

            return GoogleCalendarFactory::createForCalendarId($config['calendar_id']);
        });
        ## ADDED
        $this->app->bind(Resource::class, function () {

            return GoogleCalendarFactory::createForResources();
        });
        ## END_ADDED
        $this->app->alias(GoogleCalendar::class, 'laravel-google-calendar');
    }

}
