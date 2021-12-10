<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Library\JWT_Library;

class Module_Controller extends BaseController
{
    public $my_request;
    public $logged_user_data;
    public $env;

    public function __construct(Request $request)
    {
        // mencegah user yang tidak login
        $this->middleware('logged_user_middleware');
        $this->my_request       = $request;
        $this->logged_user_data = $this->get_logged_user_data($request->post('token'));
        $this->env              = getenv();
    }

    public function get_logged_user_data($token)
    {
        try {

            $jwtLib = new JWT_Library();
            $decode = $jwtLib->decode_token($token);
            if ($decode['status'] != 1)
                throw new \Exception();

            $data = $decode['data'];
            return $data;

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'invalid token',
            ], 401);
        }
    }
}
