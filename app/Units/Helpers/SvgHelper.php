<?php

namespace App\Units\Helpers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class SvgHelper
{
    public static function getAttr($element)
    {
        // إنشاء خصائص SVG
        $style  = '';
        $style .= TransformUnit::rander($element);
        $style .= ColorUnit::rander($element, 'color');
        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= OpacityUnit::rander($element);

        $fillColor = $element['colors'][0] ?? 'none';

        return [
            'type' => 'svg',
            'attr' => [
                'width' => $element['boxSize']['width'],
                'height' => $element['boxSize']['height'],
                'style' => $style,
                'viewBox' => "0 0 {$element['boxSize']['width']} {$element['boxSize']['height']}",
                'transform' => "translate({$element['position']['x']}, {$element['position']['y']})"
            ],
            'fill' => $fillColor,
            'image' => $element['image']
        ];
    }

    public static function render($element)
    {
        $attrs = '';
        foreach ($element['attr'] as $key => $value) {
            $attrs .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }


        return '<img src="' . htmlspecialchars($element['image'], ENT_QUOTES) . '" ' . trim($attrs) . '  preserveAspectRatio="xMidYMid slice" style="filter: brightness(0) invert(1);"/>';

    }



}
