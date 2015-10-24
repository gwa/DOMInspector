<?php
namespace Gwa\DOMInspector;

class NodeTextExtractor
{
    const BR = '|%BR%|';
    const P  = '|%P%|';

    /**
     * @var \DOMNode
     */
    protected $_node;

    /**
     * @param \DOMNode $node
     */
    public function __construct(\DOMNode $node)
    {
        $this->_node = $node;
    }

    /**
     * @return string
     */
    public function extract()
    {
        $text = $this->extractText($this->_node);

        // replace preceeding and trailing spaces
        $text = preg_replace('/^ +/', '', $text);
        $text = preg_replace('/ +$/', '', $text);

        // replace multiple spaces
        $text = preg_replace('/[ ]{2,}/', ' ', $text);

        // replace BRs with line breaks
        $text = $this->replaceTrimmed(self::BR, PHP_EOL, $text);

        // replace Ps with line breaks
        $text = $this->replaceTrimmed(self::P, PHP_EOL.PHP_EOL, $text);

        return trim($text);
    }

    private function replaceTrimmed($search, $replace, $subject)
    {
        $lines = array_map(
            function($line) {
                return trim($line);
            },
            explode($search, $subject)
        );
        return implode($replace, $lines);
    }

    private function extractText(\DOMNode $node)
    {
        $text = '';
        $trim = ['\t', '\n', '\r', PHP_EOL];

        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $nodetext = str_replace($trim, ' ', $child->nodeValue);
                $text .= $nodetext;
                continue;
            }

            if ($child->nodeName === 'br') {
                $text .= self::BR;
                continue;
            }

            $text .= $this->extractText($child);

            if ($child->nodeName === 'p') {
                $text .= self::P;
            }
        }

        return $text;
    }
}
