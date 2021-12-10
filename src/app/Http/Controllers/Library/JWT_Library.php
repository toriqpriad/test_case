<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Library\Base_Library;
use Firebase\JWT\JWT;

class JWT_Library extends Base_Library
{
    private $env;
    private $yaml;
    private $key;
    private $hash;
    private $expired_interval;

    public function __construct()
    {
        parent::__construct();
        $this->env              = $this->get_env;
        $this->yaml             = $this->get_yaml['JWT'];
        $this->key              = $this->yaml["KEY"];
        $this->hash             = $this->yaml["ALGORITMA"];
        $this->expired_interval = $this->yaml["EXPIRED"];
    }

    public function decode_token($token, $roleName = FALSE)
    {
        try {

            $jwt         = new JWT();
            $decodeToken = $jwt->decode($token, $this->key, [$this->hash]);

            if (!$decodeToken) {
                throw new \Exception("invalid token");
            }

            $data = (array)$decodeToken;

            if ($roleName == TRUE) {
                if ($data['role'] != $roleName) {
                    throw new \Exception("invalid user role");
                }
            }

            return [
                'status'  => 1,
                'data'    => $data,
                'message' => "valid token",
            ];
        } catch (\Exception $e) {
            return [
                'status'  => 0,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function encode_data($data, $with_expired = FALSE)
    {

        try {

            $jwt = new JWT();
            if ($with_expired == TRUE) {
                $today       = date("Y-m-d H:i:s");
                $expired_day = date('Y-m-d H:i:s', strtotime($today . "+{$this->expired_interval} day"));
                if (is_object($data)) {
                    $data->expired_token_at = $expired_day;
                }
                if (is_array($data)) {
                    $data['expired_token_at'] = $expired_day;
                }
            }
            $encodeData = $jwt->encode($data, $this->key);
            if (!$encodeData) {
                throw new \Exception("failed to generating data encode");
            }

            return [
                'status'  => 1,
                'data'    => $encodeData,
                'message' => "successfully encode data",
            ];
        } catch (\Exception $e) {
            return [
                'status'  => 0,
                'message' => $e->getMessage(),
            ];
        }
    }
}
