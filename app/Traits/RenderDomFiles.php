<?php

namespace App\Traits;

use App\Services\CssFormatter;
use Illuminate\Support\Facades\File;

trait RenderDomFiles
{
    public static $file_name_css = 'styles.css';

    public static function renderFileCss()
    {
        $path_css = self::$paths_dir['path_css'];
        if(!File::exists($path_css)) {
            File::makeDirectory($path_css, 0755, true);
        }

        self::RenderCollectionCss();


        $formatter = new CssFormatter();
        $cssFilePath = $path_css . self::$file_name_css;
        $formattedCss = $formatter->format(self::$templete_css);
        File::put(public_path($cssFilePath), $formattedCss);
        return new static();
    }

    public static function getUrlCss()
    {
        return asset(self::$paths_dir['path_css'] . self::$file_name_css);
    }
}
