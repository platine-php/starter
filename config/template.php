<?php

use Platine\Framework\Template\Tag\AuthTag;
use Platine\Framework\Template\Tag\CsrfTag;
use Platine\Framework\Template\Tag\CurrentUrlTag;
use Platine\Framework\Template\Tag\PermissionTag;
use Platine\Framework\Template\Tag\RouteUrlTag;
use Platine\Framework\Template\Tag\SessionFlashTag;
use Platine\Framework\Template\Tag\SessionTag;
use Platine\Framework\Template\Tag\StaticTag;
use Platine\Template\Cache\NullCache;

    return [
        'cache_driver'   => env('PL_TEMPLATE_CACHE_DRIVER', NullCache::class),
        'cache_expire'   => env('PL_TEMPLATE_CACHE_TTL', 5600, 'int'),
        'cache_dir'      => __DIR__ . '/../storage/tmp/cache',
        'cache_prefix'   => env('PL_TEMPLATE_CACHE_FILE_PREFIX', '__platine_template'),
        'template_dir'   => env('PL_TEMPLATE_PATH', __DIR__ . '/../storage/resource/templates'),
        'file_extension' => 'html',
        'auto_escape'    => true,
        'filters'        => [],
        'tags'           => [
            'static' => StaticTag::class,
            'current_url' => CurrentUrlTag::class,
            'csrf' => CsrfTag::class,
            'session' => SessionTag::class,
            'flash' => SessionFlashTag::class,
            'route_url' => RouteUrlTag::class,
            'auth' => AuthTag::class,
            'permission' => PermissionTag::class,
        ],
    ];
