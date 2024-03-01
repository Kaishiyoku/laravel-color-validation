<?php

namespace Kaishiyoku\Validation\Color\Tests\Feature;

it('validates the "color" rule', function (?string $color, bool $expected) {
    expect(testValidate($color, 'color'))->toBe($expected);
})->with([
    [null, false],
    ['white', true],
    ['DeepSkyBlue', true],
    ['rgba(4,200,100,0)', true],
    ['rgb(4, 200,100)', true],
    ['#37F', true],
    ['#37FFFF', true],
    ['fakecolor!', false],
    ['hsl(360, 30%, 20%)', true],
    ['hsla(360, 30%, 20%, 0.57893)', true],
]);

it('validates the "color_hex" rule', function (?string $color, bool $expected) {
    expect(testValidate($color, 'color_hex'))->toBe($expected);
})->with([
    [null, false],
    ['white', false],
    ['rgba(4,200,100,0)', false],
    ['rgb(4,200,100)', false],
    ['#37F', true],
    ['#37FFFF', true],
    ['#37Ffff', true],
    ['fakecolor', false],
]);

it('validates the "color_rgb" rule', function (?string $color, bool $expected) {
    expect(testValidate($color, 'color_rgb'))->toBe($expected);
})->with([
    [null, false],
    ['white', false],
    ['rgba(4,200,100,0', false],
    ['rgb(4,200,100)', true],
    ['rgb(4, 200, 100)', true],
    ['#37F', false],
    ['#37FFFF', false],
    ['fakecolor', false],
]);

it('validates the "color_rgba" rule', function (?string $color, bool $expected) {
    expect(testValidate($color, 'color_rgba'))->toBe($expected);
})->with([
    [null, false],
    ['white', false],
    ['rgba(4,200,100,0)', true],
    ['rgba(4, 200, 100, 0)', true],
    ['rgba(4,200,100)', false],
    ['#37F', false],
    ['#37FFFF', false],
    ['fakecolor', false],
]);

it('validates the "color_name" rule', function (?string $color, bool $expected) {
    expect(testValidate($color, 'color_name'))->toBe($expected);
})->with([
    [null, false],
    ['invalidcolor', false],
    ['blue', true],
    ['transparent', true],
    ['TRANSPARENT', true],
]);

it('validates the "color_hsl" rule', function (?string $color, bool $expected) {
    expect(testValidate($color, 'color_hsl'))->toBe($expected);
})->with([
    [null, false],
    ['fakecolor', false],
    ['hsl(120,60%,70%)', true],
    ['hsl(120, 60%, 70%)', true],
]);

it('validates the "color_hsla" rule', function (?string $color, bool $expected) {
    expect(testValidate($color, 'color_hsla'))->toBe($expected);
})->with([
    [null, false],
    ['fakecolor', false],
    ['hsla(120,60%,70%,1)', true],
    ['hsla(120,60%,70%,0)', true],
    ['hsla(120, 60%, 70%, 0.52834823489)', true],
    ['hsla(120,60%,70%,1.00000123)', false],
    ['hsla(120,60%,70%,2)', false],
    ['hsla(102, 100%, 50%, 1.0000000)', true],
    ['hsla(102, 100%, 50%, 1.)', false],
    ['hsla(102, 100%, 50%, 0.)', false],
    ['hsla(102, 100%, 50%, 0.1)', true],
    ['hsla(102, 100%, 50%, 0)', true],
    ['hsla(980, 100%, 50%, 0)', false],
]);