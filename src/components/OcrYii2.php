<?php

namespace tsmd\ocr\components;

use Yii;
use yii\base\BaseObject;

/**
 *  ocr Yii兼容
 */
class OcrYii2 extends BaseObject
{
    /**
    'ocr'=>[
        'class' => 'tsmd\ocr\components\OcrYii2',
        'ocrs' => ['Tencent' => [
                    'secretId' => '',
                    'secretKey' => '',
                ],
                'Huawei' => [
                    'appKey' => '',
                    'appSecret' => '',
                    'projectId' => '',
                    'regionName' => '',
                ],
                'Baidu' => [
                    'appID' => '',
                    'apiKey' => '',
                    'secretKey' => '',
                ]
        ]
    ]
     */
    public $ocrs;

    public $ocrClass;

    public function init (){
        $this->ocrClass = new Ocr($this->ocrs);
    }

    public function ocrAction($type,$imageUrl){
        return $this->ocrClass->$type($imageUrl);
    }
}