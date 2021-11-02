<?php

namespace tsmd\ocr\components;

/**
 * 智能文字识别接口
 */
interface OcrImageInterface
{
    /**
     * 精简文字识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function simplifyOcr(string $imageUrl);

    /**
     * 普通文字识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function GeneralOcr(string $imageUrl);

    /**
     * 高精度文字识别
     *
     * @param array $imageUrl
     * @return array
     */
    public function AccurateOcr(string $imageUrl);

    /**
     * 普通表格识别
     *
     * @param string $imageUrl
     * @return array
     */
    public function TableOcr(string $imageUrl);

}
