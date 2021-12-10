<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ErrorReturnCheck($message)
    {
        $returnMsg = $message;
        if (getenv('APP_ENV') != "local") {
            if (!str_contains($message, '[MSG]')) {
                // LOGGING TO SLACK
                $error_id   = date('YmdHis');
                $error_data = ['id' => $error_id, 'message' => $message];
                $returnMsg  = "Terjadi kesalahan saat memproses, hubungi operator. #{$error_id}";
                Log::error(json_encode($error_data));
            } else {
                $returnMsg = str_replace("[MSG]", '', $message);
            }
        } else {
            $returnMsg = str_replace("[MSG]", '', $message);
        }
        return $returnMsg;
    }

    public function custom_validator($input, $rules, $message = FALSE)
    {
        if ($message == FALSE) {
            $message = [];
        }
        $validator = Validator::make($input->all(), $rules, $message);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

    }
}
