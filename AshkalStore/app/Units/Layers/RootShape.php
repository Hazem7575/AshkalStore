<?php

namespace App\Units\Layers;

use App\Units\Helpers\ImageHelper;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class RootShape
{
    public static function rander($element)
    {
        $data = [
            'children' => []
        ];

        $style = '';
        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element);;
        $style .= ColorUnit::rander($element);
        //convert in grid way 
        $style.="display:grid;";
        

        if (isset($element['image']['url'])) {
            $data['children'][] = ImageHelper::getAttr($element['image']);
        }
        $style .= 'overflow: hidden;position: relative;left:auto;right:auto;';
        // $style .= 'overflow: hidden;position: relative;width: 100%;left:auto;right:auto;';
        $data['style'] = $style;
        return $data;
    }


}
