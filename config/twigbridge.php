<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Configuration options for Twig.
 */
return [

    'twig' => [
        /*
        |--------------------------------------------------------------------------
        | Extension
        |--------------------------------------------------------------------------
        |
        | File extension for Twig view files.
        |
        */
        'extension' => 'twig',

        /*
        |--------------------------------------------------------------------------
        | Accepts all Twig environment configuration options
        |--------------------------------------------------------------------------
        |
        | http://twig.sensiolabs.org/doc/api.html#environment-options
        |
        */
        'environment' => [

            // When set to true, the generated templates have a __toString() method
            // that you can use to display the generated nodes.
            // default: false
            'debug' => env('APP_DEBUG', false),

            // The charset used by the templates.
            // default: utf-8
            'charset' => 'utf-8',

            // An absolute path where to store the compiled templates, or false to disable caching. If null
            // then the cache file path is used.
            // default: cache file storage path
            'cache' => false,

            // When developing with Twig, it's useful to recompile the template
            // whenever the source code changes. If you don't provide a value
            // for the auto_reload option, it will be determined automatically based on the debug value.
            'auto_reload' => true,

            // If set to false, Twig will silently ignore invalid variables
            // (variables and or attributes/methods that do not exist) and
            // replace them with a null value. When set to true, Twig throws an exception instead.
            // default: false
            'strict_variables' => true,

            // If set to true, auto-escaping will be enabled by default for all templates.
            // default: 'html'
            'autoescape' => false,

            // A flag that indicates which optimizations to apply
            // (default to -1 -- all optimizations are enabled; set it to 0 to disable)
            'optimizations' => -1,
        ],

        /*
        |--------------------------------------------------------------------------
        | Safe Classes
        |--------------------------------------------------------------------------
        |
        | When set, the output of the `__string` method of the following classes will not be escaped.
        | default: Laravel's Htmlable, which the HtmlString class implements.
        |
        */
        'safe_classes' => [
            \Illuminate\Contracts\Support\Htmlable::class => ['html'],
        ],

        /*
        |--------------------------------------------------------------------------
        | Global variables
        |--------------------------------------------------------------------------
        |
        | These will always be passed in and can be accessed as Twig variables.
        | NOTE: these will be overwritten if you pass data into the view with the same key.
        |
        */
        'globals' => [],
    ],

    'extensions' => [

        /*
        |--------------------------------------------------------------------------
        | Extensions
        |--------------------------------------------------------------------------
        |
        | Enabled extensions.
        |
        | `Twig\Extension\DebugExtension` is enabled automatically if twig.debug is TRUE.
        |
        */
        'enabled' => [
            'TwigBridge\Extension\Laravel\Event',
            'TwigBridge\Extension\Loader\Facades',
            'TwigBridge\Extension\Loader\Filters',
            'TwigBridge\Extension\Loader\Functions',
            'TwigBridge\Extension\Loader\Globals',

            'TwigBridge\Extension\Laravel\Auth',
            'TwigBridge\Extension\Laravel\Config',
            'TwigBridge\Extension\Laravel\Dump',
            'TwigBridge\Extension\Laravel\Input',
            'TwigBridge\Extension\Laravel\Session',
            'TwigBridge\Extension\Laravel\Str',
            'TwigBridge\Extension\Laravel\Translator',
            'TwigBridge\Extension\Laravel\Url',
            'TwigBridge\Extension\Laravel\Model',
            'TwigBridge\Extension\Laravel\Gate',
            'TwigBridge\Extension\Laravel\Vite',

            // 'TwigBridge\Extension\Laravel\Form',
            // 'TwigBridge\Extension\Laravel\Html',
            // 'TwigBridge\Extension\Laravel\Legacy\Facades',
        ],



        /*
        |--------------------------------------------------------------------------
        | Facades
        |--------------------------------------------------------------------------
        |
        | Available facades. Access like `{{ Config.get('foo.bar') }}`.
        |
        | Each facade can take an optional array of options. To mark the whole facade
        | as safe you can set the option `'is_safe' => true`. Setting the facade as
        | safe means that any HTML returned will not be escaped.
        |
        | It is advisable to not set the whole facade as safe and instead mark the
        | each appropriate method as safe for security reasons. You can do that with
        | the following syntax:
        |
        | <code>
        |     'Form' => [
        |         'is_safe' => [
        |             'open'
        |         ]
        |     ]
        | </code>
        |
        | The values of the `is_safe` array must match the called method on the facade
        | in order to be marked as safe.
        |
        */
        'facades' => [],

        /*
        |--------------------------------------------------------------------------
        | Functions
        |--------------------------------------------------------------------------
        |
        | Available functions. Access like `{{ secure_url(...) }}`.
        |
        | Each function can take an optional array of options. These options are
        | passed directly to `Twig\TwigFunction`.
        |
        | So for example, to mark a function as safe you can do the following:
        |
        | <code>
        |     'link_to' => [
        |         'is_safe' => ['html']
        |     ]
        | </code>
        |
        | The options array also takes a `callback` that allows you to name the
        | function differently in your Twig templates than what it's actually called.
        |
        | <code>
        |     'link' => [
        |         'callback' => 'link_to'
        |     ]
        | </code>
        |
        */
        'functions' => [
            'elixir',
            'head',
            'last',
            'mix',
        ],

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        |
        | Available filters. Access like `{{ variable|filter }}`.
        |
        | Each filter can take an optional array of options. These options are
        | passed directly to `Twig\TwigFilter`.
        |
        | So for example, to mark a filter as safe you can do the following:
        |
        | <code>
        |     'studly_case' => [
        |         'is_safe' => ['html']
        |     ]
        | </code>
        |
        | The options array also takes a `callback` that allows you to name the
        | filter differently in your Twig templates than what is actually called.
        |
        | <code>
        |     'snake' => [
        |         'callback' => 'snake_case'
        |     ]
        | </code>
        |
        */
        'filters' => [
            'get' => 'data_get',
            'n_format' => function ($value) {
                if (is_numeric($value)) {
                    if ($value < 0) {
                        return '(' . number_format(abs($value), 2) . ')';
                    }
                    return number_format($value, 2);
                }
                return $value;
            },
            'n_format_limit' => function ($value, $limit = 10) {
                if (is_numeric($value)) {
                    if ($value > $limit) {
                        return number_format($value, 0, '.', ',');
                    }
                    return number_format($value, 2);
                }
                return $value;
            },
        ],

    ],
];
