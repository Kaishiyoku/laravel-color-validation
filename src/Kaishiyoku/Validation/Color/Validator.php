<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
    /**
     * Available validation rule names
     */
    public const VALIDATION_RULE_NAMES = [
        'color',
        'color_hex',
        'color_rgb',
        'color_rgba',
        'color_name',
    ];

    /**
     * Supported color names
     *
     * @var array
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
     * The standard color validator (hex, rgb, rgba)
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function validateColor($attribute, $value)
    {
        $fnNames = ['validateColorHex', 'validateColorRGB', 'validateColorRGBA', 'validateColorName'];

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
     * The color name validator
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function validateColorName($attribute, $value)
    {
        return in_array($value, self::AVAILABLE_COLOR_NAMES, true);
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
