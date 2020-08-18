<?php

namespace Kaishiyoku\Validation\Color\Tests;

use Validator;

class ColorValidatorTest extends TestCase
{
    protected function validate($color, $rule = 'color')
    {
        return !(Validator::make(['test' => $color], ['test' => $rule])->fails());
    }

    public function testValidatorColor()
    {
        $this->assertTrue($this->validate('white', 'color'));
        $this->assertTrue($this->validate('DeepSkyBlue', 'color'));
        $this->assertTrue($this->validate('rgba(4,200,100,0)', 'color'));
        $this->assertTrue($this->validate('rgb(4, 200,100)', 'color'));
        $this->assertTrue($this->validate('#37F', 'color'));
        $this->assertTrue($this->validate('#37FFFF', 'color'));
        $this->assertFalse($this->validate('fakecolor!', 'color'));
        $this->assertTrue($this->validate('hsl(360, 30%, 20%)', 'color'));
        $this->assertTrue($this->validate('hsla(360, 30%, 20%, 0.57893)', 'color'));
    }

    public function testValidatorColorAsHex()
    {
        $this->assertFalse($this->validate('white', 'color_hex'));
        $this->assertFalse($this->validate('rgba(4,200,100,0)', 'color_hex'));
        $this->assertFalse($this->validate('rgb(4,200,100)', 'color_hex'));
        $this->assertTrue($this->validate('#37F', 'color_hex'));
        $this->assertTrue($this->validate('#37FFFF', 'color_hex'));
        $this->assertFalse($this->validate('fakecolor!', 'color_hex'));
    }

    public function testValidatorColorAsRGB()
    {
        $this->assertFalse($this->validate('white', 'color_rgb'));
        $this->assertFalse($this->validate('rgba(4,200,100,0)', 'color_rgb'));
        $this->assertTrue($this->validate('rgb(4,200,100)', 'color_rgb'));
        $this->assertFalse($this->validate('#37F', 'color_rgb'));
        $this->assertFalse($this->validate('#37FFFF', 'color_rgb'));
        $this->assertFalse($this->validate('fakecolor!', 'color_rgb'));
    }

    public function testValidatorColorAsRGBA()
    {
        $this->assertFalse($this->validate('white', 'color_rgba'));
        $this->assertTrue($this->validate('rgba(4,200,100,0)', 'color_rgba'));
        $this->assertFalse($this->validate('rgb(4,200,100)', 'color_rgba'));
        $this->assertFalse($this->validate('#37F', 'color_rgba'));
        $this->assertFalse($this->validate('#37FFFF', 'color_rgba'));
        $this->assertFalse($this->validate('fakecolor!', 'color_rgba'));
    }

    public function testValidatorColorAsName()
    {
        $this->assertFalse($this->validate('invalidcolor', 'color_name'));
        $this->assertTrue($this->validate('blue', 'color_name'));
        $this->assertTrue($this->validate('transparent', 'color_name'));
        $this->assertTrue($this->validate('TRANSPARENT', 'color_name'));
    }

    public function testValidatorColorAsHSL()
    {
        $this->assertFalse($this->validate('fakecolor', 'color_hsl'));
        $this->assertTrue($this->validate('hsl(120,60%,70%)', 'color_hsl'));
    }

    public function testValidatorColorAsHSLA()
    {
        $this->assertFalse($this->validate('fakecolor', 'color_hsla'));
        $this->assertTrue($this->validate('hsla(120,60%,70%,1)', 'color_hsla'));
        $this->assertTrue($this->validate('hsla(120,60%,70%,0)', 'color_hsla'));
        $this->assertTrue($this->validate('hsla(120, 60%, 70%, 0.52834823489)', 'color_hsla'));
        $this->assertFalse($this->validate('hsla(120,60%,70%,1.00000123)', 'color_hsla'));
        $this->assertFalse($this->validate('hsla(120,60%,70%,2)', 'color_hsla'));
        $this->assertTrue($this->validate('hsla(102, 100%, 50%, 1.0000000)', 'color_hsla'));
        $this->assertFalse($this->validate('hsla(102, 100%, 50%, 1.)', 'color_hsla'));
        $this->assertFalse($this->validate('hsla(102, 100%, 50%, 0.)', 'color_hsla'));
        $this->assertTrue($this->validate('hsla(102, 100%, 50%, 0.1)', 'color_hsla'));
        $this->assertTrue($this->validate('hsla(102, 100%, 50%, 0)', 'color_hsla'));
        $this->assertFalse($this->validate('hsla(980, 100%, 50%, 0)', 'color_hsla'));
    }
}
