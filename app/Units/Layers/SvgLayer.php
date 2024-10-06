<?php

namespace App\Units\Layers;

use App\Units\Helpers\ImageHelper;
use App\Units\Helpers\SvgHelper;
use App\Units\Helpers\TextHelper;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\FontSizeUnit;
use App\Units\Styles\GridElementUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class SvgLayer
{
    public static function rander($element)
    {
        $style = '';

        $data = [
            'children' => []
        ];
        $data['children'][] = SvgHelper::getAttr($element);


        $data['style'] = $style;
        $data['grid']= GridElementUnit::rander($element);

        return $data;
    }

}
