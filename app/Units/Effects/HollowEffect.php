<?php 
namespace App\Units\Effects;
class HollowEffect{
    // "effect": {
    //     "name": "hollow",
    //     "settings": {
    //         "thickness": 97,
    //         "color": "rgb(0, 0, 0)"
    //     }
    // }

    public static function render($props){
        $style = '';
        // -webkit-text-stroke: 3.77996px rgb(0, 0, 0);
        // -webkit-text-fill-color: transparent;
        if (isset($props['effect'])) {
            // $thickness = 
        }

    }
}

//0.0924991  is const rate stander  max thickness/fontSize for 100%
// to get max-thickness multiplied by 0.0924991 * fontSize

// for fontSize 100 and max-thickness 9.24991px 
//5.08329px
// this is how can i calc text-stroke 
// text-stroke=0.0091666×font-size+0.0008333×(font-size×thickness)
 
