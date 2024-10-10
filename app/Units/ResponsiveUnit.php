<?php

namespace App\Units;

class ResponsiveUnit
{
    public $style;

    public function new_size($file , $new_width, $new_height)
    {
        $json = json_decode($file);
        $new_json = $this->new_json($json);
        dd($new_json);
        return $json;
    }

    public function new_json($file_json)
    {
        $new_json = [];
        foreach ($file_json as $json) {

            dd($json);
        }
    }
}
