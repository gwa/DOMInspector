<?php
namespace Gwa\DOMInspector;

class Node
{
    protected $_node;

    protected $_children;

    /**
     * @param \DOMNode $node
     */
    public function __construct(\DOMNode $node)
    {
        $this->_node = $node;
    }

    /**
     * Returns a NodeList containing the child nodes of this node.
     *
     * @return NodeList
     */
    public function children($index = null)
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
     * Returns a NodeList containing all child nodes that match the selector.
     *
     * @param string|Selector $selector
     * @return NodeList
     */
    public function find($selector)
    {
        if (is_string($selector)) {
            $selector = new Selector($selector);
        }

        return $this->doFind($selector, new NodeList($this));
    }

    /**
     * @param Selector $selector
     * @param NodeList $list
     * @return NodeList
     */
    protected function doFind(Selector $selector, $list)
    {
        if ($this->matches($selector)) {
            $list->add($this);
        }

        foreach ($this->children() as $node) {
            $node->doFind($selector, $list);
        }

        return $list;
    }

    /**
     * Tests whether the node matches the selector passed.
     *
     * @param  Selector $selector
     * @return boolean
     */
    public function matches(Selector $selector)
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
     * @return string|null
     */
    public function attr($attr)
    {
        return $this->_node->hasAttribute($attr) ?
            $this->_node->getAttribute($attr) :
            null;
    }

    /**
     * Returns the (trimmed) node HTML as a string.
     *
     * @return string
     */
    public function html()
    {
        return trim($this->getDOMNode()->ownerDocument->saveHTML($this->getDOMNode()));
    }

    /**
     * Returns the text value of the node.
     *
     * @return string
     */
    public function text()
    {
        return $this->getDOMNode()->nodeValue;
    }

    /**
     * Asserts whether the node has the CSS class passed.
     *
     * @return boolean
     */
    public function hasClass($cssclass)
    {
        if (!$this->_node->hasAttribute('class')) {
            return false;
        }

        $classes = explode(' ', $this->_node->getAttribute('class'));
        return in_array($cssclass, $classes);
    }

    /**
     * Asserts whether this node contains child nodes by selector.
     *
     * @param string $selector
     * @return boolean
     */
    public function contains($selector)
    {
        return $this->getContainsCount($selector) > 0;
    }

    /**
     * Asserts if the node contains a certain number of child node
     * with the selector passed.
     *
     * @param int $expectedcount
     * @param string|Selector $selector
     * @return boolean
     */
    public function containsNum($expectedcount, $selector)
    {
        return $expectedcount === $this->getContainsCount($selector);
    }

    /**
     * @param string|Selector $selector
     * @return integer
     */
    private function getContainsCount($selector)
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

        return $count;
    }

    /**
     * Asserts whether this node or its children contains nodes by selector.
     *
     * @param string|Selector $selector
     * @return boolean
     */
    public function containsDeep($selector)
    {
        return $this->find($selector)->count() > 0;
    }

    /**
     * Asserts whether this node or its children contains a certain number of nodes by selector.
     *
     * @param int $expectedcount
     * @param string|Selector $selector
     * @return boolean
     */
    public function containsNumDeep($expectedcount, $selector)
    {
        return $this->find($selector)->count() === $expectedcount;
    }

    /**
     * Returns the actual \DOMNode instance.
     *
     * @return \DOMNode
     */
    public function getDOMNode()
    {
        return $this->_node;
    }
}
