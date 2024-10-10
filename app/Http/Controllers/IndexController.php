<?php

namespace App\Http\Controllers;

use App\Units\Json2HtmlUnit;
use App\Units\ResponsiveUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class IndexController extends Controller
{
    public function index()
    {
        $file = File::get(public_path('10.json'));
        $json = json_decode($file, true);
      //  dd((new ResponsiveUnit())->new_size($file , 400,1640));
        $html = Json2HtmlUnit::convert($json);

        return view('welcome', compact('html'));
    }

    public function grid()
    {
        return view('grid');
    }
}
