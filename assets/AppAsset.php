<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/backend.css',
        'css/backend-plugin.min.css'
    ];
    public $js = [
        'vendor\moment.min.js',
        'js\yii2AjaxRequest.js',
        // 'js\dashboard.js',
        
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',        
    ];
    //Controller Map is for custom route to controller
    public $controllerMap = [
        'loginUrl' => array('/auth/login'),
        '/tos'=>'app\controllers\SiteController'
        // 'auth' => 'app\controllers\AuthController',
        // 'dashboard' => 'app\controllers\DashboardController',
    ];
}
