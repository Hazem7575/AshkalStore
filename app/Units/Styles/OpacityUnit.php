<?php

namespace App\Units\Styles;

class OpacityUnit
{
    public static function rander($props)
    {
        $style = '';
        if (isset($props['transparency'])) {
            $style .= 'opacity: ' . (1 - $props['transparency']) . '; ';
        }
        return $style;
    }
}
