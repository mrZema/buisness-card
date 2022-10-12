<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GridAsset extends AssetBundle
{
    public $basePath = '@assetsRoot';
    public $baseUrl = '@assets';
    public $css = [];
    public $js = [
        'js/grid_v.0.0.1.js'
    ];
    public $depends = [
        'kartik\dialog\DialogBootstrapAsset',
    ];
}
