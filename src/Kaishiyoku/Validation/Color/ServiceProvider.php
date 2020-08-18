<?php

namespace Kaishiyoku\Validation\Color;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Validation\Factory;
use Kaishiyoku\Validation\Color\Validator as ColorValidator;

class ServiceProvider extends IlluminateServiceProvider
{
    private const NAMESPACE = 'color_validation';

    private const FALLBACK_LOCALE = 'en';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../../resources/lang' => resource_path('lang/vendor/color_validation')
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../../../../resources/lang/', self::NAMESPACE);
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
                $colorValidationMessages = __(self::NAMESPACE . '::validation');

                $messages = array_merge($messages, is_string($colorValidationMessages)
                    ? __(self::NAMESPACE . '::validation', [], self::FALLBACK_LOCALE)
                    : $colorValidationMessages);

                return new ColorValidator($translator, $data, $rules, $messages);
            });
        });
    }
}
