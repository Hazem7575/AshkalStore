<?php

namespace App\Units\Styles;

class FontSizeUnit
{
    public static function rander($props)
    {
        $style = '';

        if(isset($props['fontSizes'])) {
            $style .= 'font-size:'.$props['fontSizes'][0].'px;';
        }

        return $style;
    }
}
