<?php

namespace App\Units\Helpers;

use App\Traits\RenderDomCss;
use App\Traits\RenderDomJS;
use App\Units\Json2HtmlUnit;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\FilterUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;

class BackgroundHelper
{
    use RenderDomJS , RenderDomCss;
    public static function getAttr($color)
    {
        return [
            'type' => 'background',
            'attr' => [
                'style' => 'background:' . $color.';width: 100%;height: 100%;',
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

        return '<div  style="grid-area:1 / 1 / 2 / 4;display:grid;position:absolute;min-height:100%;min-width:100%;">
            <div style="z-index:0;">
                <div  style="box-sizing:border-box;width:100%;height:100%;transform:rotate(0deg);">
                    <div style="width:100%;height:100%;opacity:1.0;">
                        <div  ' . trim($attrs) . '></div>
                    </div>
                </div>
            </div>
        </div>';
    }

}
