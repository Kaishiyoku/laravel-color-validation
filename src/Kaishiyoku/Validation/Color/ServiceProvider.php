<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Validation\Factory;
use Kaishiyoku\Validation\Color\Validator as ColorValidator;

class ServiceProvider extends IlluminateServiceProvider
{
    private const NAMESPACE = 'colorValidation';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../../../resources/lang/', self::NAMESPACE);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving('validator', function (Factory $factory, $app) {
            $factory->resolver(function ($translator, $data, $rules, $messages) {
                $messages += trans(self::NAMESPACE . '::validation');

                return new ColorValidator($translator, $data, $rules, $messages);
            });
        });
    }
}
