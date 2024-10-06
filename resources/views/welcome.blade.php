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
            overflow-x: hidden;
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
        const screenWidth = window.innerWidth;
        const mobileBreakpoint = 768;

        // العثور على العناصر الأب وتحديثها
        for (const id in sizesElements) {
            if (sizesElements[id].grid) {
                const parentElement = document.querySelector(`.${id}`);
                if (parentElement) {
                    if (screenWidth < mobileBreakpoint) {
                        // إذا كانت الشاشة أصغر من 768 بكسل، تغيير تخطيط الـ Grid إلى عمود واحد
                        parentElement.style.gridTemplateColumns = '1fr'; // عمود واحد
                    } else {
                        // حساب معامل التغيير
                        const scalingFactor = screenWidth / layerSize;

                        const originalColumns = sizesElements[id].grid.split(' ');
                        const newGridCols = originalColumns.map(column => {
                            const originalValue = parseFloat(column);
                            const newValue = originalValue * scalingFactor;
                            return `${newValue}rem`;
                        });
                        parentElement.style.gridTemplateColumns = newGridCols.join(' ');
                    }
                }
            }
        }

        // تحديث عرض العناصر
        for (const className in sizesElements) {
            const elementData = sizesElements[className];
            const originalWidth = parseFloat(elementData.width) || 0;
            const newWidth = originalWidth * (screenWidth < mobileBreakpoint ? 1 : (screenWidth / layerSize));
            const element = document.querySelector(`.${className}`);
            if (element) {
                element.style.width = newWidth + 'px';
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
