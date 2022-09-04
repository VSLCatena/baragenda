<?php
###
### GoogleCalendarFactory
###
namespace App\Helpers\GoogleCalendarResource;

#namespace Google\Service\Directory\Resource;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Directory;

class ResourceFactory extends \Spatie\GoogleCalendar\GoogleCalendarFactory {

    public static function createForResources(): Resource
    {
        $config = config('google-calendar');
        $client = self::createServiceAccountClient($config['auth_profiles']['service_account']);

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

