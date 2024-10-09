<?php

namespace App\Units\Layers;

use App\Units\Helpers\ImageHelper;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\GridElementUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\RoundedCornersUnit;
use App\Units\Styles\ShapeUnit;
use App\Units\Styles\TransformUnit;

class ShapeLayer
{
    public static function rander($element)
    {
        $style = '';

        $data = [
            'children' => []
        ];


        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element);;
        $style .= ColorUnit::rander($element);
        $style .= OpacityUnit::rander($element);
        $style .= RoundedCornersUnit::rander($element);
        $style .= ShapeUnit::rander($element);
        if (isset($element['image']['url'])) {
            $data['children'][] = ImageHelper::getAttr($element['image'] , $element);
        }

        $data['style'] = $style;
        $data['grid']= GridElementUnit::rander($element);

        return $data;
    }

    public static function randerStyleDiv($element) {

    }
}
