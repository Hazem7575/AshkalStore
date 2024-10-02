<?php

namespace App\Units\Helpers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;

class ImageHelper
{
    public static function getAttr($element)
    {
        $styleImage = '';

        $styleImage .= OpacityUnit::rander($element);
        $styleImage .= BoxSizeUnit::rander($element['boxSize']);
        $styleImage .= PositionUnit::rander($element['position']);

        return [
            'type' => 'img',
            'attr' => [
                'src' => $element['url'],
                'style' => $styleImage,
            ]
        ];
    }


    public static function render($element)
    {
        $attrs = '';
        foreach ($element['attr'] as $key => $value) {
            $attrs .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }
        return '<img ' . trim($attrs) . '>';
    }

}
