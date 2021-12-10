<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Service\Base_Service;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Model\Outlet_Model;
use Illuminate\Http\Request;


class Outlet_Service extends Base_Service
{

    public function get_outlet_by($column, $value)
    {
        try {

            $model = new Outlet_Model();
            $get   = $model->do_connection()->where($column, $value)->first();

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
