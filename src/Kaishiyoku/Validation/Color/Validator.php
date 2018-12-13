<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
    public const VALIDATION_NAMES = [
        'color',
        'color_hex',
        'color_rgb',
        'color_rgba',
    ];

    public function validateColor($attribute, $value)
    {
        $fnNames = ['validateColorHex', 'validateColorRGB', 'validateColorRGBA'];

        return $this->checkValidationFnsFor($fnNames, $attribute, $value);
    }

    public function validateColorHex($attribute, $value)
    {
        $fnNames = ['isColorLongHex', 'isColorShortHex'];

        return $this->checkValidationFnsFor($fnNames, $attribute, $value);
    }

    public function validateColorRGB($attribute, $value)
    {
        $regex = '/^(rgb)\(([01]?\d\d?|2[0-4]\d|25[0-5])(\W+)([01]?\d\d?|2[0-4]\d|25[0-5])\W+(([01]?\d\d?|2[0-4]\d|25[0-5])\))$/i';

        return $this->callPregMatcher($value, $regex);
    }

    public function validateColorRGBA($attribute, $value)
    {
        $regex = '/^(rgba)\(([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\)?\W+([01](\.\d+)?)\)$/i';

        return $this->callPregMatcher($value, $regex);
    }

    private function isColorShortHex($attribute, $color)
    {
        $regex = '/^#(\d|a|b|c|d|e|f){3}$/i';

        return $this->callPregMatcher($color, $regex);
    }

    private function isColorLongHex($attribute, $color)
    {
        $regex = '/^#(\d|a|b|c|d|e|f){6}$/i';

        return $this->callPregMatcher($color, $regex);
    }

    private function callPregMatcher($color, $str)
    {
        preg_match($str, $color, $matches);

        return count($matches) > 0;
    }

    private function checkValidationFnsFor($fnNames, $attribute, $value)
    {
        return array_reduce($fnNames, function ($accum, $fnName) use ($attribute, $value) {
            return $accum || $this->{$fnName}($attribute, $value);
        }, false);
    }
}
