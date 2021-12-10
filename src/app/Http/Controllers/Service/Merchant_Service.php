<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Merchant_Model;

class Merchant_Service extends Base_Service
{
    public function get_merchant_by($column, $value)
    {
        try {

            $merchant = new Merchant_Model();
            $get      = $merchant->do_connection()->where($column, $value)->first();

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
