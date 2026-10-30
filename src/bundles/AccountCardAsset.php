<?php

namespace portalium\site\bundles;

use yii\web\AssetBundle;

/**
 * Styles of the account card in the side menu ({@see \portalium\site\widgets\AccountCard}).
 */
class AccountCardAsset extends AssetBundle
{
    public $sourcePath = '@vendor/portalium/yii2-site/src/assets/account';

    public $css = [
        'css/account-card.css',
    ];

    public $depends = [
        'portalium\theme\bundles\AppAsset',
    ];
}
