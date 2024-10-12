<?php

namespace App\Traits;

use App\Services\CssFormatter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait RenderDomCss
{
    public static $collection_css;
    public static $render_css_collection;
    public static $mediaQuery = [
        [
            'max-width' => '375px',
        ],
        [
            'min-width' => '375.05px',
            'max-width' => '480px',
        ],
        [
            'min-width' => '480.05px',
            'max-width' => '768px',
        ],
        [
            'min-width' => '768.05px',
            'max-width' => '1024px',
        ],
        [
            'min-width' => '1024.05px',
        ]
    ];

    public static function css_name($style, $prefix = null, $name = null, $is_class = true)
    {
        if (is_null($name)) {
            $name = 'store_' . Str::random(8);
        }

        $name = $prefix . $name;


        self::$collection_css[$name] = [
            'is_class' => $is_class,
            'style' => $style,
        ];

        return $name;
    }

    public static function FirstRenderCss()
    {
        self::$render_css_collection = '
                    @media (prefers-reduced-motion: reduce) {
                        .animated {
                            animation: none !important;
                        }
                    }
                    :root {
                       --layer-size: '. self::$size_layer['width'] .'px;

                    }
                    .layer-contianer  {
                       width: calc((536.30099955744 / var(--layer-size)) * 100vw);
                    }
                    html {
                        -webkit-text-size-adjust: 100%;
                        scroll-behavior: smooth;
                    }
                    body,
                    html,
                    p,
                    ul,
                    ol,
                    li {
                        margin: 0;
                        padding: 0;
                        font-synthesis: none;
                        font-kerning: none;
                        font-variant-ligatures: none;
                        font-feature-settings: "kern" 0, "calt" 0, "liga" 0, "clig" 0, "dlig" 0, "hlig" 0;
                        font-family: unset;
                        -webkit-font-smoothing: subpixel-antialiased;
                        -moz-osx-font-smoothing: grayscale;
                        text-rendering: geometricprecision;
                        white-space: normal;
                    }

                    li {
                        text-align: unset;
                    }

                    a {
                        text-decoration: none;
                        color: inherit;
                    }

                    img {
                        -webkit-user-drag: none;
                        -moz-user-drag: none;
                        -o-user-drag: none;
                        user-drag: none;
                        -webkit-touch-callout: none;
                    }




                ';

    }

    public static function RenderCollectionCss()
    {
        self::FirstRenderCss();
        self::RenderCssMediaQuery();
        self::$templete_css .= self::$render_css_collection;
    }

    public static function RenderCssMediaQuery()
    {
       foreach (self::$mediaQuery as $mediaQuery) {
           $html_media = '';
           if(isset($mediaQuery['min-width'])) {
               $html_media .= '(min-width: ' . $mediaQuery['min-width'] . ')';
           }
           if(isset($mediaQuery['max-width'])) {
               if(isset($mediaQuery['min-width'])) {
                   $html_media .= ' and ';
               }
               $html_media .= '(max-width: ' . $mediaQuery['max-width'] . ')';
           }
           self::$render_css_collection .= '@media '. $html_media .' {';
           self::$render_css_collection .= self::ResponsiveCss($mediaQuery['min-width'] ?? null , $mediaQuery['max-width'] ?? null);
           self::$render_css_collection .= '}';

       }
    }

    public static function ResponsiveCss($min = null, $max = null)
    {
        $styles = '';
        if(is_array(self::$collection_css) AND count(self::$collection_css) > 0) {
            foreach (self::$collection_css as $key => $collect) {
                $prefix = '.';
                if (!$collect['is_class']) {
                    $prefix = '#';
                }

                $style_media = self::RenderMediaCss($collect['style'] ,  $max);
                $styles .= $prefix . $key . '{' . $style_media . '}';
            }
        }

        return $styles;
    }

    public static function RenderMediaCss($style , $max = null) {
        if(in_array($max , ['375px' , '480px'])) {
            return self::MobileCss($style , $max);
        }
        return $style;
    }


    public static function MobileCss($style, $maxPercentage) {
        $styleArray = explode(';', $style);
        $maxPercentage = str_replace('px', '', $maxPercentage);
        foreach ($styleArray as $key => $value) {
            $value = trim($value);

            if (preg_match('/(width|margin|padding|top|left|right|bottom):\s*(\d+)(px|rem)/', $value, $matches)) {

                $property = $matches[1]; // الخاصية مثل width أو height
                $originalValue = $matches[2]; // القيمة الأصلية
                $unit = $matches[3]; // وحدة القياس مثل px أو rem

                if($originalValue != 0) {
                    $newValue = $maxPercentage / $originalValue;
                    $newValue = $originalValue * $newValue;
                    $styleArray[$key] = "$property: $newValue$unit !important;";
                }
            }
        }

        // إعادة دمج الخصائص المعدلة في نص CSS واحد
        return implode(';', $styleArray);
    }


}
