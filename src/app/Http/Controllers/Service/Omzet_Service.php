<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Service\Base_Service;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Model\Transaction_Model;
use Illuminate\Http\Request;


class Omzet_Service extends Base_Service
{

    public function get_merchant_omzet($merchant_id, $outlet_id = FALSE, $start_date = FALSE, $end_date = FALSE, $pageNumber = FALSE)
    {
        try {

            if ($pageNumber == FALSE)
                $pageNumber = 1;

            $transaction          = new Transaction_Model();
            $dates_between_format = FALSE;
            $where_date           = '';
            $where_outlet         = '';
            if ($outlet_id == TRUE) {
                $where_date = " AND t.outlet_id = '$outlet_id' ";
            }
            if ($start_date == TRUE) {
                if ($end_date == FALSE) {
                    // jika hanya start date saja atau satu tanggal saja
                    $where_date = " AND DATE(t.created_at) = '$start_date' ";
                } else {
                    // jika start dan end date
                    $where_date    = " AND DATE(t.created_at) BETWEEN '$start_date' AND '$end_date' ";
                    $dates_between = $this->get_dates_from_range($start_date, $end_date);
                    foreach ($dates_between as $each) {
                        $dates_between_format[$each] = 0;
                    }
                }
            }
            $sql = "SELECT DATE_FORMAT(t.created_at, '%Y-%m-%d') as tgl,SUM(t.bill_total) as total_omzet
                    FROM $transaction->table_transaction AS t
                    WHERE t.merchant_id = $merchant_id
                    $where_outlet
                    $where_date
                    GROUP BY DATE(t.created_at)
                    ORDER BY DATE(t.created_at) asc";

            $result = $transaction->do_sql($sql);
            $data   = NULL;
            if (!empty($result)) {
                if ($dates_between_format == TRUE) {
                    foreach ($result as $dt) {
                        if (in_array($dt->tgl, $dates_between)) {
                            $dates_between_format[$dt->tgl] = $dt->total_omzet;
                        }
                    }
                    $valid = [];
                    foreach ($dates_between_format as $dt2Key => $dt2Val) {
                        $valid[] = ['tgl' => $dt2Key, 'total_omzet' => $dt2Val];
                    }

                    $result = $valid;
                }
                $total   = count($result);
                $request = request();
                $data    = new Paginator($result, $total, 10, $pageNumber, [
                    'path'     => $request->url(),
                    'pageName' => 'page_number'
                ]);

            }

            return [
                'status' => 1,
                'data'   => $data
            ];

        } catch (\Exception $e) {

            return [
                'status'  => 0,
                'message' => $e->getMessage()
            ];
        }
    }

    function get_dates_from_range($start, $end)
    {
        $dates = array($start);
        while (end($dates) < $end) {
            $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
        }
        return $dates;
    }
}
