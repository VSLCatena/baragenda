<?php
###
### GoogleCalendarFactory
###
namespace Spatie\GoogleCalendar;
#namespace Google\Service\Directory\Resource;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Directory;

class GoogleCalendarResourceFactory extends GoogleCalendarFactory {

    public static function createForResources(): Resource
    {
        $config = config('google-calendar');

        $client = self::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Directory($client);

        return self::createResourceClient($service);
    }

    protected static function createServiceAccountClient(array $authProfile): Google_Client
    {
        $client = new Google_Client;

        $client->setScopes([
            Google_Service_Calendar::CALENDAR,
            Google_Service_Directory::ADMIN_DIRECTORY_RESOURCE_CALENDAR_READONLY ##extended
        ]);

        $client->setAuthConfig($authProfile['credentials_json']);

        if (config('google-calendar')['user_to_impersonate']) {
            $client->setSubject(config('google-calendar')['user_to_impersonate']);
        }

        return $client;
    }

    protected static function createResourceClient(Google_Service_Directory $service): Resource
    {
        return new Resource($service);
    }
}
###
### GoogleCalendarServiceProvider
###
class GoogleCalendarResourceServiceProvider extends GoogleCalendarServiceProvider {
    public function register()
    {
        #$this->mergeConfigFrom(__DIR__.'/../../../config/google-calendar.php', 'google-calendar');

        $this->app->bind(GoogleCalendar::class, function () {
            $config = config('google-calendar');

            $this->guardAgainstInvalidConfiguration($config);

            return GoogleCalendarFactory::createForCalendarId($config['calendar_id']);
        });

        $this->app->bind(Resource::class, function () {

            return GoogleCalendarFactory::createForResources();
        });

        $this->app->alias(GoogleCalendar::class, 'GSCalendar');
    }

}
###
### resource
###

namespace Spatie\GoogleCalendar;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTime;

use Google_Service_Directory;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class Resource {
    /** @var \Google_Service_Directory */
    protected $resourceService;


    public function __construct(Google_Service_Directory $resourceService)
    {
        $this->resourceService = $resourceService;
        $this->resources = [];
    }


    public static function get(): Array
    {
        $adminService = GoogleCalendarFactory::createForResources();

        $googleResources = $adminService->resourceService->resources_calendars->listResourcesCalendars('my_customer'); //the whole resource object

        $googleResourcesList = $googleResources->getItems();

        while ($googleResources->getNextPageToken()) {
            $queryParameters['pageToken'] = $googleResources->getNextPageToken();

            $googleResources = $adminService->resources_calendars->listResourcesCalendars('my_customer');

            $googleResourcesList = array_merge($googleResourcesList, $googleResources->getItems());
        }
        #echo("<pre>");print_r($googleResourcesList);
        $resources=$googleResourcesList;

        foreach($resources as $key => $resource) {
            $resourceList[] = array(
                'name' => $resource->getResourceName() ,
                'id' => $resource->getResourceId() ,
                'generatedname' => $resource->getGeneratedResourceName() ,
                'capacity' => $resource->getCapacity() ,
                'floorname' => $resource->getFloorName() ,
                'floorsection' => $resource->getFloorSection() ,
                'features'=> $resource->getFeatureInstances(),
                'email' => $resource->getResourceEmail()
            );
        }

        return $resourceList;
    }
}
