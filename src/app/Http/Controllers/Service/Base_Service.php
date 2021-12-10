<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Yaml\Yaml_Reader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Base_Service extends Controller
{
    //

    public $timestamp;

    public function __construct()
    {
        $this->timestamp = date("Y-m-d H:i:s");
    }

    public function get_request_data()
    {
        $request = \request();
        return $request->query();
    }

}
