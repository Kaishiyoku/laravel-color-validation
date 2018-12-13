<?php

namespace Kaishiyoku\Validation\Color;

class Validator
{
    public function isColor($color)
    {
        return $this->isColorAsHex($color)
            || $this->isColorAsRGB($color)
            || $this->isColorAsRGBA($color);
    }

    public function isColorAsHex($color)
    {
        return $this->isColorAsLongHex($color) || $this->isColorAsShortHex($color);
    }

    public function isColorAsRGB($color)
    {
        preg_match('/^(rgb)\(([01]?\d\d?|2[0-4]\d|25[0-5])(\W+)([01]?\d\d?|2[0-4]\d|25[0-5])\W+(([01]?\d\d?|2[0-4]\d|25[0-5])\))$/i',$color,$m);
        return count($m) > 0;
    }

    public function isColorAsRGBA($color)
    {
        preg_match('/^(rgba)\(([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\)?\W+([01](\.\d+)?)\)$/i',$color,$m);
        return count($m) > 0;
    }

    public function isColorAsShortHex($color)
    {
        preg_match('/^#(\d|a|b|c|d|e|f){3}$/i', $color, $m);
        return count($m) > 0;
    }

    public function isColorAsLongHex($color)
    {
        preg_match('/^#(\d|a|b|c|d|e|f){6}$/i', $color, $m);
        return count($m) > 0;
    }
}
