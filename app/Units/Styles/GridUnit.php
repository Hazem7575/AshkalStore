<?php

namespace App\Units\Styles;

use App\Traits\RenderDomCss;

class GridUnit
{
    use RenderDomCss;

    public static $childColumns = [];
    public static $childRows = [];
    public static $currentElement;
    public static $grid = [
        'columns' => [],
        'rows' => [],
        'grid' => [],
    ];

    public static function rander(&$element, &$collection){

        $children = [];
        foreach ($element['child'] as $key) {
            $children[$key] = &$collection[$key];
        }
        $style = static::buildGrid($element, $collection);

        $children =static::sortCollection($element, $collection);
        foreach (static::$mediaQuery as $key => $media) {
            $maxWidth = $media['maxWidth'];
            $propsName = $media['propsName'];
            $element[$propsName] =  $element['props'];
            $element[$propsName]['boxSize']['width'] = $maxWidth;

            $res = self::randerMedia($element, $children,$media);
            $element[$media['propsName']]['grid_template'] = $res['style'];
            $children = $res['collection'];
        }
        foreach ($children as $key => $child) {
            $collection[$key] = $child;
        }
        $element['props']['grid_template'] = $style;
        return $style;
    }

    public static function buildGrid($element, &$children,$media = null) {

        static::$childColumns = [];
        static::$childRows = [];
        static::$grid = [
            'columns' => [],
            'rows' => [],
            'grid' => [],
        ];

        static::$currentElement = $element;

        $style = '';


        if (!empty($element['child'])) {
            // if($media&&$media['propsName']=='props_iphon'){
            //     dd($media,$children,static::$childColumns,static::$childRows,static::$grid);
            // }
            foreach ($element['child'] as $childName) {
                $childElement = $children[$childName];
                self::processChildElement($childElement, $element, $childName,$media);
            }

            $style .= self::buildGridColumn($media);
            $style .= self::buildGridRow($element,$media);
            $style .= "display:grid;";

            self::applyGridPositionsToChildren($children, static::$childColumns, 'column',$media);
            self::applyGridPositionsToChildren($children, static::$childRows, 'row',$media);
        }
        // if($media){
        //     dd('buildGrid',$style,static::$grid,static::$childColumns,static::$childRows);
        // }

        return $style;

    }

    public static function sortCollection($element, &$collection){

        $children = [];
        foreach ($element['child'] as $childName) {
            $children[$childName] = &$collection[$childName];
        }

        // Sort the collection and set up the grid structure
        $collectionCopy = &$children;
        $alreadySorted = [];
        $sortedCollection = [];
        while (!empty($collectionCopy)) {
            $minSum = INF;
            $minColumn = INF;
            $minElement = null;
            foreach ($collectionCopy as $key => $elementObject) {
                if ($elementObject['type']['resolvedName'] == 'RootLayer') continue;

                if (in_array($key, $alreadySorted)) {
                    continue;
                }
                if (empty($elementObject['props']['grid']) || empty($elementObject['props']['grid']['column-start']) || empty($elementObject['props']['grid']['row-start'])) {
                    continue;
                }
                $sum = $elementObject['props']['grid']['column-start'] + $elementObject['props']['grid']['row-start'];
                if ($sum < $minSum || ($sum === $minSum && $elementObject['props']['grid']['column-start'] < $minColumn)) {
                    $minSum = $sum;
                    $minColumn = $elementObject['props']['grid']['column-start'];
                    $minElement = $elementObject;
                    $minElementKey = $key;
                }
            }
            if (empty($minElement)) {
                break;
            }
            $alreadySorted[] = $minElementKey;
            $sortedCollection[$minElementKey] = $minElement;
            unset($collectionCopy[$minElementKey]);
            $intersects = [];
            foreach ($collectionCopy as $key => $item) {
                if ($item['type']['resolvedName'] == 'RootLayer') continue;

                if (empty($item['props']['grid']) || empty($item['props']['grid']['column-start']) || empty($item['props']['grid']['row-start'])) {
                    continue;
                }

                if (($item['props']['grid']['row-start'] >= $minElement['props']['grid']['row-start'] && $item['props']['grid']['row-start'] < $minElement['props']['grid']['row-end']) ||
                    ($item['props']['grid']['row-end'] > $minElement['props']['grid']['row-start'] && $item['props']['grid']['row-end'] <= $minElement['props']['grid']['row-end'])
                ) {
                    $intersects[$key] = $item;
                    //     if ($item['props']['grid']['column-start'] >= $minElement['props']['grid']['column-start'] && $item['props']['grid']['column-end'] <= $minElement['props']['grid']['column-end']) {

                    // }
                }
            }
            if (!empty($intersects)) {
                uasort($intersects, function ($a, $b) {
                    $sumA = $a['props']['grid']['column-start'] + $a['props']['grid']['row-start'];
                    $sumB = $b['props']['grid']['column-start'] + $b['props']['grid']['row-start'];
                    $columnSort = $a['props']['grid']['column-start'] <=> $b['props']['grid']['column-start'];
                    return ($sumA < $sumB || ($sumA === $sumB && $columnSort < 0)) ? -1 : 1;
                });
                foreach ($intersects as $key => $item) {
                    $sortedCollection[$key] = $item;
                    unset($collectionCopy[$key]);
                }
            }

        }

        // $collection = $sortedCollection;

        // Initialize grid with null values
        foreach (self::$grid['rows'] as $rowNum => $value) {
            if (!isset(self::$grid['grid'][$rowNum])) {
                self::$grid['grid'][$rowNum] = [];
            }
            foreach (self::$grid['columns'] as $colNum => $value) {
                self::$grid['grid'][$rowNum][$colNum] = null;
            }
        }

        // Fill the grid with elements
        foreach ($sortedCollection as $key => $item) {
            if (isset($item['props']['grid']) && isset($item['props']['grid']['row-start'])) {
                $columnStart = $item['props']['grid']['column-start'];
                $columnEnd = $item['props']['grid']['column-end'];
                $rowStart = $item['props']['grid']['row-start'];
                $rowEnd = $item['props']['grid']['row-end'];
                for ($i = $columnStart; $i < $columnEnd; $i++) {
                    for ($j = $rowStart; $j < $rowEnd; $j++) {
                        self::$grid['grid'][$j][$i] = 'e';
                    }
                }
            }
        }
        foreach ($sortedCollection as $key => &$item) {
            $item['key'] = $key;
        }
        return $sortedCollection;
    }



    public static function randerMedia($element, $collection,$media = null)
    {
        $localCollection  = $collection;

        $maxWidth = $media['maxWidth'];
        $propsName = $media['propsName'];
        $res = self::generateGrid($element,$localCollection,$maxWidth,$propsName);
        $element = $res['contiainerElement'];
        $localCollection=$res['collection'];
        $responsiveGrid = $res['responsiveGrid'];
        $style=  self::buildGrid($element,$localCollection,$media);

        foreach ($localCollection as $key => $item) {
            $collection[$key] = $item;
        }

        return [
            'style'=>$style,
            'collection'=>$collection
        ];


    }

    private static function processChildElement($childElement, $parentElement, $childName,$media = null)
    {
        $propsName = $media['propsName']??'props';
        static::$childColumns[] = [
            'x' => min($childElement[$propsName]['position']['x'], $parentElement[$propsName]['boxSize']['width']),
            'id' => $childName,
            'type' => 'column-start'
        ];
        static::$childColumns[] = [
            'x' => min($childElement[$propsName]['position']['x'] + $childElement[$propsName]['boxSize']['width'], $parentElement[$propsName]['boxSize']['width']),
            'id' => $childName,
            'type' => 'column-end'
        ];
        static::$childRows[] = [
            'y' => min($childElement[$propsName]['position']['y'], $parentElement[$propsName]['boxSize']['height']),
            'id' => $childName,
            'type' => 'row-start'
        ];
        static::$childRows[] = [
            'y' => min($childElement[$propsName]['position']['y'] + $childElement[$propsName]['boxSize']['height'], $parentElement[$propsName]['boxSize']['height']),
            'id' => $childName,
            'type' => 'row-end'
        ];
    }

    private static function buildGridColumn($media)
    {
        $propsName = $media['propsName']??'props';
        usort(static::$childColumns, fn($a, $b) => $a['x'] <=> $b['x']);
        self::adjustNegativeValues(static::$childColumns, 'x');

        $columns = [];
        $style = '';

        foreach (static::$childColumns as $key => $child) {
            $value = ($key === 0) ? $child['x'] : $child['x'] - static::$childColumns[$key - 1]['x'];
            $columns[] = $value;
            static::$childColumns[$key]['column'] = count($columns) + 1;
            static::$grid['columns'][count($columns)] = $value;
            $style .= $value / 16 . "rem ";
        }

        $value = static::$currentElement[$propsName]['boxSize']['width'] - array_sum($columns);
        $columns[] = $value;
        static::$grid['columns'][count($columns)] = $value;
        $style .= $value / 16 . "rem ";

        return 'grid-template-columns: ' . trim($style) . ';';
    }

    private static function buildGridRow($contiainerElement,$media)
    {

        $propsName = $media['propsName']??'props';
        $maxWidth = $contiainerElement[$propsName]['boxSize']['width'];
        usort(static::$childRows, fn($a, $b) => $a['y'] <=> $b['y']);
        self::adjustNegativeValues(static::$childRows, 'y');

        $rows = [];
        $style = '';

        foreach (static::$childRows as $key => $child) {
            $value = ($key === 0) ? $child['y'] : $child['y'] - static::$childRows[$key - 1]['y'];
            $rows[] = $value;
            static::$childRows[$key]['row'] = count($rows) + 1;
            static::$grid['rows'][count($rows)] = $value;

            if ($value > $maxWidth) {
                $value = $maxWidth;
            }

            if ($value) {
                $style .= 'minmax(' . ($value / 16) . "rem,max-content) ";
            } else {
                $style .= 0 . " ";
            }
        }

        $value = static::$currentElement[$propsName]['boxSize']['height'] - array_sum($rows);
        $rows[] = $value;
        static::$grid['rows'][count($rows)] = $value;
        static::$grid['rows'][count($rows) + 1] = $value;
        $style .= $value / 16 . "rem ";

        return 'grid-template-rows: ' . trim($style) . ';';
    }

    private static function applyGridPositionsToChildren(&$collection, $children, $type,$media)
    {
        $propsName = $media['propsName']??'props';
        foreach ($children as $child) {
            $childElement = $collection[$child['id']];
            $collection[$child['id']][$propsName]['grid'][$child['type']] = $child[$type] ?? null;
        }
    }

    private static function adjustNegativeValues(&$elements, $key)
    {
        foreach ($elements as $index => $value) {
            if ($value[$key] < 0) {
                $elements[$index][$key] = 0;
            }
        }
    }
    private static function generateGrid($contiainerElement,$collection,$maxWidth,$propsName)
    {

        $targetWidth = $maxWidth; // العرض المستهدف
        $responsiveGrid = [];
        $currentRowWidth = 0;
        $currentRow = [];
        $rowIndex = 1;
        $originalWidth = $contiainerElement['props']['boxSize']['width'];
        // Initialize arrays to hold column widths and row heights
        $columnWidths = [];
        $rowHeights = [];
        foreach ($collection as $key => &$element) {


            $elementWidth = $element['props']['boxSize']['width'];
            $elementHeight = $element['props']['boxSize']['height'];
            $normalizedWidth = min($elementWidth, $targetWidth); // تطبيع العرض

            // step 1: التحقق من إمكانية بقاء العنصر في الصف الحالي
            $canStayInCurrentRow = false;

            // إذا كان عرض العنصر أصغر من العرض المتاح في الصف
            if ($currentRowWidth + $normalizedWidth <= $targetWidth) {

                if (!empty($currentRow)) {
                    // تحقق إذا كان هناك عنصر سابق في الصف الحالي
                    $previousElementInRow = $currentRow[count($currentRow) - 1];

                    $previousElementWidth = $previousElementInRow['props']['boxSize']['width'];
                    //check if current element interect with previous element in rows-start and row-end
                    // حساب المسافة بين العناصر
                    // $originalDistance = ($previousElement['props']['boxSize']['x'] + $previousElementWidth) - $element['props']['boxSize']['x'];
                    $originalDistance =  ($previousElementInRow['props']['boxSize']['x'] + $previousElementWidth) - $element['props']['boxSize']['x'];
                    $scaledDistance = ($originalDistance / $originalWidth) * $targetWidth;
                    // تحقق إذا كانت المسافة المقطوعة بين العنصر الحالي والعنصر التالي
                    if ($currentRowWidth + $normalizedWidth + $scaledDistance <= $targetWidth) {
                        $canStayInCurrentRow = true;
                    }
                } else {
                    // لا يوجد عنصر سابق في الصف الحالي
                    $canStayInCurrentRow = true;
                }
            }
            if ($canStayInCurrentRow) {
                // العنصر يمكن أن يبقى في الصف الحالي
                $currentRow[] = &$element; // تمرير العنصر عبر المرجع
                $currentRowWidth += $normalizedWidth;

                // تحديث ارتفاع الصف الحالي
                if (!isset($rowHeights[$rowIndex])) {
                    $rowHeights[$rowIndex] = $elementHeight; // أول عنصر في الصف
                } else {
                    $rowHeights[$rowIndex] = max($rowHeights[$rowIndex], $elementHeight); // تحديث الارتفاع
                }
            } else {
                self::proccessAppendRow($currentRow, $rowHeights, $rowIndex, $targetWidth, $element, $normalizedWidth, $elementHeight,$responsiveGrid,$propsName,$originalWidth);
            }
        }

        //  التعامل مع الصف الأخير
        if (!empty($currentRow)) {
            self::proccessAppendRow($currentRow, $rowHeights, $rowIndex, $targetWidth, $element, $normalizedWidth, $elementHeight,$responsiveGrid,$propsName,$originalWidth);
        }
        // حفظ عرض الأعمدة وارتفاع الصفوف للاستخدام لاحقًا
        self::$grid['columns'] = $columnWidths;
        self::$grid['rows'] = $rowHeights;
        return [
            'responsiveGrid'=>$responsiveGrid,
            'contiainerElement'=>$contiainerElement,
            'collection'=>$collection,
        ];
    }
    private static function proccessAppendRow(&$currentRow, &$rowHeights,&$rowIndex,&$targetWidth,&$element,&$normalizedWidth,&$elementHeight,&$responsiveGrid,$propsName,$originalWidth) {

        // step 2: التعامل مع كل صف
        // إذا كان الصف يحتوي على عنصر واحد
        if (count($currentRow) === 1) {

            $currentRow[0][$propsName] = $currentRow[0]['props'];
            $left = ($targetWidth - $currentRow[0][$propsName]['boxSize']['width']) / 2;
            $currentRow[0][$propsName]['position']['x'] = $left;
            $currentRow[0][$propsName]['boxSize']['x'] = $left;
            $top = ($rowHeights[$rowIndex] - $currentRow[0][$propsName]['boxSize']['height']) / 2;
            $currentRow[0][$propsName]['position']['y'] = $top;
            $currentRow[0][$propsName]['boxSize']['y'] = $top;
        } else {

            // // إذا كان هناك أكثر من عنصر في الصف
            // $columnIndex = 1;
            $previousElement = null;
            $currentRowWidth = 0;
            foreach ($currentRow as &$rowElement) {

                //first element
                $rowElement[$propsName] = $rowElement['props'];
                if (!$previousElement) {
                    $previousElement = &$rowElement;
                    $left = 0;
                    $rowElement[$propsName]['position']['x'] = $left;
                    $rowElement[$propsName]['boxSize']['x'] = $left;

                    $top = ($rowHeights[$rowIndex] - $rowElement[$propsName]['boxSize']['height']) / 2;
                    $rowElement[$propsName]['position']['y'] = $top;
                    $rowElement[$propsName]['boxSize']['y'] = $top;

                    $currentRowWidth = $rowElement[$propsName]['boxSize']['width'];
                } else {
                    $previousElementWidth = $previousElement['props']['boxSize']['width'];
                    $originalDistance =  ($previousElement['props']['boxSize']['x'] + $previousElementWidth) - $rowElement['props']['boxSize']['x'];
                    $scaledDistance = ($originalDistance / $originalWidth) * $targetWidth;
                    $rowElement[$propsName]['position']['x'] = $currentRowWidth + $scaledDistance;
                    $currentRowWidth += $scaledDistance + $rowElement[$propsName]['boxSize']['width'];

                    $top = ($rowHeights[$rowIndex] - $rowElement[$propsName]['boxSize']['height']) / 2;
                    $rowElement[$propsName]['position']['y'] = $top;
                    $rowElement[$propsName]['boxSize']['y'] = $top;
                }
            }
            if ($currentRowWidth < $targetWidth) {
                $leftResult = ($targetWidth - $currentRowWidth) / 2;

                foreach ($currentRow as &$rowElement) {
                    $rowElement[$propsName]['position']['x'] += $leftResult;
                    $rowElement[$propsName]['boxSize']['x'] += $leftResult;
                }
            }
        }

        // الانتقال إلى الصف التالي
        $responsiveGrid[] = $currentRow;
        $currentRow = [&$element]; // بدء صف جديد بالعنصر الحالي

        $currentRowWidth = $normalizedWidth;
        $rowIndex++;

        // تحديث ارتفاع الصف الحالي
        if (!isset($rowHeights[$rowIndex])) {
            $rowHeights[$rowIndex] = $elementHeight; // أول عنصر في الصف
        } else {
            $rowHeights[$rowIndex] = max($rowHeights[$rowIndex], $elementHeight); // تحديث الارتفاع
        }
    }



}
