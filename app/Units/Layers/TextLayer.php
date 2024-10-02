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

class TextLayer
{
    public static function rander($element)
    {
        $style = '';

        $data = [
            'children' => []
        ];

        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= FontSizeUnit::rander($element);
        $style .= TransformUnit::rander($element);

        if (isset($element['text'])) {
            $data['children'][] = TextHelper::getAttr($element);
        }

        if (isset($element['fonts'])) {
            foreach ($element['fonts'] as $font) {
                if (isset($font['name'], $font['fonts'][0]['urls'][0])) {
                    $style .= 'font-family: ' . $font['name'] . '; ';
                }
            }
        }

        $data['style'] = $style;

        // إرجاع البيانات
        return $data;
    }

}
