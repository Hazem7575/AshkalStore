<?php

namespace App\Units\Styles;

class TransformUnit
{
    public static function rander($props)
    {
        $style = '';

        if(isset($props['rotate'])) {
            $transform[] = 'rotate(' . $props['rotate'] . 'deg)';
        }
        if(isset($props['scale'])) {
            $transform[] = 'scale(' . $props['scale'] . ')';
        }

        if (!empty($transform)) {
            $style .= 'transform: ' . implode(' ', $transform) . '; ';
        }
        return $style;
    }
}
