<?php

namespace App\Units;

class ResponsiveUnit
{
    public $style;

    public function new_size($file, $new_width, $new_height)
    {
        $json = json_decode($file, true);

        $original_width = $json[0]['layers']['ROOT']['props']['boxSize']['width'];
        $original_height = $json[0]['layers']['ROOT']['props']['boxSize']['height'];

        // Calculate the scaling factors for width and height
        $width_ratio = $new_width / $original_width;
        $height_ratio = $new_height / $original_height;

        // Update the JSON with new sizes
        $new_json = $this->new_json($json, $width_ratio, $height_ratio);

        // Return the updated JSON (or dump it for debugging)
        return json_encode($new_json);
    }

    public function new_json($file_json, $width_ratio, $height_ratio)
    {
        // Loop through each layer and adjust its properties
        foreach ($file_json[0]['layers'] as $key => &$layer) {
            if (isset($layer['props']['boxSize'])) {
                // Resize width and height
                $layer['props']['boxSize']['width'] *= $width_ratio;
                $layer['props']['boxSize']['height'] *= $height_ratio;
            }

            if (isset($layer['props']['position'])) {
                // Adjust x and y positions
                $layer['props']['position']['x'] *= $width_ratio;
                $layer['props']['position']['y'] *= $height_ratio;
            }
        }

        return $file_json;
    }

}
