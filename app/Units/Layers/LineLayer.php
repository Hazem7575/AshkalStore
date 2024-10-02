<?php

namespace App\Units\Layers;

use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class LineLayer
{
    public static function rander($element)
    {
        $style = '';

        // إعداد بيانات الطبقة
        $data = [
            'children' => []
        ];

        // تطبيق الأنماط المختلفة
        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element);
        $style .= ColorUnit::rander($element, 'background'); // يمكن أن يكون لون الخط
        $style .= OpacityUnit::rander($element);

        // إذا كان هناك خاصية 'strokeWidth' لتحديد عرض الخط
        if (isset($element['strokeWidth'])) {
            $style .= 'stroke-width: ' . $element['strokeWidth'] . 'px; ';
        }

        // إذا كان هناك خاصية 'style' لتحديد نمط الخط (مثل dashed أو solid)
        if (isset($element['style'])) {
            $style .= 'stroke-dasharray: ' . ($element['style'] === 'dashed' ? '5, 5' : '0') . '; ';
        }

        // إضافة الخصائص إلى البيانات
        $data['style'] = $style;
        return $data;
    }
}
