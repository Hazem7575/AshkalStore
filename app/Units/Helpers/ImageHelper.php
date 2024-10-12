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

class ImageHelper
{
    use RenderDomJS , RenderDomCss;
    public static function getAttr($element , $parent , $is_root = false)
    {
        $styleImage = '';

        $styleImage .= OpacityUnit::rander($element);
        $styleImage .= 'width: 100%;height: 100%;';
        //  $styleImage .= PositionUnit::rander($element['position']);
        $styleImage .= FilterUnit::rander($element);
        $styleImage .= ZindexUnit::rander(1);


        $style_render = Json2HtmlUnit::ImageStyleListen($styleImage , $element, $parent);

        if($is_root) {
            $styleImage .= 'width:100%;position: absolute;';

        }

        $parent_style =  BoxSizeUnit::rander($element['boxSize']);
        $parent_style .=  'position: relative;';
        if(isset($element['position'])) {
            $parent_style .=  'transform: translate('. $element['position']['x'] .'px, '. $element['position']['y'] .'px);';

        }



        return [
            'type' => 'img',
            'is_root' => $is_root,
            'parent' => [
                'style' => $parent_style
            ],
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
        $html = '<img ' . trim($attrs) . '>';
        if(isset($element['parent']) AND !$element['is_root']) {
            return '<div style="'. $element['parent']['style'] .'">' . $html . '</div>';
        }else{
            return  $html;
        }
    }

}
