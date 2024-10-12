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
            'propsName'=>'props_s_iphon',
            'maxWidth'=>375,
        ],
        [
            'min-width' => '375.05px',
            'max-width' => '480px',
            'propsName'=>'props_iphon',
            'maxWidth'=>480,

        ],
        [
            'min-width' => '480.05px',
            'max-width' => '768px',
            'propsName'=>'props_mobile',
            'maxWidth'=>1024,

        ],
        [
            'min-width' => '768.05px',
            'max-width' => '1024px',
            'propsName'=>'props_tap',
            'maxWidth'=>1024,
        ],
        // [
        //     'min-width' => '1024.05px',
        //     'propsName'=>'props',
        //     'maxWidth'=>1024,

        // ]
    ];

    public static function css_name($style, $prefix = null, $name = null, $is_class = true)
    {
        if (is_null($name)) {
            $name = 'store_' . Str::random(8);
        }

        $name = $prefix . $name;
        self::$collection_css[$name] = [
            'is_class' => $is_class,
            'props' => $style['props'],
        ];
        foreach (self::$mediaQuery as  $media) {
            self::$collection_css[$name][$media['propsName']] = $style[$media['propsName']];
        }
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
                    }';

    }

    public static function RenderCollectionCss()
    {
        self::FirstRenderCss();
        self::RenderCssMediaQuery();
        self::$templete_css .= self::$render_css_collection;
    }

    public static function RenderCssMediaQuery()
    {
        foreach (self::$collection_css as $key => $collect) {
            $prefix = '.';
            if (!$collect['is_class']) {
                $prefix = '#';
            }

            self::$render_css_collection .= $prefix . $key . '{' . $collect['props'] . '}';
        }
       foreach (self::$mediaQuery as$key=> $mediaQuery) {
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
           if(is_array(self::$collection_css) AND count(self::$collection_css) > 0) {
               foreach (self::$collection_css as $key => $collect) {
                   $prefix = '.';
                   if (!$collect['is_class']) {
                       $prefix = '#';
                   }

                   self::$render_css_collection .= $prefix . $key . '{' . $collect[$mediaQuery['propsName']] . '}';
               }
           }

           self::$render_css_collection .= '}';

       }
    }

}
