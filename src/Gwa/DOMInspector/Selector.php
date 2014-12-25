<?php
namespace Gwa\DOMInspector;

class Selector
{
    /**
     * @var string
     */
    private $_selector;

    /**
     * @var string
     */
    private $_tagname;

    /**
     * @var string
     */
    private $_id;

    /**
     * @var array
     */
    private $_classes;

    /**
     * @param string $selector
     */
    public function __construct( $selector )
    {
        $this->_selector = $selector;
        $this->parseSelector($selector);
    }

    /* ---- Parsing ---- */

    private function parseSelector( $selector )
    {
        $this->parseTagName($selector);
        $this->parseId($selector);
        $this->parseClasses($selector);
    }

    private function parseTagName( $selector )
    {
        $pattern = '/^[a-z1-6]+/';
        if (preg_match($pattern, $selector, $match)) {
            $this->_tagname = $match[0];
        }
    }

    private function parseId( $selector )
    {
        $pattern = '/#([\-_A-Za-z0-9]+)/';
        if (preg_match($pattern, $selector, $match)) {
            $this->_id = $match[1];
        }
    }

    private function parseClasses( $selector )
    {
        $pattern = '/\.([\-_A-Za-z0-9]+)/';
        if (preg_match_all($pattern, $selector, $matches)) {
            $this->_classes = $matches[1];
        }
    }

    /* ---- Matching ---- */

    /**
     * @param  Node $node
     * @return boolean
     */
    public function matches( Node $node )
    {
        return
            $this->matchesTagName($node) &&
            $this->matchesId($node) &&
            $this->matchesClasses($node);
    }

    private function matchesTagName( Node $node )
    {
        return !isset($this->_tagname) || $node->tagname() === $this->_tagname ?
            true :
            false;
    }

    private function matchesId( Node $node )
    {
        return !isset($this->_id) || $node->id() === $this->_id ?
            true :
            false;
    }

    private function matchesClasses( Node $node )
    {
        if (!isset($this->_classes)) {
            return true;
        }
        foreach ($this->_classes as $cssclass) {
            if (!$node->hasClass($cssclass)) {
                return false;
            }
        }
        return true;
    }
}
