<?php

namespace App\Units\Helpers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class SvgHelper
{
    public static function getAttr($element)
    {
        // إنشاء خصائص SVG
        $style  = '';
        $style .= TransformUnit::rander($element);
        $style .= ColorUnit::rander($element, 'color');
        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= OpacityUnit::rander($element);
        $fillColor = $element['colors'][0] ?? 'none';

        return [
            'type' => 'svg',
            'attr' => [
                'width' => $element['boxSize']['width'],
                'height' => $element['boxSize']['height'],
                'style' => $style,
                'viewBox' => "0 0 {$element['boxSize']['width']} {$element['boxSize']['height']}",
                'transform' => "translate({$element['position']['x']}, {$element['position']['y']})"
            ],
            'fill' => $fillColor,
            'image' => $element['image']
        ];
    }

    public static function render($element)
    {
        // فك تشفير بيانات SVG من Base64
        $svgData = $element['image'];
        $decodedSvg = base64_decode(substr($svgData, strpos($svgData, ",") + 1)); // حذف header data:image/svg+xml;base64,
    
        // إعداد السمات
        $attrs = '';
        foreach ($element['attr'] as $key => $value) {
            $attrs .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }
    
        // دمج السمات مع كود SVG
        // نبحث عن بداية عنصر <svg> في كود SVG لفك وتطبيق السمات
        $svgElementWithAttributes = preg_replace('/<svg(.*?)>/', '<svg$1 ' . trim($attrs) . '>', $decodedSvg);
    
        // تطبيق لون fill على جميع العناصر الداخلية
        $fillColor = $element['fill']; // افتراض لون fill إذا لم يكن محددًا
        $svgElementWithFillColor = preg_replace('/(<(path|rect|circle|ellipse|polygon|line|polyline)([^>]*?))/', '$1 fill="' . htmlspecialchars($fillColor, ENT_QUOTES) . '"', $svgElementWithAttributes);
    
        // إرجاع الـ SVG مباشرة كـ HTML
        return $svgElementWithFillColor;
    }


}
