<?php

namespace App\Http\Controllers\API;

use App\Repositories\V1\UserRepository as UserRepositoryV1;
use App\Repositories\V2\UserRepository as UserRepositoryV2;
use App\Utilities\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserController extends BaseController
{
    protected $userRepoV1;
    protected $userRepoV2;

    public function __construct()
    {
        parent::__construct();
        $this->userRepoV1 = app(UserRepositoryV1::class);
        $this->userRepoV2 = app(UserRepositoryV2::class);
    }



    public function isUserExist(Request $request)
    {
        $version = "userRepoV".Config::get("version");

        return $this->$version->sampleFunction($request);

    }
}
