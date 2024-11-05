<?php

namespace App\Repositories\V2;

use Illuminate\Support\Facades\Validator;

class BaseRepository
{
    public function __construct()
    {

    }

    public function validated($rules, $request)
    {
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];

        return Validator::make($request, $rules, $customMessages)->stopOnFirstFailure(true);

    }
}
