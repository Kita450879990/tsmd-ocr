<?php

namespace tsmd\ocr\components;

require_once 'HWOcrClient/HWOcrClientAKSK.php';
require_once 'HWOcrClient/HWOcrClientToken.php';

/**
 * 华为文字识别
 *
 * @author Haisen <thirsight@gmail.com>
 * @since 1.0
 */
class HuaweiOcr implements OcrImageInterface
{
    /**
     * @var string 我的凭证 -> 访问密钥
     */
    public $appKey;
    /**
     * @var string 我的凭证 -> 访问密钥
     */
    public $appSecret;
    /**
     * @var string 我的凭证 -> API凭证 -> 项目ID 0cbd84692a80f2462f2fc00d25f72bac
     */
    public $projectId;
    /**
     * @var string 我的凭证 -> API凭证 -> 华北-北京四(cn-north-4)
     */
    public $regionName;

    /**
     * @param string $response json
     * @return mixed
     */
    private function formatterResp($response)
    {
        try {
            $resp = json_decode($response, true);
            $resp = array_reduce($resp['result']['words_block_list'], function ($carrier, $item) {
                if (!empty($item['confidence']) && $item['confidence'] > 0.5) {
                    $carrier .= $item['words'] . ' ';
                }
                return $carrier;
            });
            return $resp;

        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 通用文字识别
     * @see https://apiexplorer.developer.huaweicloud.com/apiexplorer/doc?product=OCR&api=RecognizeGeneralText
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function GeneralOcr($imageUrl)
    {
        $uri = "/v2/{$this->projectId}/ocr/general-text";
        $option = [
            'detect_direction' => true,
            'quick_mode' => false,
        ];
        $akskAuth = new \HWOcrClientAKSK(
            $this->appKey,
            $this->appSecret,
            $this->regionName,
            $uri
        );
        return $this->formatterResp($akskAuth->RequestOcrAkSkService($imageUrl, $option));
    }

    /**
     * 智能分类识别
     * @see https://apiexplorer.developer.huaweicloud.com/apiexplorer/doc?product=OCR&api=RecognizeAutoClassification
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function AccurateOcr($imageUrl)
    {
        $uri = "/v2/{$this->projectId}/ocr/auto-classification";
        $akskAuth = new \HWOcrClientAKSK(
            $this->appKey,
            $this->appSecret,
            $this->regionName,
            $uri
        );
        return $this->formatterResp($akskAuth->RequestOcrAkSkService($imageUrl));
    }

    /**
     * 网络图片识别
     * @see https://apiexplorer.developer.huaweicloud.com/apiexplorer/doc?product=OCR&api=RecognizeWebImage
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function simplifyOcr($imageUrl)
    {
        $uri = "/v2/{$this->projectId}/ocr/web-image";
        $option = [
            'detect_direction' => true,
        ];
        $akskAuth = new \HWOcrClientAKSK(
            $this->appKey,
            $this->appSecret,
            $this->regionName,
            $uri
        );
        return $this->formatterResp($akskAuth->RequestOcrAkSkService($imageUrl, $option));
    }

    /**
     * 通用表格识别
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function TableOcr($imageUrl)
    {
        $uri = "/v2/{$this->projectId}/ocr/general-table";
        $akskAuth = new \HWOcrClientAKSK(
            $this->appKey,
            $this->appSecret,
            $this->regionName,
            $uri
        );

        $resp = json_decode($akskAuth->RequestOcrAkSkService($imageUrl), true);
        $resp = array_reduce($resp['result']['words_region_list'], function ($carrier, $regions) {
            $carrier .= implode('', array_column($regions['words_block_list'], 'words'));
            return $carrier;
        });
        return $resp;
    }
}
