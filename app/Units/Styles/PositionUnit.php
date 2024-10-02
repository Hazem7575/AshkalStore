<?php

namespace App\Units\Styles;

class PositionUnit
{
    public static function rander($props)
    {
        $style = '';
        if(isset($props['x'])) {
            $style .= 'left: ' . $props['x'] . 'px; ';
        }
        if(isset($props['y'])) {
            $style .= 'top: ' . $props['y'] . 'px; ';
        }
        $style .= 'position: absolute;';
        return $style;
    }
}
