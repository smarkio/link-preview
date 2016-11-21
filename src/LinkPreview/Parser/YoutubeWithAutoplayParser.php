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


class YoutubeWithAutoplayParser extends YoutubeParser
{

    /**
     * @inheritdoc
     */
    public function parseLink()
    {
        $this->readLink();
        $link     = $this->getLink();
        $content = $link->getCharset() == 'utf-8' ? utf8_decode($link->getContent()) : $link->getContent();
        $htmlData = $this->parseHtml($content);

        $link->setTitle($htmlData['title'])
            ->setDescription($htmlData['description'])
            ->setImage($htmlData['image'])
            ->setEmbedCode(
                sprintf('<iframe class="fullscreen-media" id="video" src="//www.youtube.com/embed/%s?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>', $link->getVideoId())
            )
        ;

        return $link;
    }


    /**
     * Read link
     */
    private function readLink()
    {
        $reader = $this->getReader()->setLink($this->getLink());
        $this->setLink($reader->readLink());
    }


}