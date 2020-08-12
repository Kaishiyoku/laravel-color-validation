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

    /**
     * The standard color validator (hex, rgb, rgba)
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function validateColor($attribute, $value)
    {
        $fnNames = ['validateColorHex', 'validateColorRGB', 'validateColorRGBA'];

        return $this->checkValidationFnsFor($fnNames, $attribute, $value);
    }

    /**
     * The hex color validator
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function validateColorHex($attribute, $value)
    {
        $fnNames = ['isColorLongHex', 'isColorShortHex'];

        return $this->checkValidationFnsFor($fnNames, $attribute, $value);
    }

    /**
     * The RGB color validator
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function validateColorRGB($attribute, $value)
    {
        $regex = '/^(rgb)\(([01]?\d\d?|2[0-4]\d|25[0-5])(\W+)([01]?\d\d?|2[0-4]\d|25[0-5])\W+(([01]?\d\d?|2[0-4]\d|25[0-5])\))$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * The RGBA color validator
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function validateColorRGBA($attribute, $value)
    {
        $regex = '/^(rgba)\(([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\)?\W+([01](\.\d+)?)\)$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * Check if a color is valid (short hex)
     *
     * @param string $attribute
     * @param string $color
     * @return bool
     */
    private function isColorShortHex($attribute, $color)
    {
        $regex = '/^#(\d|a|b|c|d|e|f){3}$/i';

        return $this->callPregMatcher($color, $regex);
    }

    /**
     * Check if a color is valid (long hex)
     *
     * @param string $attribute
     * @param string $color
     * @return bool
     */
    private function isColorLongHex($attribute, $color)
    {
        $regex = '/^#(\d|a|b|c|d|e|f){6}$/i';

        return $this->callPregMatcher($color, $regex);
    }

    /**
     * The regular expression matcher
     *
     * @param string $color
     * @param string $str
     * @return bool
     */
    private function callPregMatcher($color, $str)
    {
        preg_match($str, $color, $matches);

        return count($matches) > 0;
    }

    /**
     * The generic validation function which can check for multiple validators
     *
     * @param string[] $fnNames
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    private function checkValidationFnsFor($fnNames, $attribute, $value)
    {
        return array_reduce($fnNames, function ($accum, $fnName) use ($attribute, $value) {
            return $accum || $this->{$fnName}($attribute, $value);
        }, false);
    }
}
