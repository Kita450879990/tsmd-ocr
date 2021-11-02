<?php

/**
 * TSMD 模块配置文件
 *
 * 须将此配置文件导入到 /api/web/index.php 文件的 $config 参数
 *
 * @link https://tsmd.thirsight.com/
 * @copyright Copyright (c) 2008 thirsight
 * @license https://tsmd.thirsight.com/license/
 */

return [
    // 设置路径别名，以便 Yii::autoload() 可自动加载 TSMD 自定的类
    'aliases' => [
        // yii2-tsmd-flight 路径
        '@tsmd/ocr' => __DIR__ . '/../src',
    ],

    // 模块组件配置
    'components' => [
        'Ocr' => [
            'class' => 'tsmd\ocr\components\Ocr',
            'ocrs' => ['HuaweiOcr', 'TencentOcr', 'BaiduOcr']
        ],
        'TencentOcr' => [
            'class' => 'tsmd\ocr\components\TencentOcr',
            'secretId' => '',
            'secretKey' => '',
        ],
        'HuaweiOcr' => [
            'class' => 'tsmd\ocr\components\HuaweiOcr',
            'appKey' => '',
            'appSecret' => '',
            'projectId' => '',
            'regionName' => '',
        ],
        'BaiduOcr' => [
            'class' => 'tsmd\ocr\components\BaiduOcr',
            'appID' => '',
            'apiKey' => '',
            'secretKey' => '',
        ],

    ],
];
