<?php

namespace App\Units\Helpers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\FilterUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;

class ImageHelper
{
    public static function getAttr($element)
    {
        $styleImage = '';

        $styleImage .= OpacityUnit::rander($element);
        $styleImage .= BoxSizeUnit::rander($element['boxSize']);
        $styleImage .= PositionUnit::rander($element['position'] );
        $styleImage .= FilterUnit::rander($element);


        return [
            'type' => 'img',
            'attr' => [
                'src' => $element['url'],
                'style' => $styleImage,
            ]
        ];
    }


/*************  ✨ Codeium Command ⭐  *************/
    /**
     * render
     *
     * @param array $element
     * @return string
     */
/******  97a3d67a-bdae-4044-a15f-5f84947c3db9  *******/
    public static function render($element)
    {
        $attrs = '';
        foreach ($element['attr'] as $key => $value) {
            $attrs .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }
        return '<img ' . trim($attrs) . '>';
    }

}
