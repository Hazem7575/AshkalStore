<?php

namespace App\Units\Styles;

class PositionUnit
{
    public static function rander($props)
    {
        $style = '';
        if(isset($props['x'])) {
            if($props['x']<0){
                $style .= 'left: ' . $props['x'] . 'px; ';
            }
            else{
                $style .= 'left: ' . 0 . 'px; ';
            }
            // $style .= 'left: ' . $props['x'] . 'px; ';
        }
        if(isset($props['y'])) {
            if($props['y']<0){
                $style .= 'top: ' . $props['y'] . 'px; ';
            }
            else{
                $style .= 'top: ' . 0 . 'px; ';
            }
            // $style .= 'top: ' . $props['y'] . 'px; ';
        }
        $style .= 'position: absolute;';
        return $style;
    }
}
