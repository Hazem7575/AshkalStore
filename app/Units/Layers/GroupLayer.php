<?php

namespace App\Units\Layers;

use App\Units\Helpers\ImageHelper;
use App\Units\Helpers\TextHelper;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\FontSizeUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class GroupLayer
{
    public static function rander($element)
    {
        $style = '';

        $data = [
            'children' => []
        ];

        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element);
        $data['style'] = $style;
        return $data;
    }

}
