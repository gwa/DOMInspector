<?php
namespace Gwa\DOM;

class DOMInspectorNode
{
    protected $_node;

    protected $_children;

    public function __construct( \DOMNode $node )
    {
        $this->_node = $node;
    }

    /**
     * @return DOMInspectorNodeList
     */
    public function children( $index = null )
    {
        if (!isset($this->_children)) {
            $this->_children = new \Gwa\DOM\DOMInspectorNodeList($this);
            foreach ($this->_node->childNodes as $node) {
                if ($node->nodeType == XML_ELEMENT_NODE) {
                    $this->_children->add(new DOMInspectorNode($node));
                }
            }
        }
        return is_null($index) ? $this->_children : $this->_children->get($index);
    }

    /**
     * @return string
     */
    public function tagname()
    {
        return $this->_node->nodeName;
    }

    /**
     * Returns the value of the attribute with the name passed.
     * Returns NULL if attribute is not set.
     *
     * @return string
     */
    public function attr( $attr )
    {
        return $this->_node->hasAttribute($attr) ? $this->_node->getAttribute($attr) : null;
    }

    /**
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
     * @param int|string $a
     * @param string|NULL $b
     * @return boolean
     */
    public function contains( $a, $b = null )
    {
        if (is_int($a) && is_string($b)) {
            return $this->containsNum($a, $b);
        }
        $tagname = $a;
        foreach ($this->children() as $node) {
            if ($node->tagname() == $tagname) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $expectedcount
     * @param string $tagname
     * @return boolean
     */
    public function containsNum( $expectedcount, $tagname )
    {
        $count = 0;
        foreach ($this->children() as $node) {
            if ($node->tagname() == $tagname) {
                $count++;
            }
        }
        return $expectedcount == $count;
    }

    public function getDOMNode()
    {
        return $this->_node;
    }
}
