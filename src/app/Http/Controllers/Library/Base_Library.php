<?php

namespace App\Http\Controllers\Library;

use App\Yaml\Yaml_Reader;

class Base_Library
{

    public $get_env;
    public $get_yaml;

    public function __construct()
    {
        $this->get_env  = getenv();
        $yaml_reader    = new Yaml_Reader();
        $this->get_yaml = $yaml_reader->get_config();
    }
}
