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

class ImageLayer
{
    public static function rander($element , $is_root = false)
    {
        $style = '';
        $data = [
            'children' => []
        ];

        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element,true);
        $style .= ClipPathUnit::rander($element);
        $style .= ColorUnit::rander($element);
        $style .= OpacityUnit::rander($element);


        $style .= 'overflow: hidden;';
        if(isset($element['image']['flipHorizontal']) AND $element['image']['flipHorizontal']) {
            $style .= 'transform: scale(-1, 1);';
        }

//        $element['image']['boxSize']['width'] = $element['boxSize']['width'];

        $data['children'][] = ImageHelper::getAttr($element['image'] , $element , $is_root);

        $data['style'] = $style;
        $data['grid'] = GridElementUnit::rander($element);

        return $data;

    }
}
