<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Validation\Factory;
use Kaishiyoku\Validation\Color\Validator as ColorValidator;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../../../resources/lang/', 'kaishiyoku');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving('validator', function (Factory $factory, $app) {

            $colorValidator = new ColorValidator();

            foreach (ColorValidator::VALIDATION_NAMES as $validationName) {
                $factory->extend($validationName, function ($attributes, $value, $parameters, $validator) use ($colorValidator, $validationName) {
                    return $colorValidator->callValidationFor($validationName, $value);
                });

                $factory->replacer($validationName, function ($message, $attribute, $rule, $parameters) use ($factory) {
                    return $factory->getTranslator()->trans('kaishiyoku::validation.'.$rule, compact('attribute'));
                });
            }
        });
    }
}
