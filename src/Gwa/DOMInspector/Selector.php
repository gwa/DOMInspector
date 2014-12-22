<?php
namespace Gwa\DOMInspector;

class Selector
{
    /**
     * @var string
     */
    private $_selector;

    /**
     * @param string $selector
     */
    public function __construct( $selector )
    {
        $this->_selector = $selector;
    }

    /**
     * @param  Node $node
     * @return boolean
     */
    public function matches( Node $node )
    {
        return $node->tagname() == $this->_selector;
    }
}
