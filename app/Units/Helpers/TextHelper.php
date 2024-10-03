<?php

namespace App\Units\Helpers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\OpacityUnit;

class TextHelper
{
    public static function getAttr($element)
    {
        // الحصول على قيمة scale
        $scale = $element['scale'] ?? 1;

        // استخدام تعبير منتظم للعثور على font-size في النص واستبداله
        $content = preg_replace_callback('/font-size:\s*(\d+)(px|em|rem)?;/', function ($matches) use ($scale) {
            // حساب القيمة الجديدة للـ font-size
            $newFontSize = $matches[1] * $scale;
            // إعادة النص مع القيمة الجديدة
            return 'font-size: ' . $newFontSize . $matches[2] . ';';
        }, $element['text']);

        return [
            'type' => 'text',
            'content' => $content
        ];
    }


    public static function render($element)
    {
        return $element['content'];
    }

}
