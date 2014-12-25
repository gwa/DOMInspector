<?php
namespace Gwa\DOMInspector;

class Inspector extends Node
{
    protected $_html;
    protected $_domdoc;
    protected $_domdocnode;
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

    /**
     * @return string
     */
    public function getDoctype()
    {
        $doctype = $this->_domdoc->doctype;
        return $doctype ?
            $doctype->name :
            null;
    }

    /**
     * Returns a node representing the entire document.
     * @return Node
     */
    public function document()
    {
        if (!isset($this->_domdocnode)) {
            $this->_domdocnode = new Node($this->_domdoc->documentElement);
        }
        return $this->_domdocnode;
    }
}
