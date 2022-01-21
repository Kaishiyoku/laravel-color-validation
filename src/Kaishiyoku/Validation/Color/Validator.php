<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator as BaseValidator;
use Spatie\Regex\Regex;

class Validator extends BaseValidator
{
    /**
     * Available validation rule names
     *
     * @var string[]
     */
    public const VALIDATION_RULE_NAMES = [
        'color',
        'color_hex',
        'color_rgb',
        'color_rgba',
        'color_name',
        'color_hsl',
        'color_hsla',
    ];

    /**
     * Supported color names
     *
     * @var string[]
     */
    public const AVAILABLE_COLOR_NAMES = [
        // CSS Level 1
        'silver',
        'gray',
        'white',
        'maroon',
        'red',
        'purple',
        'fuchsia',
        'green',
        'lime',
        'olive',
        'yellow',
        'navy',
        'blue',
        'teal',
        'aqua',

        // CSS Level 2 (Revision 1)
        'orange',
        'aliceblue',
        'antiquewhite',
        'aquamarine',
        'azure',
        'beige',
        'bisque',
        'blanchedalmond',
        'blueviolet',
        'brown',
        'burlywood',
        'cadetblue',
        'chartreuse',
        'chocolate',
        'coral',
        'cornflowerblue',
        'cornsilk',
        'crimson',
        'darkblue',
        'darkcyan',
        'darkgoldenrod',
        'darkgray',
        'darkgreen',
        'darkgrey',
        'darkkhaki',
        'darkmagenta',
        'darkolivegreen',
        'darkorange',
        'darkorchid',
        'darkred',
        'darksalmon',
        'darkseagreen',
        'darkslateblue',
        'darkslategray',
        'darkslategrey',
        'darkturquoise',
        'darkviolet',
        'deeppink',
        'deepskyblue',
        'dimgray',
        'dimgrey',
        'dodgerblue',
        'firebrick',
        'floralwhite',
        'forestgreen',
        'gainsboro',
        'ghostwhite',
        'gold',
        'goldenrod',
        'greenyellow',
        'grey',
        'honeydew',
        'hotpink',
        'indianred',
        'indigo',
        'ivory',
        'khaki',
        'lavender',
        'lavenderblush',
        'lawngreen',
        'lemonchiffon',
        'lightblue',
        'lightcoral',
        'lightcyan',
        'lightgoldenrodyellow',

        // CSS Color Module Level 3
        'lightgray',
        'lightgreen',
        'lightgrey',
        'lightpink',
        'lightsalmon',
        'lightseagreen',
        'lightskyblue',
        'lightslategray',
        'lightslategrey',
        'lightsteelblue',
        'lightyellow',
        'limegreen',
        'linen',
        'mediumaquamarine',
        'mediumblue',
        'mediumorchid',
        'mediumpurple',
        'mediumseagreen',
        'mediumslateblue',
        'mediumspringgreen',
        'mediumturquoise',
        'mediumvioletred',
        'midnightblue',
        'mintcream',
        'mistyrose',
        'moccasin',
        'navajowhite',
        'oldlace',
        'olivedrab',
        'orangered',
        'orchid',
        'palegoldenrod',
        'palegreen',
        'paleturquoise',
        'palevioletred',
        'papayawhip',
        'peachpuff',
        'peru',
        'pink',
        'plum',
        'powderblue',
        'rosybrown',
        'royalblue',
        'saddlebrown',
        'salmon',
        'sandybrown',
        'seagreen',
        'seashell',
        'sienna',
        'skyblue',
        'slateblue',
        'slategray',
        'slategrey',
        'snow',
        'springgreen',
        'steelblue',
        'tan',
        'thistle',
        'tomato',
        'turquoise',
        'violet',
        'wheat',
        'whitesmoke',
        'yellowgreen',

        // CSS Color Module Level 4
        'rebeccapurple',
    ];

    /**
     * Special color names
     *
     * @var string[]
     */
    public const AVAILABLE_SPECIAL_COLOR_NAMES = [
        'transparent',
    ];

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
        return in_array(Str::lower($value), self::AVAILABLE_COLOR_NAMES, true)
            || in_array(Str::lower($value), self::AVAILABLE_SPECIAL_COLOR_NAMES, true);
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
