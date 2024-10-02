<?php

namespace App\Units\Styles;

class ClipPathUnit
{
    public static function rander($props)
    {
        $style = '';

        if (isset($props['clipPath'])) {
            $dimensions = self::extractDimensions($props['clipPath']);
            $width = $dimensions['width'];
            $height = $dimensions['width'];
            $style .= 'clip-path: ' . self::convertToSquare($width, $height) . '; ';
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

    private static function convertToSquare($width, $height)
    {
        // شكل مربع باستخدام القيم المحددة
        return "path('M 0 0 L $width 0 L $width $height L 0 $height Z')";
    }
}
