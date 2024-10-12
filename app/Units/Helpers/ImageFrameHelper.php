<?php

namespace App\Units\Helpers;

use App\Traits\RenderDomCss;
use App\Traits\RenderDomJS;
use App\Units\Json2HtmlUnit;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\FilterUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\ZindexUnit;

class ImageFrameHelper
{
    use RenderDomJS , RenderDomCss;
    public static function getAttr($element , $parent , $is_root = false)
    {
        $styleImage = '';

        $styleImage .= OpacityUnit::rander($element);
        $styleImage .= BoxSizeUnit::rander($element['boxSize']);
        $styleImage .= PositionUnit::rander($element['position']);
        $styleImage .= FilterUnit::rander($element);
        $styleImage .= ZindexUnit::rander(1);

        $style_render = Json2HtmlUnit::ImageStyleListen($styleImage , $element, $parent);
        if(isset($element['flipHorizontal'])) {
            $styleImage .= 'transform: scaleX(-1);';
        };
        if($is_root) {
            $styleImage .= 'width:100%';

        }

        return [
            'type' => 'imgFrame',
            'attr' => [
                'class' => $style_render['class'],
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
