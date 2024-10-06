<?php

namespace App\Units\Layers;

use App\Units\Effects\EchoEffect;
use App\Units\Effects\HollowEffect;
use App\Units\Effects\LiftEffect;
use App\Units\Effects\ShadowEffect;
use App\Units\Effects\SpliceEffect;
use App\Units\Helpers\ImageHelper;
use App\Units\Helpers\TextHelper;
use App\Units\Styles\BoxSizeUnit;
use App\Units\Styles\ColorUnit;
use App\Units\Styles\FontSizeUnit;
use App\Units\Styles\GridElementUnit;
use App\Units\Styles\OpacityUnit;
use App\Units\Styles\PositionUnit;
use App\Units\Styles\TransformUnit;

class TextLayer
{
    public static function rander($element)
    {
        $style = '';

        $data = [
            'children' => []
        ];

        if (array_key_exists('position', $element) && array_key_exists('boxSize', $element)) {
            if (array_search('position', array_keys($element)) < array_search('boxSize', array_keys($element))) {
                $style .= PositionUnit::rander($element['position']);
                $style .= BoxSizeUnit::rander($element['boxSize'] , false);
            }else{
                $style .= BoxSizeUnit::rander($element['boxSize'] , false);
                $style .= PositionUnit::rander($element['position']);
            }
        }

        $style .= FontSizeUnit::rander($element);
        $style .= TransformUnit::rander($element);
        $style .= GridElementUnit::rander($element);

        if (isset($element['text'])) {
            $data['children'][] = TextHelper::getAttr($element);
        }

        if (isset($element['fonts'])) {
            foreach ($element['fonts'] as $font) {
                if (isset($font['name'], $font['fonts'][0]['urls'][0])) {
                    $style .= 'font-family: ' . $font['name'] . '; ';
                }
            }
        }

        if (isset($element['effect'])) {
            $style .= self::matchTextEffect($element);
        }


        $data['style'] = $style;

        // إرجاع البيانات
        return $data;
    }


    private static function matchTextEffect(array $element): string
    {
        $style = '';
        switch ($element['effect']['name']) {
            case 'hollow':
                $style .= HollowEffect::render($element);
                break;
                case 'lift':
                    $style .= LiftEffect::render($element);
                break;
            case 'shadow':
                $style .= ShadowEffect::render($element);
                break;
            case 'echo':
                $style .= EchoEffect::render($element);
                break;
            case 'splice':
                $style .= SpliceEffect::render($element);
                break;
            default:
                break;
        }
        return $style;
    }

}
