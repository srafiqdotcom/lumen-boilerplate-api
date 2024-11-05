<?php

namespace App\Repositories\V1;
use Illuminate\Support\Facades\Validator;

class BaseRepository
{

    public function __construct()
    {
        $this->request = app('request');

        // $this->apikey = $this->request->header('apikey');
    }

    public function validated($rules, $request)
    {
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        return Validator::make($request, $rules, $customMessages)->stopOnFirstFailure(true);

    }

}
