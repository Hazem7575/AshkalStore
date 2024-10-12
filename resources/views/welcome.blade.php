<!doctype html>
<html lang="en" data-layer="{{$html['sizes']['width']}}">
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
        .layer-contianer {
            position: relative;
            overflow: hidden;
            display: grid;
            align-items: center;
            grid-template-columns: auto {{$html['sizes']['width'] / 16}}rem auto;
            z-index: 0;
        }
    </style>
</head>
<body>
{!! $html['html'] !!}

<script src="{{$html['js']}}"></script>
<script src="{{asset('test/js/grid.js')}}"></script>
<script>
   
</script>
</body>
</html>
