<?php
namespace App\Units\Effects;

class EchoEffect
{
    public static function render($props)
    {
        $style = '';

        if (isset($props['effect'])) {
            // الحصول على الإعدادات
            $offset = $props['effect']['settings']['offset'];
            $direction = $props['effect']['settings']['direction'];
            $color = isset($props['effect']['settings']['color']) ? $props['effect']['settings']['color'] : 'rgb(0, 0, 0)';

            // معالجة اللون لاستخراج قيم الـ RGB فقط
            if (strpos($color, 'rgb') !== false) {
                $color = preg_replace('/[^\d,]/', '', $color); 
            }

            // تحويل الزاوية من درجات إلى راديان
            $angleRad = deg2rad($direction);

            // معامل التصحيح
            $correctionFactor = 6.2; // يمكنك تعديل هذا القيمة حسب الحاجة

            // حساب الإزاحة للظل الأول
            $x1 = round(($offset * cos($angleRad)) / $correctionFactor, 5);
            $y1 = round(($offset * sin($angleRad)) / $correctionFactor, 5);

            // حساب الإزاحة للظل الثاني
            $x2 = round(2 * $x1, 5);
            $y2 = round(2 * $y1, 5);

            // بناء خصائص text-shadow للظل الأول والثاني
            $style .= "text-shadow: rgba({$color}, 0.5) {$x1}px {$y1}px 0px, rgba({$color}, 0.3) {$x2}px {$y2}px 0px;";
        }

        return $style;
    }
}

