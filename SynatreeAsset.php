<?php

namespace synatree\dynamicrelations;

use yii\web\AssetBundle;

class SynatreeAsset extends AssetBundle
{

    public $sourcePath = '@vendor/synatree/yii2-dynamic-relations/assets';
    public $js = [
        'js/dynamic-relations.js'
    ];
    public $css = [
        'css/dynamic-relations.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
