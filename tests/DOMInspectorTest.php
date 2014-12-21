<?php
use Gwa\DOM\DOMInspector;

class DOMInspectorTest extends PHPUnit_Framework_TestCase
{
    const HTML_VALID_1 = '<p class="lead">This is a test node <a href="#" class="btn btn-primary">with a <span>link</span></a></p>';

    public function testCanBeConstructed()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $this->assertInstanceOf('Gwa\DOM\DOMInspector', $inspector);
    }

    public function testIsADomInspectorNode()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $this->assertInstanceOf('Gwa\DOM\DOMInspectorNode', $inspector);
    }

    public function testHasADOMNode()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $node = $inspector->getDOMNode();
        $this->assertInstanceOf('\DOMNode', $node);
    }

    public function testCanReturnChildNodes()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $children = $inspector->children();
        $this->assertInstanceOf('Gwa\DOM\DOMInspectorNodeList', $children);
        $this->assertEquals(1, $children->length());

        $this->assertInstanceOf('Gwa\DOM\DOMInspectorNode', $children->get(0));
        $this->assertNull($children->get(1));
    }

    public function testCanReturnTheNodeName()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $this->assertEquals('p', $inspector->children()->get(0)->tagname());
        $this->assertEquals('a', $inspector->children()->get(0)->children()->get(0)->tagname());
    }

    public function testCanReturnAnAttributeValue()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $this->assertEquals('lead', $inspector->children(0)->attr('class'));
        $this->assertNull($inspector->children(0)->attr('notexist'));
    }

    public function testCanCheckIfANodeHasAClass()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $a = $inspector->children(0)->children(0);
        $this->assertTrue($a->hasClass('btn'));
        $this->assertTrue($a->hasClass('btn-primary'));
        $this->assertFalse($a->hasClass('notexist'));
        $this->assertFalse($a->children(0)->hasClass('notexist'));
    }

    public function testChildrenIsIterable()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $count = 0;
        foreach ($inspector->children() as $key => $value) {
            $this->assertInstanceOf('Gwa\DOM\DOMInspectorNode', $value);
            $count++;
        }
        $this->assertEquals(1, $count);
    }

    public function testCanCheckIfANodeContainsNodesOfAType()
    {
        $inspector = new DOMInspector(self::HTML_VALID_1);
        $this->assertTrue($inspector->contains('p'));
        $this->assertTrue($inspector->contains(1, 'p'));
        $this->assertFalse($inspector->contains(2, 'p'));
        $this->assertFalse($inspector->contains(1, 'a'));
        $this->assertTrue($inspector->children(0)->contains(1, 'a'));
    }
}
