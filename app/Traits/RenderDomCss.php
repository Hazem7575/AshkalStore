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
//        if(in_array($max , ['375px' , '480px'])) {
//            return self::MobileCss($style , $max);
//        }
        return $style;
    }


    public static function MobileCss($style , $max) {
        $styleArray = explode(';', $style);
        foreach ($styleArray as $key => $value) {

            $value = trim($value);
            if (strpos($value, 'grid-template-columns:') !== false) {
                $modifiedGridColumns = 'grid-template-columns: auto 100rem auto';
                $styleArray[$key] = $modifiedGridColumns .' !important;';
            }

//            if (strpos($value, 'grid-template-rows:') !== false) {
//                $columns_value = str_replace('grid-template-rows:' , '' , $value);
//                $rowsArray = explode(' ', trim($columns_value));
//                if (preg_match('/minmax\((\d*\.?\d*)rem,max-content\)/', $columns_value, $matches)) {
//
//                    $minValue = (float)$matches[1];
//
//                    $maxValue = 'max-content';
//
//                    if ($minValue * 16 > $max) {
//                        $new_val = $max / 16;
//                        $rowsArray[$key] = "minmax({$new_val}rem,$maxValue)";
//                    }
//                }
//
//                $modifiedGridColumns = 'grid-template-rows: ' . implode(' ' , $rowsArray);
//                $styleArray[$key] = $modifiedGridColumns .' !important;';
//            }

            if (strpos($value, 'grid-area:') !== false) {
                $columns_value = str_replace('grid-area:' , '' , $value);
                $explode = explode('/' , $columns_value);
                $explode[0] = $explode[0] + 1;
                $explode[1] = $explode[1] - 2;
                $explode[2] = $explode[2] + 1;

                $modifiedGridColumns = 'grid-area: ' . implode(' / ' , $explode);
                $styleArray[$key] = $modifiedGridColumns .' !important;';
            }
            if (strpos($value, 'width:') !== false) {
                $columns_value = str_replace('width:' , '' , $value);
                $value_alone = trim(str_replace('px' , '' , $columns_value));

                $modifiedGridColumns = 'width: ' . $max .' !important;';
                $styleArray[$key] = $modifiedGridColumns;


            }
        }

        return implode(';' , $styleArray);
    }


}
