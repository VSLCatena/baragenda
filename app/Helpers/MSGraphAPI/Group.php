<?php
namespace App\Helpers\MSGraphAPI;


use Microsoft\Graph\{Graph};
use GuzzleHttp;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

#https://forum.yiiframework.com/t/microsoft-graph-api-working-example/133342
class Group {
    protected $LimitQueryUser;
    protected $LimitQueryGroup ;
    protected $employeeNumberProperty ;
    protected $descriptionProperty ;


    public function __construct()
    {
        $extension_app_id = Str::remove('-', env('AZURE_EXTENSION_APP_ID'));
        $this->employeeNumberProperty = 'extension_' . $extension_app_id . '_employeeNumber';
        $this->descriptionProperty = 'extension_' . $extension_app_id . '_description';
        $this->LimitQueryUser = '&$select=id,displayName,givenName,' . $this->descriptionProperty . ',mail,employeeId,' . $this->employeeNumberProperty  . ',onPremisesSamAccountName';
        $this->LimitQueryGroup = '&$select=id,displayName';
    }

    public function getGroupInfo($groupName) {
        $token = $this->_token();
        if ($token) {
            if($groupName == "leden"){
                $target = env('AZURE_SEARCH_FILTER_GROUP_LEDEN');
            }
            elseif($groupName == "commissies"){
                $target = env('AZURE_SEARCH_FILTER_GROUP_COMMISSIES');
            }
            else{
                return false;
            }

            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);


            $endpoint = '/groups';
            $url = $endpoint . '?$count=true&$orderBy=displayName&$search=' . "\"$target\"" . $this->LimitQueryGroup;
            $request = $graph->createRequest("GET", $url);
            $request->addHeaders(["ConsistencyLevel"=> "eventual"]);
            $data=$request->execute();

            foreach ($data->getBody()['value'] as $key => $val) {
                $groupinfo[] = [
                    'id' => $val['id'],
                    'displayName' => strtolower($val['displayName'])
                ];
            }
            return $groupinfo;
        }
    }


    public function getGroupMembers($groupID, $nextUrl = null) {

        $token = $this->_token();

        if ($token) {
            $graph = new Graph();
            $graph->setApiVersion("beta");
            $graph->setAccessToken($token);
            $endpoint = "/groups/$groupID/members";
            if ($nextUrl) {
                $url = $nextUrl;
            } else {
                $url = $endpoint . '?$count=true&$orderBy=displayName' . $this -> LimitQueryUser;
            }
            //prepare request
            $request = $graph->createRequest("GET", $url);
            $request->addHeaders(["ConsistencyLevel"=> "eventual"]);
            //send request
            $response = $request->execute();
            //parse response
            $content = $response->getBody();
            $nexturl = $response-> getNextLink();
            //prepare output
            $GroupObject['count']=$content['@odata.count'] ?? null;
            $GroupObject['group']=array("id"=> $groupID);
            //loop over content
            foreach ($content['value'] as $key => $val) {
                $description = Arr::has($val,$this->descriptionProperty) ? Arr::join( $val[$this->descriptionProperty] , ',' ) : null;
                $GroupObject['members'][] = [
                    'id' => $val['id'],
                    'objectType' => Str::remove('#microsoft.graph.', $val['@odata.type']),
                    'displayName' => $val['displayName'],
                    'givenName' => $val['givenName'] ?? null,
                    'description' => $description ?? null,
                    'mail' => "#privacyredacted", #strtolower($val['mail'] ?? null),
                    'employeeId' => $val['employeeId'] ?? null,
                    'onPremisesSamAccountName' => $val['onPremisesSamAccountName'] ,
                    'employeeNumber' => $val[$this->employeeNumberProperty ] ?? null
                ];
            }
            if ($nexturl && $nexturl !== '') {
                $GroupObject['members'] = array_merge($GroupObject['members'], ($this->getGroupMembers($groupID, $nexturl))['members']);
            }

            return $GroupObject;
        }
    }

    private function _token(){
        $config = config('services')['azure'];
        try {
            $requestParms = ['form_params' => [

                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'resource' => 'https://graph.microsoft.com/',
                'grant_type' => 'client_credentials',
            ]];

            $client = new GuzzleHttp\Client([
                'base_uri' => "https://login.microsoftonline.com/". $config['tenant'] .  "/oauth2/",
                'timeout'  => 2.0,
            ]);
            $response =  (string) $client->request('POST', "token?api-version=1.0", $requestParms)->getBody();
            $response = json_decode($response);
            if (isset($response->access_token)) {
                return $response->access_token;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            print_r($e->getMessage(), true);
            return false;
        };
    }

    public function getToken(){
        return $this->_token();
    }



}
