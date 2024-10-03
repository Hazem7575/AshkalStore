<?php

namespace App\Units\Effects;

class ShadowEffect
{
    public static function render($props)
    {
        $style = '';

        if (isset($props['effect'])) {
            // الحصول على الإعدادات
            $offset = $props['effect']['settings']['offset'];
            $direction = $props['effect']['settings']['direction'];
            $blur = isset($props['effect']['settings']['blur']) ? $props['effect']['settings']['blur'] : 0;
            $transparency = $props['effect']['settings']['transparency'] / 100;
            $color = isset($props['effect']['settings']['color']) ? $props['effect']['settings']['color'] : $props['colors'][0];

            // معالجة اللون لاستخلاص قيم الـ RGB فقط
            if (strpos($color, 'rgb') !== false) {
                $color = preg_replace('/[^\d,]/', '', $color); 
            }

            // تحويل الاتجاه إلى راديان
            $directionRadians = deg2rad($direction);

            // حساب الإزاحة مع معامل التصحيح
            $correctionFactor = 5.75;
            $xOffset = ($offset * cos($directionRadians)) / $correctionFactor;
            $yOffset = ($offset * sin($directionRadians)) / $correctionFactor;

            // بناء الـ text-shadow
            $style .= "text-shadow: rgba({$color}, {$transparency}) {$yOffset}px {$xOffset}px {$blur}px;";
        }

        return $style;
    }
}

// "effect": {
//     "name": "shadow",
//     "settings": {
//         "offset": 50,
//         "direction": 45,
//         "blur": 0,
//         "transparency": 40,
//         "color": "rgb(229, 112, 27)"
//     }
// },
//text-shadow:rgba(229, 112, 27, 0.4) 6.15774px 6.15774px 0px;