<?php 
namespace App\Units\Effects;

class SpliceEffect
{
    public static function render($props)
    {
        $style = '';

        if (isset($props['effect'])) {
            // الحصول على الإعدادات
            $thickness = $props['effect']['settings']['thickness'];
            $offset = $props['effect']['settings']['offset'];
            $direction = $props['effect']['settings']['direction'];
            $color = isset($props['effect']['settings']['color']) ? $props['effect']['settings']['color'] : 'rgb(0, 0, 0)';

            // تحويل الزاوية من درجات إلى راديان
            $angleRad = deg2rad($direction);

            // حساب text-stroke
            $fontSize = 100; // افتراض حجم الخط 100px، يمكنك تغييره حسب الحاجة
            $textStroke = (0.0091666 * $fontSize + 0.0008333 * ($fontSize * $thickness)) * 0.95004; // تطبيق معامل التحويل

            // حساب الإزاحة
            $x = round($offset * cos($angleRad) * 0.39077, 5); // تطبيق معامل التحويل
            $y = round($offset * sin($angleRad) * 0.07758, 5); // تطبيق معامل التحويل

            // بناء الخصائص
            $style .= "-webkit-text-stroke: {$textStroke}px {$color};";
            $style .= "-webkit-text-fill-color: transparent;";
            $style .= "text-shadow: {$color} {$x}px {$y}px 0px;";
        }

        return $style;
    }
}
