<?php

namespace App\Units\Helpers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\OpacityUnit;

class TextHelper
{
    public static function getAttr($element)
    {
        $scale = $element['scale'] ?? 1;
        $content = preg_replace_callback('/font-size:\s*(\d+)(px|em|rem)?;/', function ($matches) use ($scale) {
            $newFontSize = $matches[1] * $scale;

            return 'font-size: ' . $newFontSize . $matches[2] . ';';
        }, $element['text']);

        return [
            'type' => 'text',
            'content' => $content
        ];
    }


    public static function render($element)
    {
        return $element['content'];
    }

}
