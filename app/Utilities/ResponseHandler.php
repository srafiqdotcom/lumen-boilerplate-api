<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Repositories\V1\EPFailedActivationLogsRepository;

use stdClass;
class ResponseHandler
{

    public static function ErrorResponse($emsg = '', $ecode = 9770, $scode = 200, $data = NULL)
    {

        if(empty($data))
        {
            $data = new stdClass();
        }

        $request = app("request");
        $uuid = $request->uuid??"";

//         if($ecode == 8115 || $ecode == 9779){
        if($ecode == 8115){

            $epApi = \Route::current()->uri;

            \Log::channel('user_not_found_log')->info("$uuid - record not found.API Endpoint => ".$epApi);

            $ecode = 8106;

            $emsg = "SlashNext cloud is unable to locate this user.";
        }

        $data = array (
            'status' => $scode,
            'code' => $ecode,
            'message' => $emsg,
            'data' => $data
        );

        if( !is_null(\Route::current()) && isset(\Route::current()->uri) )
        {
            if( in_array(\Route::current()->uri,array('api/endpoint/activate/mv3','api/endpoint/reactivate/mv3')) )
            {
                app(EPFailedActivationLogsRepository::class)->recordFailedActivationLog( $request ,$data);
            }
        }

        return $data;
    }

    public static function SuccessResponse($data = NULL, $msg = '')
    {
        if(empty($data))
        {
            $data = new stdClass();
        }

        return array (
            'status' => 200,
            'code' => 8200,
            'message' => $msg,
            'data' => $data
        );
    }

     public static function ApplyHTMLEntities($params)
    {
        if (is_array($params))
        {
            return array_map([self::class, __METHOD__], $params);
        } else if (is_string($params))
        {
            return htmlspecialchars($params);
        } else if (is_a($params, 'Illuminate\Database\Eloquent\Collection'))
        {
            return array_map([self::class, __METHOD__], $params->toArray());
        } else
        {
            return $params;
        }
    }

}
