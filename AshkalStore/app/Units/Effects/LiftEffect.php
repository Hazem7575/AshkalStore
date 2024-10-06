<?php

namespace App\Units\Effects;

class LiftEffect
{
    public static function render($props)
    {
        $style = '';

        if (isset($props['effect'])) {
            // الحصول على إعدادات التأثير
            $intensity = $props['effect']['settings']['intensity'];
            // $color = isset($props['effect']['settings']['color']) ? $props['effect']['settings']['color'] : 'rgb(0, 0, 0)';
            $color = 'rgb(0, 0, 0)';

            // معالجة اللون لاستخراج قيم الـ RGB فقط
            if (strpos($color, 'rgb') !== false) {
                $color = preg_replace('/[^\d,]/', '', $color); 
            }

            // حساب التعتيم (alpha) بناءً على قيمة intensity
            $alpha = 0.0055 * $intensity;

            // حساب الضبابية (blur-radius) بناءً على قيمة intensity
            $blurRadius = 4.875 + (0.0625 * $intensity);

            // بناء خصائص text-shadow
            $style .= "text-shadow: rgba({$color}, {$alpha}) 0px 4.75px {$blurRadius}px;";
        }

        return $style;
    }
}
// "effect": {
//                         "name": "lift",
//                         "settings": {
//                             "intensity": 50,
//                             "color": "rgb(229, 112, 27)"
//                         }
//                     },