<?php

namespace app\assets;
use yii\web\AssetBundle;

class VpnUIAssetBundle extends AssetBundle {
    public $sourcePath = '@app/assets/vpn';
    public $js = [
        'js/main.js'
    ];
    public $css = [
        'css/vpn.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}