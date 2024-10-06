<!doctype html>
<html lang="en" data-layer="1640">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{$html['css']}}">
    <title>Document</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
{!! $html['html'] !!}
<script src="{{$html['js']}}"></script>
<script src="{{asset('test/js/grid.js')}}"></script>
<script>
    const width = window.innerWidth;
    document.documentElement.style.setProperty('--width', `${width}px`);
    window.addEventListener('resize', () => {
        const width = window.innerWidth;
        document.documentElement.style.setProperty('--width', `${width}px`);
    });
    function updateGrid() {
        const layerSize = 1640; // العرض الأصلي للطبقة
        const screenWidth = window.innerWidth; // العرض الحالي للشاشة

        // حساب معامل التغيير
        const scalingFactor = screenWidth / layerSize;

        // قائمة لتخزين الأعمدة الجديدة
        const allNewGridCols = [];

        // العثور على العناصر الأب وتحديثها
        for (const id in sizesElements) {
            if (sizesElements[id].parent) {
                // العثور على العنصر الأب
                const parentElement = document.querySelector(`.${id}`);
                if (parentElement) {


                    const originalColumns = sizesElements[id].grid.split(' ');
                    console.log(originalColumns)
                    const newGridCols = originalColumns.map(column => {
                        const originalValue = parseFloat(column); // استخراج القيمة الأصلية
                        const newValue = originalValue * scalingFactor; // حساب القيمة الجديدة
                        return `${newValue}rem`; // إرجاع القيمة الجديدة بوحدة rem
                    });
                    parentElement.style.gridTemplateColumns = newGridCols.join(' ');
                    allNewGridCols.push(newGridCols);
                }
            }
        }

        // تحديث عرض العناصر داخل الشبكة
        for (const className in sizesElements) {
            const elementData = sizesElements[className];
            const originalWidth = parseFloat(elementData.width) || 0;
            const newWidth = originalWidth * scalingFactor;
            const element = document.querySelector(`.${className}`);
            if (element) {
                element.style.width = newWidth + 'px'; // تحديث عرض العنصر فقط
            }
        }
    }

    // استدعاء الدالة عند تحميل المحتوى
    window.addEventListener('DOMContentLoaded', () => {
        updateGrid();
    });

    // استدعاء الدالة عند تغيير حجم الشاشة
    window.addEventListener('resize', updateGrid);



</script>
</body>
</html>
