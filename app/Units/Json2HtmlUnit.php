<?php

namespace App\Units;

use App\Traits\RenderDomCss;
use App\Traits\RenderDomFiles;
use App\Traits\RenderDomFonts;
use App\Traits\RenderDomJS;
use App\Units\Helpers\RenderElement;
use App\Units\Layers\FrameLayer;
use App\Units\Layers\GroupLayer;
use App\Units\Layers\LineLayer;
use App\Units\Layers\RootShape;
use App\Units\Layers\ShapeLayer;
use App\Units\Layers\SvgLayer;
use App\Units\Layers\TextLayer;
use App\Units\Styles\GridUnit;

class Json2HtmlUnit
{
    use RenderDomFonts , RenderDomFiles , RenderDomCss , RenderDomJS;
    public static $template;
    public static $paths_dir;

    public static $templete_css;
    public static $size_layer;
    public static $width_layers;
    public static function convert($json)
    {
        self::$paths_dir = [
            'path_css' => '/test/css/',
            'path_js' => '/test/js/',
            'path_font' => '/test/fonts',
        ];

        $html = '';
        foreach ($json as $key => $value) {

            $first = $value['layers'];
            $root = $first['ROOT'];
            self::$templete = $first;
            self::getFonts()->getFontsUrl()->getRenderFonts()->render();
            $width = $root['props']['boxSize']['width'];
            $height = $root['props']['boxSize']['height'];
            self::$size_layer = [
                'width' => $width,
                'height' => $height,
            ];
            $html .= self::buildRoot($root, 'ROOT', $first);
        }

        self::renderFileCss();

        return [
            'html' => $html,
            'css' => self::getUrlCss(),
            'js' => self::getUrlJs(),
            'sizes' => self::$size_layer
        ];
    }




    public static function buildRoot($child, $index, &$collection)
    {
        $style = [];
        $style['props'] = self::shaps($child,$collection);
        $mediaStyle = [];
        $mediaStyle['props'] = $style['props']['style']."display: grid;position: relative;grid-area: 1 / 2 / 2 / 3;".$child['props']['grid_template'];
        foreach (self::$mediaQuery as $key => $media) {
            $style[$media['propsName']] = self::shaps($child,$collection,$media['propsName']);
            $mediaStyle[$media['propsName']] = $style[$media['propsName']]['style']."display: grid;position: relative;grid-area: 1 / 2 / 2 / 3;".$child[$media['propsName']]['grid_template'];
        }

        // if (!isset($style['style'])) return '';
        $classes = 'layer-contianer ';
        $html = '<section class="' . $classes . '">';
        $class_name = self::css_name($mediaStyle);
        // self::put_size($style['style'] , $class_name , $child);
        $html .='<div class="'. $class_name .'">';
        if (isset($style['children']) and is_array($style['children']) and count($style['children']) > 0) {
            foreach ($style['children'] as $child) {
                $html .= RenderElement::render($child);
            }
        }

        $check_if_have_child = collect($collection)->where('parent', '=', $index)->all();

        if ($check_if_have_child and count($check_if_have_child) > 0) {
            $zIndex = 1;
            // $zIndex = count($check_if_have_child);
            foreach ($check_if_have_child as $row => $child_sub) {
                $html .= self::children($child_sub, $row, $collection,$zIndex);
                $zIndex+=1;
            }
        }
        $html .='</div>';

        $html .= '</section>';




        return $html;
    }
    public static function children($child, $index, &$collection,$zIndex)
    {
        $mediaCollectionStyle=[];
        //for props
        $mediaCollectionStyle['props'] = self::shaps($child,$collection);
        foreach (self::$mediaQuery as $key => $media) {
            $mediaCollectionStyle[$media['propsName']] = self::shaps($child,$collection,$media['propsName']);
        }
        // if (!isset($style['style'])) return '';

        $mediaStyleGrid=[];
        $meidaStyleDiv=[];
        $styleWithGridDiv = 'position: relative;z-index: '.$zIndex.';';
        foreach ($mediaCollectionStyle as $mediaKey => $style) {
            if(isset($style['grid'])) {
                $mediaStyleGrid[$mediaKey] = $style['grid'].' '.$styleWithGridDiv;
            }
            $meidaStyleDiv[$mediaKey]=$style['style'];
        }
        $class_name = self::css_name($mediaStyleGrid);
        $class_name2 = self::css_name($meidaStyleDiv);


        // self::put_size($style_1 , $class_name , $child);

        // self::put_size($style['style'] , $class_name2 , $child);


        $html = '<div class="'. $class_name .'">';
        $html .= '<div class="'. $class_name2 .'">';
        if (isset($style['children']) and is_array($style['children']) and count($style['children']) > 0) {
            foreach ($style['children'] as $child) {
                $html .= RenderElement::render($child);
            }
        }

        $check_if_have_child = collect($collection)->where('parent', '=', $index)->all();

        if ($check_if_have_child and count($check_if_have_child) > 0) {
            $zIndex = 1;
            foreach ($check_if_have_child as $row => $child_sub) {
                $html .= self::children($child_sub, $row, $collection,$zIndex);
                $zIndex+=1;
            }
        }
        $html .= '</div>';
        $html .= '</div>';




        return $html;
    }



    public static function shaps(&$childElement,&$collection,$propsName='props')
    {
       
        $type = $childElement['type']['resolvedName'];
        
        $props = $childElement[$propsName];
        //get fonts
        if ($type == 'TextLayer' and isset($props['fonts']) and count($props['fonts']) > 0) {
            foreach ($props['fonts'] as $font) {
                $fontName = $font['name'];
                if (!isset(self::$fonts[$fontName])) {
                    self::$fonts[$fontName] = [];
                }
                foreach ($font['fonts'] as $fontVariant) {
                    if (isset($fontVariant['urls'])) {
                        foreach ($fontVariant['urls'] as $url) {
                            self::$fonts[$fontName][] = [
                                'url' => $url,
                                'style' => $fontVariant['style'] ?? 'normal'
                            ];
                        }
                    }
                }
            }
        }



        $style = match ($type) {
            'RootLayer'  =>   RootShape::rander($props),
            'ShapeLayer' =>   ShapeLayer::rander($props),
            'FrameLayer' =>   FrameLayer::rander($props),
            'TextLayer'  =>   TextLayer::rander($props),
            'GroupLayer' =>   GroupLayer::rander($props),
            'SvgLayer'  =>    SvgLayer::rander($props),
            'ImageLayer' =>   FrameLayer::rander($props),
            'LineLayer' =>   LineLayer::rander($props),
            default => '',
        };
        if(isset($childElement['child'])&&!isset($childElement['props']['grid_template'])){
            $style['style'] = $style['style'].GridUnit::rander($childElement,$collection);
                        
        }
        return $style;
    }



    public function fonts() {}
}
