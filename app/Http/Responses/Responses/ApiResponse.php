<?php
/**
 * Created by PhpStorm.
 * User: waqas
 * Date: 3/16/2016
 * Time: 1:46 PM
 */

namespace App\Http\Responses\Responses;

use App\Http\Responses\Interfaces\ResponseInterface;
use App\Http\Responses\Response as AppResponse;
use App\Traits\RequestHelper;
use League\Flysystem\Config;

class ApiResponse extends AppResponse implements ResponseInterface
{
    use RequestHelper;

    public function __construct(){}


    /**
     * @param $response
     * @param $headers
     * @return json
     * @description
     * following function accepts data from
     * controllers and return a pre-setted view.
     **/
    public function respond(array $response, array $headers = []){
        $response['status'] = ($this->getHttpStatus() == 200)?1:0;
        $response['message'] = (isset($data['message']))?$data['message']:(($response['status'] == 1)?config('constants.SUCCESS_MESSAGE'):config('constants.ERROR_MESSAGE'));
        $response['access_token'] = $this->computeAccessToken($response);
        return response()->json($response, $this->getHttpStatus(), $headers);
    }

    public function computeAccessToken($response)
    {
        if(isset($response['access_token']))
            $access_token = $response['access_token'];
        else if(isset($response['data']) && isset($response['data']['authUser'])){
            $access_token = $response['data']['authUser']->access_token;
        }else{
            $access_token = $this->getAccessToken();
        }
        return $access_token;
    }

}