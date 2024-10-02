<?php

namespace App\Units\Styles;

class ClipPathUnit
{
    public static function rander($props)
    {
        $style = '';

        if (isset($props['clipPath'])) {
            // لا داعي لحساب العرض والارتفاع في هذه الحالة لأننا نستخدم الـ clipPath كما هو
            $style .= 'clip-path: path(' . "'".$props['clipPath']."'" . '); ';
        }
        return $style;
    }

    private static function extractDimensions($clipPath)
    {
        // استخدم تعبيرات عادية لاستخراج النقاط (x و y) من الـ clipPath
        preg_match_all('/M\s*(\d+)\s*(\d+)|[L|C|S]\s*(\d+)\s*(\d+)/', $clipPath, $matches);

        $points = [];

        foreach ($matches[0] as $match) {
            preg_match('/(\d+)\s*(\d+)/', $match, $point);
            if (!empty($point)) {
                $points[] = ['x' => (int)$point[1], 'y' => (int)$point[2]];
            }
        }

        // حساب العرض والارتفاع بناءً على النقاط المستخرجة
        $width = max(array_column($points, 'x'));
        $height = max(array_column($points, 'y'));

        return ['width' => $width, 'height' => $height];
    }
}