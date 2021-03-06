<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'im\base\Bootstrap',
        'im\eav\Bootstrap',
        'im\variation\Bootstrap',
        'im\seo\Bootstrap',
        'im\filesystem\Bootstrap',
        'im\search\Bootstrap',
        'im\elasticsearch\Bootstrap',
        'im\users\Bootstrap',
        'im\cms\Bootstrap',
        'im\catalog\Bootstrap',
        'im\imshop\Bootstrap',
    ],
    'modules' => [
        'base' => [
            'class' => 'im\base\Module'
        ],
        'backend' => [
            'class' => 'im\backend\Module'
        ],
        'users' => [
            'class' => 'im\users\Module'
        ],
        'cms' => [
            'class' => 'im\cms\Module'
        ],
        'catalog' => [
            'class' => 'im\catalog\Module'
        ],
        'filesystem' => [
            'class' => 'im\filesystem\Module'
        ],
        'search' => [
            'class' => 'im\search\Module'
        ],
        'eav' => [
            'class' => 'im\eav\Module'
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zDPCtYx9C1ULvuuhUi7myprETRR_FFn-',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'im\users\components\UserComponent',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
//            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'cache' => false,
            'rules' => [
                ['pattern' => '/', 'route' => 'cms/page/view', 'defaults' => ['path' => 'index']],
                ['pattern' => 'storage/<server:\w+>/<path:(.*)>', 'route' => 'glide/index', 'encodeParams' => false],
                [
                    'class' => 'im\base\routing\GroupUrlRule',
                    'pattern' => '<path:[a-zA-Z0-9_\-]+>/<query:.+>',
                    'defaults' => ['query' => ''],
                    'encodeParams' => false,
                    'resolvers' => [
                        [
                            'class' => 'im\base\routing\ModelRouteResolver',
                            'route' => 'catalog/product-category/view',
                            'modelClass' => 'im\catalog\models\ProductCategory'
                        ],
                        [
                            'class' => 'im\base\routing\ModelRouteResolver',
                            'route' => 'search/search-page/view',
                            'modelClass' => 'im\search\models\SearchPage'
                        ]
                    ]
                ],
                [
                    'class' => 'im\base\routing\GroupUrlRule',
                    'pattern' => '<path:.+>',
                    'defaults' => ['path' => 'index'],
                    'resolvers' => [
                        [
                            'class' => 'im\base\routing\ModelRouteResolver',
                            'route' => 'catalog/product/view',
                            'modelClass' => 'im\catalog\models\Product'
                        ],
                        [
                            'class' => 'im\base\routing\ModelRouteResolver',
                            'route' => 'cms/page/view',
                            'modelClass' => 'im\cms\models\Page'
                        ],
                    ]
                ]

//                [
//                    'class' => 'im\base\routing\ModelUrlRule',
//                    'pattern' => '<url:.+>',
//                    'route' => 'site/index',
//                    'modelClass' => 'im\cms\models\Page'
//                ]
            ]
        ],


        'core' => 'im\base\components\Core',
        'typesRegister' => 'im\base\types\EntityTypesRegister',
        'filesystem' => [
            'class' => 'im\filesystem\components\FilesystemComponent',
            'filesystems' => require(__DIR__ . '/filesystems.php')
        ],
        'elFinder' => [
            'class' => '\im\elfinder\ElFinderComponent',
            'roots' => [
                [
                    'path' => '@webroot/uploads',
                    'url' => '@web/uploads',
                    'alias' => ['modules/filesystem/module', 'Uploads']
                ],
                [
                    'driver' => 'S3',
                    'options' => array_merge(require(__DIR__ . '/s3.php'), [
                        'alias' => 'S3',
                        'glideURL' => '/storage/s3',
                        'glideKey' => 'fsdsadasd'
                    ])
                ]
            ],
            'filesystems' => ['local' => [
                'glideURL' => '/storage/local',
                'glideKey' => 'fsdsadasd'
            ]/*, 'dropbox', 's3'*/]
        ],
        'seo' => [
            'class' => 'im\seo\components\Seo',
            'metaTypeSocialMetaTypes' => [
//                'product_meta' => ['open_graph']
            ]
        ],
        'view' => [
            'theme' => ['class' => 'im\imshop\Theme']
        ],
        'backendTheme' => 'im\adminlte\Theme',
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'im\users\clients\Facebook',
                    'clientId' => '507449656079588',
                    'clientSecret' => 'd82712a6066ba2310eb6c20e770c28e2'
                ],
                'google' => [
                    'class' => 'im\users\clients\Google',
                    'clientId' => '855363173801-ub3r8uvbkc5458anquemm8jcuvogfvug.apps.googleusercontent.com',
                    'clientSecret' => 'oO1HUqyxUpBpaxM1gWQ4ALIL'
                ],
//                'vkontakte' => [
//                    'class' => 'yii\authclient\clients\VKontakte',
//                    'clientId' => 'vkontakte_client_id',
//                    'clientSecret' => 'vkontakte_client_secret',
//                ]
            ],
        ],
        'searchManager' => 'im\search\components\SearchManager',
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200']
            ]
        ],
        'categorySearch' => 'im\catalog\components\search\CategorySearchComponent',
        'layoutManager' => 'im\cms\components\LayoutManager',
        'templateManager' => 'im\cms\components\TemplateManager',
        'glide' => [
            'class' => 'im\image\glide\Glide',
            'signKey' => 'fsdsadasd',
            'servers' => [
                'local' => [
                    'source' => '@app/web/files',
                    'cache' => '@app/runtime/cache/glide'
                ],
                's3' => [
                    'filesystem' => 's3',
                    'cache' => '@app/runtime/cache/glide'
                ]
            ]
        ],
        'cms' => 'im\cms\components\Cms',
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' =>  [
                    'depends' => [
                        'im\imshop\IEJqueryAsset',
                    ],
                    'jsOptions' => [
                        'condition' => '(!IE)|(gte IE 9)'
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'depends' => [
                        'yii\web\JqueryAsset'
                    ]
                ],
            ],
        ]
    ],
    'controllerMap' => [
        'glide' => 'im\image\glide\controllers\GlideController',
        'elfinder' => [
            'class' => 'im\elfinder\ElFinderController',
            'enableCsrfValidation' => false,
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
