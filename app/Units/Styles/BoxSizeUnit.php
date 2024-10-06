<?php

namespace App\Units\Styles;

use App\Units\Helpers\SizeHelper;

class BoxSizeUnit
{
    public static function rander($props , $position = true , $resize = false , $sizes = [])
    {
        $style = '';

        if (isset($props['width'])) {
            $width = $props['width'] . 'px; ';
            if($resize) {
               // $width = SizeHelper::getWidth($props['width'] , $sizes).'%; ';
            }
            $style .= 'width: ' . $width;
        }

        if (isset($props['height'])) {

            $style .= 'height: ' . $props['height'] . 'px; ';
        }

        if($position) {
            if (isset($props['x'])) {
                $style .= 'left: ' . $props['x'] . 'px; ';
            }

            if (isset($props['y'])) {
                $style .= 'top: ' . $props['y'] . 'px; ';
            }

            if (isset($props['x']) || isset($props['y'])) {
                $style .= 'position: relative; ';
            }
        }



        return $style;
    }

}
