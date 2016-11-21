<?php
/**
 *
 *
 * @author     Pedro Borges <pedro.borges@smark.io>
 * @copyright  2016 Smarkio
 * @license    [SMARKIO_URL_LICENSE_HERE]
 *
 * [SMARKIO_DISCLAIMER]
 */

namespace LinkPreview\Parser;

class GeneralParserWithImageFallback extends GeneralParser
{
    /**
     * @inheritDoc
     */
    public function parseLink()
    {
        $link  = parent::parseLink();
        $image = $link->getImage();
        if (empty($image) && !empty($link->getPictures()))
        {
            $image = current($link->getPictures());
            $res   = preg_match('/^https?:\/\//', $image);
            if ($res <= 0)
            {

                $image = rtrim($link->getRealUrl(), '/') . '/' . ltrim($image, '/');

            }
            $link->setImage($image);
        }
        return $link;
    }


}