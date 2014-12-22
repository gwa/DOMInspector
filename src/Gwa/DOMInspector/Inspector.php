<?php
namespace Gwa\DOMInspector;

class Inspector extends Node
{
    protected $_html;
    protected $_domdoc;
    protected $_node;

    /**
     * @param string $html
     */
    public function __construct( $html )
    {
        $this->_html = $html;
        $this->_domdoc = new \DOMDocument();
        $this->_domdoc->preserveWhiteSpace = false;
        $this->_domdoc->loadHTML($html);
        $this->_node = $this->_domdoc->getElementsByTagName('body')->item(0);
    }
}
