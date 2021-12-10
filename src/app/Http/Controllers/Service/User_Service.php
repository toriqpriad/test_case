<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User_Model;

class User_Service extends Base_Service
{
    public function get_user_by($column, $value)
    {
        try {

            $muser = new User_Model();
            $get   = $muser->do_connection()->where($column, $value)->first();

            return [
                'status' => 1,
                'data'   => $get
            ];

        } catch (\Exception $e) {

            return [
                'status'  => 0,
                'message' => $e->getMessage()
            ];
        }
    }

}
