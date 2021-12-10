<?php

namespace App\Yaml;

use Symfony\Component\Yaml\Yaml;

class Yaml_Reader
{
    public function get_config($name = FALSE)
    {
        $yaml      = new Yaml();
        $yaml_file = app_path('Yaml/config.yml');
        $read      = $yaml->parse(file_get_contents($yaml_file));
        if ($name) {
            return $read[$name];
        } else {
            return $read;
        }

    }
}
