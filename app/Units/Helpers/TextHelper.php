<?php

namespace App\Units\Helpers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\OpacityUnit;

class TextHelper
{
    public static function getAttr($element)
    {
        return [
            'type' => 'text',
            'content' => $element['text']
        ];
    }


    public static function render($element)
    {
        return $element['content'];
    }

}
