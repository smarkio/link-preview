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

namespace LinkPreview\Model;

class MediaLink extends Link
{

    /** @var string */
    protected $embedCode;

    /**
     * @var bool
     */
    protected $_parsed = false;

    /**
     * @return string
     */
    public function getEmbedCode()
    {
        return $this->embedCode;
    }

    /**
     * @param string $embedCode
     *
     * @return MediaLink
     */
    public function setEmbedCode($embedCode)
    {
        $this->embedCode = $embedCode;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        $this->_parsed = false;
        return parent::setUrl($url);
    }

    /**
     * @param boolean $parsed
     *
     * @return MediaLink
     */
    public function setParsed($parsed)
    {
        $this->_parsed = $parsed;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isParsed()
    {
        return $this->_parsed;
    }



}