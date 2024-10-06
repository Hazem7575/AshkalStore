<?php

namespace App\Units;

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
    public static $fonts = [];
    public static function convert($json)
    {
        $html = '';
        foreach ($json as $key => $value) {

            $first = $value['layers'];
            $root = $first['ROOT'];
            $width = $root['props']['boxSize']['width'];
            $height = $root['props']['boxSize']['height'];
            $html .= self::buildRoot($root, 'ROOT', $first);
        }

        $html .= '<style>';

        foreach (self::$fonts as $fontName => $fontVariants) {

            foreach ($fontVariants as $variant) {
                $html .= '@font-face {';
                $html .= 'font-family: "' . $fontName . '"; ';
                $html .= 'src: url("' . "https://corsproxy.io/?" . $variant['url'] . '"); ';
                if (isset($variant['style'])) {
                    if (strpos($variant['style'], 'Italic') !== false) {
                        $html .= 'font-style: italic; ';
                    } else {
                        $html .= 'font-style: normal; ';
                    }

                    if (strpos($variant['style'], 'Bold') !== false) {
                        $html .= 'font-weight: bold; ';
                    } else {
                        $html .= 'font-weight: normal; ';
                    }
                } else {
                    $html .= 'font-style: normal; ';
                    $html .= 'font-weight: normal; ';
                }

                $html .= '}';
            }
        }

        $html .= '</style>';
        return $html;
    }




    public static function buildRoot($child, $index, &$collection)
    {
        $style = self::shaps($child,$collection);

        if (!isset($style['style'])) return '';
        $classes = 'layer-contianer ';
        $html = '<section class="' . $classes . '">';
        $html .='<div style="'.$style['style']."    display: grid;position: relative;grid-area: 1 / 2 / 2 / 3;". '">';
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
        $style = self::shaps($child,$collection);

        if (!isset($style['style'])) return '';

        $styleWithGridDiv = 'position: relative;z-index: '.$zIndex.';';
        $html = '<div style="' . (isset($style['grid'])? $style['grid']:''). $styleWithGridDiv . '">';
        $html .= '<div style="' . $style['style'] ."border: 1px solid;". '">';

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



    public static function shaps($childElement,&$collection)
    {
        $type = $childElement['type']['resolvedName'];
        $props = $childElement['props'];
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
        if(isset($childElement['child'])){
            $style['style'] = $style['style'].GridUnit::rander($childElement,$collection);
        }
        return $style;
    }


    public function fonts() {}
}
