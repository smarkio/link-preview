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


class VimeoParser extends YoutubeParser
{

    const PATTERN = '/^(?:https?:\/\/)?(?:www\.)?vimeo.com\/([0-9a-z\-_]+)$/';

    /**
     * Extract required data from html source
     *
     * @param $html
     *
     * @return array
     */
    protected function parseHtml($html)
    {
        $data = [
            'image'       => '',
            'title'       => '',
            'description' => '',
            'videoUrl'    => ''
        ];

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        /** @var \DOMElement $meta */
        foreach ($doc->getElementsByTagName('meta') as $meta)
        {
            if ($meta->getAttribute('property') === 'og:image')
            {
                $data['image'] = $meta->getAttribute('content');
            }

            if ($meta->getAttribute('property') === 'og:title')
            {
                $data['title'] = $meta->getAttribute('content');
            }

            if ($meta->getAttribute('property') === 'og:description')
            {
                $data['description'] = $meta->getAttribute('content');
            }

            if ($meta->getAttribute('property') === 'og:video:secure_url' && empty($data['videoUrl']))
            {
                $data['videoUrl'] = $meta->getAttribute('content');
            }
        }

        return $data;
    }

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
                sprintf('<iframe src="%s" width="640" height="338" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>', $htmlData['videoUrl'])
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

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return 'vimeo';
    }


}