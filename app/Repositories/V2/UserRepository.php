<?php

namespace App\Repositories\V2;

use App\Models\EPUser;
use App\Utilities\ResponseHandler;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function userSignUp($request)
    {

        return ResponseHandler::SuccessResponse($request->all(),"Success");
    }
}
