<?php

namespace im\imshop;

use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/imshop/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/main.css'
    ];

//    /**
//     * @inheritdoc
//     */
//    public $js = [
//
//    ];

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'only' => [
            'images/*',
            'images/*/*',
            'css/*.css',
        ],
        'forceCopy' => true
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\imshop\FontAwesomeAsset'
    ];
}