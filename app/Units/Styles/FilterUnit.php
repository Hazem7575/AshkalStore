<?php

namespace App\Units\Styles;

class FilterUnit
{
    public static function rander($props)
    {
        $style = '';

        $filter = [];
//        if (isset($props['flipHorizontal'])) {
//            $style .= 'transform: scaleX(-1);';
//        }
        if (isset($props['hueRotate']) AND $props['hueRotate'] != 0) {
            $filter[] = 'hue-rotate(' . $props['hueRotate'] . 'deg)';
        }
        if (isset($props['hueRotate']) AND $props['grayscale'] != 0) {
            $filter[] = 'grayscale(' . $props['grayscale'] . '%)';
        }
        if (isset($props['blur']) AND $props['blur'] != 0) {
            $filter[] = 'blur(' . $props['blur'] . 'px)';
        }

        if (!empty($filter)) {
            $style .= 'filter: ' . implode(' ', $filter) . ';';
        }

        return $style;
    }

}
