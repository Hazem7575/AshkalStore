<?php

namespace App\Units\Styles;

class ClipPathUnit
{
    public static function rander($props)
    {
        $style = '';

        if (isset($props['clipPath'])) {

            if(strpos($props['clipPath'], 'path') !== 0) {
                $style .="clip-path: path('{$props['clipPath']}');";
            }
            
            
            // $style .="width:".$props['boxSize']['width']."px;";
            // $style .="height:".$props['boxSize']['height']."px;";
            $style .="width:".$props['boxSize']['width']/$props['scale']."px;";
            $style .="height:".$props['boxSize']['height']/$props['scale']."px;";
            // $style .=TransformUnit::rander($props,true); 
            $style .="overflow:hidden;";
            $dimensions = self::extractDimensions($props['clipPath']);
            return $style;
            $width = $dimensions['width'];
            $height = $dimensions['width'];
            $style .= 'clip-path: ' . self::convertToSquare($width, $height) . '; ';
        }
        return $style;
    }
    public static function getClipPathStyle($props){
        $style = '';
        if(strpos($props['clipPath'], 'path') !== 0) {
            $style ="clip-path: path('{$props['clipPath']}');";
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