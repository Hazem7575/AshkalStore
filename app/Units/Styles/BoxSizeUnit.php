<?php

namespace App\Units\Styles;

class BoxSizeUnit
{
    public static function rander($props)
    {
        $style = '';

        // Add width if set
        if (isset($props['width'])) {
            $style .= 'width: ' . $props['width'] . 'px; ';
        }

        if (isset($props['height'])) {
            $style .= 'height: ' . $props['height'] . 'px; ';
        }

        if (isset($props['x'])) {
            $style .= 'left: ' . $props['x'] . 'px; ';
        }

        if (isset($props['y'])) {
            $style .= 'top: ' . $props['y'] . 'px; ';
        }

        if (isset($props['x']) || isset($props['y'])) {
            $style .= 'position: absolute; ';
        }

        return $style;
    }

}
