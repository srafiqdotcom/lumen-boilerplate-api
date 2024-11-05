<?php

namespace App\Http\Controllers\API;

class BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->request = app('request');

       // $this->apikey = $this->request->header('apikey');
    }
    //
}
