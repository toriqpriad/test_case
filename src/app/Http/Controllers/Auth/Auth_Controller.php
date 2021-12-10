<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Service\Merchant_Service;
use App\Http\Controllers\Service\User_Service;
use App\Http\Controllers\Library\JWT_Library;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Auth_Controller extends Controller
{


    public function SubmitLogin(Request $request)
    {
        try {
            $rules = ['username' => 'required', 'password' => 'required',];
            $this->custom_validator($request, $rules);
            $username     = $request->post('username');
            $password     = $request->post('password');
            $user_service = new User_Service();
            $get          = $user_service->get_user_by('user_name', $username);
            if ($get['status'] != 1)
                throw new \Exception('user not found');

            if (empty($get['data']))
                throw new \Exception('user not found');

            $user_data     = $get['data'];
            $real_password = $user_data->password;

            if ($real_password != md5($password))
                throw new \Exception('invalid password');

            unset($user_data->password);
            $user_id           = $user_data->id;
            $merchant_service  = new Merchant_Service();
            $get_merchant_data = $merchant_service->get_merchant_by('user_id', $user_id);
            if ($get_merchant_data['status'] != 1)
                throw new \Exception('invalid merchant data');

            $user_data->merchant_data = $get_merchant_data['data'];
            $today                    = Carbon::now()->isoFormat('dddd, D MMMM Y');
            $jwtLib                   = new JWT_Library();
            $encode                   = $jwtLib->encode_data($user_data, 1);
            $jwtEncode                = $encode['data'];
            return response()->json([
                'status'  => 1,
                'message' => 'success login',
                'data'    => [
                    'token' => $jwtEncode
                ]
            ], 200);


        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'failed login',
                'detail'  => $e->getMessage()
            ], 401);
        }
    }

}
