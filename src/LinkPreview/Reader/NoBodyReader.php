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

namespace LinkPreview\Reader;


use LinkPreview\Model\LinkInterface;

class NoBodyReader implements ReaderInterface
{

    /**
     * @inheritdoc
     */
    private $link;

    /**
     * Get model
     * @return LinkInterface
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set model
     *
     * @param LinkInterface $link
     *
     * @return $this
     */
    public function setLink(LinkInterface $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Read and update model
     * @return LinkInterface
     */
    public function readLink()
    {
        $link = $this->getLink();

        $handle = curl_init($this->getLink()->getUrl());
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_exec($handle);
        $headerContentType  = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
        $effectiveUrl = curl_getinfo($handle, CURLINFO_EFFECTIVE_URL);
        $contentType = '';
        if (is_array($headerContentType) && count($headerContentType) > 0) {
            $contentType = current(explode(';', current($headerContentType)));
        }elseif(is_string($headerContentType)){
            $contentType = current(explode(';', $headerContentType));
        }
        $link->setContent('')
            ->setContentType($contentType)
            ->setRealUrl($effectiveUrl)
        ;

        return $link;
    }

}