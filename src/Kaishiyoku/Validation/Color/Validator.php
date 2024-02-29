<?php

namespace Kaishiyoku\Validation\Color;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator as BaseValidator;
use Kaishiyoku\Validation\Color\Enums\Color;
use Spatie\Regex\Regex;

class Validator extends BaseValidator
{
    /**
     * The standard color validator (hex, rgb, rgba)
     */
    public function validateColor(string $attribute, ?string $value): bool
    {
        $fns = [
            $this->validateColorHex(...),
            $this->validateColorRGB(...),
            $this->validateColorRGBA(...),
            $this->validateColorName(...),
            $this->validateColorHSL(...),
            $this->validateColorHSLA(...),
        ];

        return $this->checkValidationFnsFor($fns, $attribute, $value);
    }

    /**
     * The hex color validator
     */
    public function validateColorHex(string $attribute, ?string $value): bool
    {
        $fns = [
            $this->isColorLongHex(...),
            $this->isColorShortHex(...),
        ];

        return $this->checkValidationFnsFor($fns, $attribute, $value);
    }

    /**
     * The RGB color validator
     */
    public function validateColorRGB(string $attribute, ?string $value): bool
    {
        return $this->pregMatch(
            value: $value,
            pattern: '/^(rgb)\(([01]?\d\d?|2[0-4]\d|25[0-5])(\W+)([01]?\d\d?|2[0-4]\d|25[0-5])\W+(([01]?\d\d?|2[0-4]\d|25[0-5])\))$/i',
        );
    }

    /**
     * The RGBA color validator
     */
    public function validateColorRGBA(string $attribute, ?string $value): bool
    {
        return $this->pregMatch(
            value: $value,
            pattern: '/^(rgba)\(([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\W+([01]?\d\d?|2[0-4]\d|25[0-5])\)?\W+([01](\.\d+)?)\)$/i',
        );
    }

    /**
     * The color name validator
     */
    public function validateColorName(string $attribute, ?string $value): bool
    {
        return in_array(
            needle: Str::lower($value),
            haystack: array_map(fn (Color $color) => $color->value, Color::cases()),
            strict: true,
        );
    }

    /**
     * The HSL color validator
     */
    public function validateColorHSL(string $attribute, ?string $value): bool
    {
        return $this->pregMatch(
            value: $value,
            pattern: '/^(hsl\((?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-9][0-9]|3[0-5][0-9]|360),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%\)$/i',
        );
    }

    /**
     * The HSLA color validator
     */
    public function validateColorHSLA(string $attribute, ?string $value): bool
    {
        return $this->pregMatch(
            value: $value,
            pattern: '/^^(hsla\((?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-9][0-9]|3[0-5][0-9]|360),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%),(?:\s|)(?:[0-9]|[1-9][0-9]|100)%,(?:\s|)(0|1|1\.0{1,}|0\.[0-9]{1,})\)$/i',
        );
    }

    /**
     * Check if a color is valid (short hex)
     */
    private function isColorShortHex(string $attribute, ?string $value): bool
    {
        return $this->pregMatch(
            value: $value,
            pattern: '/^#(\d|a|b|c|d|e|f){3}$/i',
        );
    }

    /**
     * Check if a color is valid (long hex)
     */
    private function isColorLongHex(string $attribute, ?string $value): bool
    {
        return $this->pregMatch(
            value: $value,
            pattern: '/^#(\d|a|b|c|d|e|f){6}$/i',
        );
    }

    /**
     * The regular expression matcher
     */
    private function pregMatch(?string $value, string $pattern): bool
    {
        return Regex::match($pattern, $value ?: '')->hasMatch();
    }

    /**
     * The generic validation function which can check for multiple validators
     *
     * @param  Closure[]  $fns
     * @param  string  $attribute
     * @param  string|null  $value
     * @return bool
     */
    private function checkValidationFnsFor(array $fns, string $attribute, ?string $value): bool
    {
        return array_reduce(
            array: $fns,
            callback: fn (bool $accum, Closure $fn) => $accum || $fn($attribute, $value),
            initial: false,
        );
    }
}
