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



use LinkPreview\Model\LinkInterface;
use LinkPreview\Model\MediaLink;
use LinkPreview\Reader\ReaderInterface;
use LinkPreview\Reader\NoBodyReader;

class MediaParser implements ParserInterface
{

    /** @var ReaderInterface */
    protected $reader;

    /** @var MediaLink */
    protected $link;

    /**
     * @inheritDoc
     */
    public function __construct(ReaderInterface $reader = null, LinkInterface $link = null)
    {
        if (null === $reader)
        {
            $this->reader = new NoBodyReader();
        }
        else
        {
            $this->reader = $reader;
        }

        if (null === $link)
        {
            $this->link = new MediaLink();
        }
        else
        {
            $this->link = $link;
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return 'media';
    }

    /**
     * Get model
     * @return MediaLink
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
     * Get reader
     * @return ReaderInterface
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * Set reader
     *
     * @param ReaderInterface $reader
     *
     * @return $this
     */
    public function setReader(ReaderInterface $reader)
    {
        $this->reader = $reader;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isValidParser()
    {
        $result = $this->parseLink();

        return !is_null($result);
    }

    /**
     * @inheritDoc
     */
    public function parseLink()
    {
        $link = $this->getLink();
        if ($link->isParsed())
        {
            return $link;
        }
        $reader = $this->getReader()->setLink($link);
        $this->setLink($reader->readLink());
        $link = $this->getLink();

        if (!strncmp($link->getContentType(), 'image/', strlen('image/')))
        {
            $link->setEmbedCode(
                sprintf('<img src="%s"></img>', $link->getRealUrl())
            );
            $link->setImage($link->getRealUrl());
        }
        else if (!strncmp($link->getContentType(), 'video/', strlen('video/')))
        {
            $link->setEmbedCode(
                sprintf('<video controls width="640" height="480" autoplay><source src="%s" type="%s"></video>', $link->getRealUrl(), $link->getContentType())
            );
        }
        else if (!strncmp($link->getContentType(), 'audio/', strlen('audio/')))
        {
            $link->setEmbedCode(
                sprintf('<audio controls="controls">  Your browser does not support the <code>audio</code> element.  <source src="%s" type="%s"></audio>', $link->getRealUrl(), $link->getContentType())
            );
        }
        else if (strncmp($link->getContentType(), 'text/', strlen('text/')))
        {
            $link->setEmbedCode('');
        }
        else
        {
            return null;
        }

        $link->setTitle(basename($link->getRealUrl()));
        $link->setParsed(true);

        return $link;
    }
}