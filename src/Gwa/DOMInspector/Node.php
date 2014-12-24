<?php
namespace Gwa\DOMInspector;

class Node
{
    protected $_node;

    protected $_children;

    public function __construct( \DOMNode $node )
    {
        $this->_node = $node;
    }

    /**
     * Returns a NodeList containing the child nodes of this node.
     * @return NodeList
     */
    public function children( $index = null )
    {
        if (!isset($this->_children)) {
            $this->parseChildren();
        }

        return is_null($index) ?
            $this->_children :
            $this->_children->get($index);
    }

    private function parseChildren()
    {
        $this->_children = new NodeList($this);
        foreach ($this->_node->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $this->_children->add(new Node($node));
            }
        }
    }

    /**
     * @param string|Selector $selector
     * @param NodeList $list used internally for recursion
     * @return NodeList
     */
    public function find( $selector, $list = null )
    {
        if (is_string($selector)) {
            $selector = new Selector($selector);
        }

        if (!isset($list)) {
            $list = new NodeList($this);
        } elseif ($this->matches($selector)) {
            $list->add($this);
        }

        foreach ($this->children() as $node) {
            $node->find($selector, $list);
        }

        return $list;
    }

    /**
     * Tests whether the node matches the selector passed.
     * @param  Selector $selector
     * @return boolean
     */
    public function matches( Selector $selector )
    {
        return $selector->matches($this);
    }

    /**
     * Returns the HTML "tag" of the node
     * @return string
     */
    public function tagname()
    {
        return $this->_node->nodeName;
    }

    /**
     * Returns the ID attribute of the node.
     * @return string
     */
    public function id()
    {
        return $this->attr('id');
    }

    /**
     * Returns the value of the attribute with the name passed.
     * Returns NULL if attribute is not set.
     *
     * @return string
     */
    public function attr( $attr )
    {
        return $this->_node->hasAttribute($attr) ?
            $this->_node->getAttribute($attr) :
            null;
    }

    /**
     * Return the (trimmed) node HTML as a string.
     * @return string
     */
    public function html()
    {
        return trim($this->getDOMNode()->ownerDocument->saveHTML($this->getDOMNode()));
    }

    /**
     * Asserts whether the node has the CSS class passed.
     * @return boolean
     */
    public function hasClass( $cssclass )
    {
        if (!$this->_node->hasAttribute('class')) {
            return false;
        }
        $classes = explode(' ', $this->_node->getAttribute('class'));
        return in_array($cssclass, $classes);
    }

    /**
     * Asserts whether this node contains child nodes by selector.
     * @param int|string $a
     * @param string|NULL $b
     * @return boolean
     */
    public function contains( $a, $b = null )
    {
        if (is_int($a) && is_string($b)) {
            return $this->containsNum($a, $b);
        }

        $selector = new Selector($a);

        foreach ($this->children() as $node) {
            if ($node->matches($selector)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Asserts if the node contains a certain number of child node
     * with the selector passed.
     *
     * @param int $expectedcount
     * @param string|Selector $selector
     * @return boolean
     */
    public function containsNum( $expectedcount, $selector )
    {
        if (is_string($selector)) {
            $selector = new Selector($selector);
        }

        $count = 0;
        foreach ($this->children() as $node) {
            if ($node->matches($selector)) {
                $count++;
            }
        }

        return $expectedcount == $count;
    }

    /**
     * Returns the actual \DOMNode instance.
     * @return \DOMNode
     */
    public function getDOMNode()
    {
        return $this->_node;
    }
}
