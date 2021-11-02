<?php

namespace tsmd\ocr\components;

/**
 * @author Haisen <thirsight@gmail.com>
 * @since 1.0
 */
class Ocr
{
    /**
     * @var ocrs[]
     */
    public $ocrs = [];

    /**
    $ocrs = [
        'Tencent' => [
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
     * 将配置实例化获取
     *
     */
    public function __construct(array $ocrs)
    {
        foreach ($ocrs as $key => &$val) {
            $name = "tsmd\ocr\components\\" . $key . "Ocr";
            $class = new $name;
            $this->setAttribute($class, $val);
            array_push($this->ocrs, $class);
        }
    }

    /**
     * 精简文字识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function simplifyOcr(string $imageUrl)
    {
        foreach ($this->ocrs as $ocr) {
            if ($info = $ocr->simplifyOcr($imageUrl)) {
                return $info;
            }
        }
        return [];
    }

    /**
     * 普通文字识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function GeneralOcr(string $imageUrl)
    {
        foreach ($this->ocrs as $ocr) {
            if ($info = $ocr->GeneralOcr($imageUrl)) {
                return $info;
            }
        }
        return [];
    }

    /**
     * 高精度文字识别
     *
     * @param array $imageUrl
     * @return array
     */
    public function AccurateOcr(string $imageUrl)
    {
        foreach ($this->ocrs as $ocr) {
            if ($info = $ocr->AccurateOcr($imageUrl)) {
                return $info;
            }
        }
        return [];
    }

    /**
     * 通用表格识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function TableOcr(string $imageUrl)
    {
        foreach ($this->ocrs as $ocr) {
            if ($info = $ocr->TableOcr($imageUrl)) {
                return $info;
            }
        }
        return [];
    }

    /**
     * 设置类的属性
     */
    public function setAttribute($class, $val)
    {
        foreach ($val as $k => $v) {
            if (property_exists($class, $k)) {
                $class->$k = $v;
            }
        }
    }
}
