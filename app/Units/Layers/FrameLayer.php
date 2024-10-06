<?php

namespace App\Units\Layers;

use App\Units\Helpers\ImageHelper;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ClipPathUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\GridElementUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class FrameLayer
{
    public static function rander($element)
    {
        $style = '';

        $style = '';

        $data = [
            'children' => []
        ];


        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element,true);
        $style .= ClipPathUnit::rander($element);
        $style .= ColorUnit::rander($element);
        $style .= OpacityUnit::rander($element);

        if (isset($element['image']['url'])) {
            $data['children'][] = ImageHelper::getAttr($element['image']);
        }

        $data['style'] = $style;
        $data['grid']= GridElementUnit::rander($element);

        return $data;

    }
}
