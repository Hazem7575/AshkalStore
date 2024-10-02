<?php

namespace App\Units\Styles;

class OpacityUnit
{
    public static function rander($props)
    {
        $style = '';
        if (isset($props['transparency'])) {
            $style .= 'opacity: ' . $props['transparency'] . '; ';
        }
        return $style;
    }
}
