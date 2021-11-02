<?php

namespace tsmd\ocr\components;
require_once 'tencentcloud/src/TencentCloud/Common/Credential.php';
require_once 'tencentcloud/src/TencentCloud/Common/Profile/ClientProfile.php';
require_once 'tencentcloud/src/TencentCloud/Common/Profile/HttpProfile.php';
require_once 'tencentcloud/src/TencentCloud/Ocr/V20181119/OcrClien.php';
require_once 'tencentcloud/src/TencentCloud/Ocr/V20181119/Models/GeneralBasicOCRRequest.php';
require_once 'tencentcloud/src/TencentCloud/Ocr/V20181119/Models/GeneralAccurateOCRRequest.php';
require_once 'tencentcloud/src/TencentCloud/Ocr/V20181119/Models/GeneralEfficientOCRRequest.php';
require_once 'tencentcloud/src/TencentCloud/Ocr/V20181119/Models/GeneralFastOCRRequest.php';
require_once 'tencentcloud/src/TencentCloud/Ocr/V20181119/Models/RecognizeTableOCRRequest.php';
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Ocr\V20181119\OcrClient;
use TencentCloud\Ocr\V20181119\Models\GeneralBasicOCRRequest;
use TencentCloud\Ocr\V20181119\Models\GeneralAccurateOCRRequest;
use TencentCloud\Ocr\V20181119\Models\GeneralEfficientOCRRequest;
use TencentCloud\Ocr\V20181119\Models\GeneralFastOCRRequest;
use TencentCloud\Ocr\V20181119\Models\RecognizeTableOCRRequest;

/**
 * 腾讯通用文字识别
 *
 * @author Haisen <thirsight@gmail.com>
 * @since 1.0
 */
class TencentOcr implements OcrImageInterface
{
    /**
     * @var string
     */
    public $secretId;
    /**
     * @var string
     */
    public $secretKey;

    /**
     * 通用印刷体识别
     * @see https://cloud.tencent.com/document/product/866/33526
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function GeneralOcr($imageUrl)
    {
        $req = new GeneralBasicOCRRequest();
        $req->fromJsonString($this->formatterParams($imageUrl));
        $resp = $this->getOcrClient()->GeneralBasicOCR($req);
        return $this->formatterResp($resp->toJsonString());
    }

    /**
     * 通用印刷体识别（精简版）
     * @see https://cloud.tencent.com/document/product/866/37831
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function simplifyOcr($imageUrl)
    {
        $req = new GeneralEfficientOCRRequest();
        $req->fromJsonString($this->formatterParams($imageUrl));
        $resp = $this->getOcrClient()->GeneralEfficientOCR($req);
        return $this->formatterResp($resp->toJsonString());
    }

    /**
     * 通用印刷体识别（高精度版）
     * @see https://cloud.tencent.com/document/product/866/34937
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function AccurateOcr($imageUrl)
    {
        $req = new GeneralAccurateOCRRequest();
        $req->fromJsonString($this->formatterParams($imageUrl));
        $resp = $this->getOcrClient()->GeneralAccurateOCR($req);
        return $this->formatterResp($resp->toJsonString());
    }

    /**
     * 通用印刷体识别（高速版）
     * @see https://cloud.tencent.com/document/product/866/33525
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function generalFast($imageUrl)
    {
        $req = new GeneralFastOCRRequest();
        $req->fromJsonString($this->formatterParams($imageUrl));
        $resp = $this->getOcrClient()->GeneralFastOCR($req);
        return $this->formatterResp($resp->toJsonString());
    }

    /**
     * 表格识别（V2)
     * @see https://cloud.tencent.com/document/api/866/49525
     * @param string $imageUrl 图片 URL
     * @return string
     */
    public function TableOcr($imageUrl)
    {
        $req = new RecognizeTableOCRRequest();
        $req->fromJsonString($this->formatterParams($imageUrl));
        $resp = $this->getOcrClient()->RecognizeTableOCR($req);

        $resp = json_decode($resp->toJsonString(), true);
        $resp = array_reduce($resp['TableDetections'], function ($carrier, $tables) {
            $cellsTexts = implode('', array_column($tables['Cells'], 'Text'));
            $carrier .= str_replace(' ', '', $cellsTexts);
            return $carrier;
        });
        return $resp;
    }

    /**
     * @param string $response json
     * @return mixed
     */
    private function formatterParams($imageUrl)
    {
        $params = [
            'LanguageType' => 'zh',
        ];
        if (stripos($imageUrl, 'http') !== false) {
            $params['ImageUrl'] = $imageUrl;
        } elseif (file_exists($imageUrl)) {
            $params['ImageBase64'] = base64_encode(file_get_contents($imageUrl));
        }
        return json_encode($params);
    }

    /**
     * @param string $response json
     * @return mixed
     */
    private function formatterResp($response)
    {
        $resp = json_decode($response, true);
        $resp = array_reduce($resp['TextDetections'], function ($carrier, $item) {
            $carrier .= $item['DetectedText'] . ' ';
            return $carrier;
        });
        return $resp;
    }

    /**
     * @return OcrClient
     */
    private function getOcrClient()
    {
        $cred = new Credential($this->secretId, $this->secretKey);
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("ocr.tencentcloudapi.com");

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        return new OcrClient($cred, "ap-shanghai", $clientProfile);
    }
}
