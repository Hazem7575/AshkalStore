<?php

namespace App\Units\Helpers;

class RenderElement
{
    public static function render($element)
    {
        return match ($element['type']) {
            'img'  => ImageHelper::render($element),
            'text' => TextHelper::render($element),
            'svg'  => SvgHelper::render($element),
            default => '',
        };
    }
}
