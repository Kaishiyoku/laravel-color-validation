<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Support\Str;

class Validator
{
    public const VALIDATION_NAMES = [
        'color',
        'color_hex',
        'color_rgb',
        'color_rgba',
    ];

    public function isColor($color)
    {
        $fnNames = ['isColorHex', 'isColorRGB', 'isColorRGBA'];

        return $this->checkValidationFnsFor($fnNames, $color);
    }

    public function isColorHex($color)
    {
        $fnNames = ['isColorLongHex', 'isColorShortHex'];

        return $this->checkValidationFnsFor($fnNames, $color);
    }

    public function isColorRGB($color)
    {
        $regex = '/^(rgb)\(([01]?\d\d?|2[0-4]\d|25[0-5])(\W+)([01]?\d\d?|2[0-4]\d|25[0-5])\W+(([01]?\d\d?|2[0-4]\d|25[0-5])\))$/i';

        return $this->callPregMatcher($color, $regex);
    }

    public function isColorRGBA($color)
    {
        $regex = '/^(rgba)\(([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\)?\W+([01](\.\d+)?)\)$/i';

        return $this->callPregMatcher($color, $regex);
    }

    public function isColorShortHex($color)
    {
        $regex = '/^#(\d|a|b|c|d|e|f){3}$/i';

        return $this->callPregMatcher($color, $regex);
    }

    public function isColorLongHex($color)
    {
        $regex = '/^#(\d|a|b|c|d|e|f){6}$/i';

        return $this->callPregMatcher($color, $regex);
    }

    public function callValidationFor($name, $color)
    {
        $fnName = Str::camel('is_'. $name);

        return $this->{$fnName}($color);
    }

    private function callPregMatcher($color, $str)
    {
        preg_match($str, $color, $matches);

        return count($matches) > 0;
    }

    private function checkValidationFnsFor($fnNames, $color)
    {
        return array_reduce($fnNames, function ($accum, $fnName) use ($color) {
            return $accum || $this->{$fnName}($color);
        }, false);
    }
}
