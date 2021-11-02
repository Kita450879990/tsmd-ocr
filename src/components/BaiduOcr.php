<?php

namespace tsmd\ocr\components;

require_once 'aip/AipOcr.php';

/**
 * 百度文字识别
 *
 * @see https://cloud.baidu.com/doc/OCR/s/Zk3h7ydmv
 * @author Haisen <thirsight@gmail.com>
 * @since 1.0
 */
class BaiduOcr implements OcrImageInterface
{
    /**
     * @var string
     */
    public $appID;
    /**
     * @var string
     */
    public $apiKey;
    /**
     * @var string
     */
    public $secretKey;
    /**
     * @var \AipOcr
     */
    public $client;

    /**
     * @param string $type eg. basicGeneral, basicGeneralUrl, basicAccurate
     * @param string $url URL or base64
     * @return string
     */
    public function ocr($type, $url)
    {
        $options = [];
        $options["language_type"] = "CHN_ENG";
        $options["detect_direction"] = "true";
        $options["detect_language"] = "true";
        $options["probability"] = "true";
        try {
            $client = new \AipOcr($this->appID, $this->apiKey, $this->secretKey);
            $resp = $client->{$type}($url, $options);
            return array_reduce($resp['words_result'], function ($carry, $w) {
                $carry .= $w['words'] . ' ';
                return $carry;
            });
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 通用文字识别
     * @param string $url
     * @return string
     */
    public function GeneralOcr($url)
    {
        return $this->ocr('basicGeneralUrl', $url);
    }

    /**
     * 通用文字识别（高精度版）
     * @param string $url
     * @return string
     */
    public function AccurateOcr($url)
    {
        return $this->ocr('basicAccurate', file_get_contents($url));
    }

    /**
     * 精简文字识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function simplifyOcr(string $imageUrl)
    {
        return false;
    }

    /**
     * 普通表格识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function TableOcr(string $imageUrl)
    {
        return false;
    }
}
