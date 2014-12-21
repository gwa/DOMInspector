<?php
namespace Gwa\DOM;

class DOMInspectorNodeList implements \Iterator
{
    protected $_node;
    protected $_nodes;
    protected $_position = 0;

    public function __construct( DOMInspectorNode $node = null )
    {
        $this->_node = $node;
        $this->_nodes = array();
    }

    /**
     * @param DOMInspectorNode
     */
    public function add( DOMInspectorNode $node )
    {
    	array_push($this->_nodes, $node);
        return $this;
    }

    /**
     * @return int
     */
    public function length()
    {
        return count($this->_nodes);
    }

    /**
     * @param int
     * @return DOMInspectorNode
     */
    public function get( $index )
    {
    	return array_key_exists($index, $this->_nodes) ? $this->_nodes[$index] : null;
    }

    /* -------- Implement Iterable interface -------- */

    public function rewind()
    {
        $this->_position = 0;
    }

    public function current()
    {
        return $this->_nodes[$this->_position];
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
        ++$this->_position;
    }

    public function valid()
    {
        return array_key_exists($this->_position, $this->_nodes);
    }

    /* -------- */
}
