<?php
namespace Gwa\DOMInspector;

class NodeList implements \Iterator
{
    protected $_node;
    protected $_nodes;
    protected $_position = 0;

    /**
     * @param Node
     */
    public function __construct( Node $node = null )
    {
        $this->_node = $node;
        $this->_nodes = array();
    }

    /**
     * @param Node
     */
    public function add( Node $node )
    {
    	array_push($this->_nodes, $node);
        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_nodes);
    }

    /**
     * Returns a node by index.
     * @param int
     * @return Node
     */
    public function get( $index )
    {
        return array_key_exists($index, $this->_nodes) ? $this->_nodes[$index] : null;
    }

    /**
     * Returns the first node in the list.
     * @return Node
     */
    public function first()
    {
    	return $this->get(0);
    }

    /**
     * Returns a node by index.
     * @param string|Selector $selector
     * @return NodeList
     */
    public function filter( $selector )
    {
        if (is_string($selector)) {
            $selector = new Selector($selector);
        }
        $list = new NodeList($this->_node);
        foreach ($this as $node) {
            if ($node->matches($selector)) {
                $list->add($node);
            }
        }
        return $list;
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
