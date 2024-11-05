<?php

namespace App\Repositories\V1;


use App\Utilities\Messages;
use App\Utilities\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class UserRepository extends BaseRepository
{

    public function __construct(Request $request)
    {
        parent::__construct();
    }



    public function sampleFunction()
    {

        DB::beginTransaction();

        try {

            $customer = Customer::select("id", "custuuid")->where([["custid", "=", "SNX-CONSUMER"], ["isenable", "=", "Y"]])->first();

            if (!$customer) {
                return ResponseHandler::ErrorResponse("CUSTOMER_NOT_FOUND",Messages::$CUSTOMER_NOT_FOUND_CODE);
            }

            // udpate queries or any other

        } catch (\Throwable $e) {
            DB::rollback();
            return ResponseHandler::ErrorResponse($e->getMessage(),Messages::$DB_FAIL__ERROR_CODE);

        }

        DB::commit();

        // commit transactions here
        return ResponseHandler::SuccessResponse([], "Success");

    }

}
