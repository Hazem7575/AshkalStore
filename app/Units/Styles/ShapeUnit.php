<?php

namespace App\Units\Styles;

class ShapeUnit
{
    public static function rander($props)
    {
        if($props['shape'] == 'circle') {
            return 'clip-path: circle(50%);';
        }

//        if($props['shape'] == 'rectangle') {
//            $x = $props['boxSize']['x'];
//            $y = $props['boxSize']['y'];
//            $width = $props['boxSize']['width'];
//            $height = $props['boxSize']['height'];
//
//            return "clip-path: inset({$y}px {$width}px {$height}px {$x}px);";
//        }

        return '';
    }
}
