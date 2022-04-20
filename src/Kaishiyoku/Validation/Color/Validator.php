<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator as BaseValidator;
use Spatie\Regex\Regex;

class Validator extends BaseValidator
{
    /**
     * The standard color validator (hex, rgb, rgba)
     */
    public function validateColor(string $attribute, ?string $value): bool
    {
        $fnNames = [
            'validateColorHex',
            'validateColorRGB',
            'validateColorRGBA',
            'validateColorName',
            'validateColorHSL',
            'validateColorHSLA',
        ];

        return $this->checkValidationFnsFor($fnNames, $attribute, $value);
    }

    /**
     * The hex color validator
     */
    public function validateColorHex(string $attribute, ?string $value): bool
    {
        $fnNames = ['isColorLongHex', 'isColorShortHex'];

        return $this->checkValidationFnsFor($fnNames, $attribute, $value);
    }

    /**
     * The RGB color validator
     */
    public function validateColorRGB(string $attribute, ?string $value): bool
    {
        $regex = '/^(rgb)\(([01]?\d\d?|2[0-4]\d|25[0-5])(\W+)([01]?\d\d?|2[0-4]\d|25[0-5])\W+(([01]?\d\d?|2[0-4]\d|25[0-5])\))$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * The RGBA color validator
     */
    public function validateColorRGBA(string $attribute, ?string $value): bool
    {
        $regex = '/^(rgba)\(([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\)?\W+([01](\.\d+)?)\)$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * The color name validator
     */
    public function validateColorName(string $attribute, ?string $value): bool
    {
        return in_array(Str::lower($value), ColorConstants::AVAILABLE_COLOR_NAMES, true)
            || in_array(Str::lower($value), ColorConstants::AVAILABLE_SPECIAL_COLOR_NAMES, true);
    }

    /**
     * The HSL color validator
     */
    public function validateColorHSL(string $attribute, ?string $value): bool
    {
        $regex = '/^(hsl\((?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-9][0-9]|3[0-5][0-9]|360),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%\)$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * The HSLA color validator
     */
    public function validateColorHSLA(string $attribute, ?string $value): bool
    {
        $regex = '/^^(hsla\((?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-9][0-9]|3[0-5][0-9]|360),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%,(?:\s|)(0|1|1\.0{1,}|0\.[0-9]{1,})\)$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * Check if a color is valid (short hex)
     */
    private function isColorShortHex(string $attribute, ?string $value): bool
    {
        $regex = '/^#(\d|a|b|c|d|e|f){3}$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * Check if a color is valid (long hex)
     */
    private function isColorLongHex(string $attribute, ?string $value): bool
    {
        $regex = '/^#(\d|a|b|c|d|e|f){6}$/i';

        return $this->callPregMatcher($value, $regex);
    }

    /**
     * The regular expression matcher
     */
    private function callPregMatcher(?string $value, string $pattern): bool
    {
        return Regex::match($pattern, $value ?: '')->hasMatch();
    }

    /**
     * The generic validation function which can check for multiple validators
     *
     * @param string[] $fnNames
     * @param string $attribute
     * @param string|null $value
     * @return bool
     */
    private function checkValidationFnsFor(array $fnNames, string $attribute, ?string $value): bool
    {
        return array_reduce($fnNames, function ($accum, $fnName) use ($attribute, $value) {
            return $accum || $this->{$fnName}($attribute, $value);
        }, false);
    }
}
