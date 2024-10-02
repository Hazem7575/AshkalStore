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

class Json2HtmlUnit
{
    public static $fonts = [];
    public static function convert($json)
    {
        $html = '';

        foreach ($json as $key => $value) {
            $html .= '<div style="position: relative;">';
            $first = $value['layers'];
            $root = $first['ROOT'];
            $html .= self::children($root , 'ROOT' , $first);
            $html .= '</div>';
        }


        $html .= '<style>';

        foreach (self::$fonts as $fontName => $fontVariants) {

            foreach ($fontVariants as $variant) {
                $html .= '@font-face {';
                $html .= 'font-family: "' . $fontName . '"; ';
                $html .= 'src: url("' . "https://corsproxy.io/?".$variant['url'] . '"); ';
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




    public static function children($child , $index, $collection)
    {

        $style = self::shaps($child['type']['resolvedName'], $child['props']);

        if(!isset($style['style'])) return '';
        $html = '<div style="'. $style['style'] .'">';

        if(isset($style['children']) AND is_array($style['children']) AND count($style['children']) > 0) {
            foreach ($style['children'] as $child) {
                $html .= RenderElement::render($child);
            }
        }

        $check_if_have_child = collect($collection)->where('parent' , '=' , $index)->all();

        if($check_if_have_child AND count($check_if_have_child) > 0) {
            foreach ($check_if_have_child as $row => $child_sub) {
                $html .= self::children($child_sub , $row, $collection);
            }
        }
        $html .= '</div>';




        return $html;
    }

    public static function shaps($type , $props)
    {
        //get fonts
        if($type == 'TextLayer' AND isset($props['fonts']) AND count($props['fonts']) > 0) {
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



       return match ($type) {
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
    }


    public function fonts()
    {

    }

}
