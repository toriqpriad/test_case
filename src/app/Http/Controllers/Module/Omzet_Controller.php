<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Service\Omzet_Service;
use App\Http\Controllers\Service\Outlet_Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Module\Module_Controller;

class Omzet_Controller extends Module_Controller
{


    public function PostFetchMyOmzet(Request $request)
    {

        try {

            $date1      = FALSE;
            $date2      = FALSE;
            $pageNumber = FALSE;
            $rules      = ['merchant_id' => 'required'];
            if ($request->has('page_number'))
                $pageNumber = $request->get('page_number');

            if ($request->has('start_date')) {
                $date1               = $request->post('start_date');
                $rules['start_date'] = 'date_format:Y-m-d';
            }

            if ($request->has('end_date')) {
                $date2             = $request->post('end_date');
                $rules['end_date'] = 'date_format:Y-m-d';
            }

            $this->custom_validator($request, $rules);
            $merchant_id = $request->post('merchant_id');
            if ($merchant_id != $this->logged_user_data['merchant_data']->id)
                throw new \Exception('invalid merchant id');

            $omzet           = new Omzet_Service();
            $merchant_detail = $this->logged_user_data['merchant_data'];
            unset(
                $merchant_detail->user_id,
                $merchant_detail->created_at,
                $merchant_detail->updated_at,
                $merchant_detail->created_by,
                $merchant_detail->updated_by
            );
            $data  = ['merchant_info' => $merchant_detail];
            $fetch = $omzet->get_merchant_omzet($merchant_id, FALSE, $date1, $date2, $pageNumber);
            if ($fetch["status"] == 1 and !empty($fetch['data'])) {
                $data['omzet'] = $fetch['data'];
            }

            return response()->json([
                'status'  => 1,
                'message' => 'success fetch data my omzet',
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'failed fetch data my omzet',
                'detail'  => $e->getMessage(),
                'data'    => NULL
            ], 500);
        }
    }

    public function PostFetchMyOutletOmzet(Request $request)
    {

        try {

            $date1      = FALSE;
            $date2      = FALSE;
            $pageNumber = FALSE;
            $rules      = ['outlet_id' => 'required'];
            if ($request->has('page_number'))
                $pageNumber = $request->get('page_number');

            if ($request->has('start_date')) {
                $date1               = $request->post('start_date');
                $rules['start_date'] = 'date_format:Y-m-d';
            }

            if ($request->has('end_date')) {
                $date2             = $request->post('end_date');
                $rules['end_date'] = 'date_format:Y-m-d';
            }

            $this->custom_validator($request, $rules);
            $outlet_id   = $request->post('outlet_id');
            $merchant_id = $this->logged_user_data['merchant_data']->id;
            $outlet      = new Outlet_Service();
            $get_outlet  = $outlet->get_outlet_by('id', $outlet_id);
            if ($get_outlet["status"] != 1 or empty($get_outlet['data']))
                throw new \Exception('outlet id not found');

            $outlet_data = $get_outlet['data'];
            if ($merchant_id != $outlet_data->merchant_id)
                throw new \Exception('invalid outlet owner');

            unset(
                $outlet_data->merchant_id,
                $outlet_data->created_at,
                $outlet_data->updated_at,
                $outlet_data->created_by,
                $outlet_data->updated_by
            );
            $data['outlet_info'] = $outlet_data;
            $omzet               = new Omzet_Service();
            $fetch               = $omzet->get_merchant_omzet($merchant_id, $outlet_id, $date1, $date2, $pageNumber);
            if ($fetch["status"] == 1 and !empty($fetch['data'])) {
                $data['omzet'] = $fetch['data'];
            }

            return response()->json([
                'status'  => 1,
                'message' => 'success fetch data my outlet omzet',
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'failed fetch data my outlet omzet',
                'detail'  => $e->getMessage(),
                'data'    => NULL
            ], 500);
        }
    }

}
