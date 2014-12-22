<?php
use Gwa\DOMInspector\Inspector;

class InspectorTest extends PHPUnit_Framework_TestCase
{
    const HTML_PARAGRAPH = '<p class="lead">This is a test node <a href="#" class="btn btn-primary">with a <span>link</span></a></p>';
    const HTML_NESTED_LIST = '<ul><li class="foo">First Item</li><li>Second Item<ul><li class="foo">Nested Item</li></ul></li></ul>';

    public function testCanBeConstructed()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertInstanceOf('Gwa\DOMInspector\Inspector', $inspector);
    }

    public function testIsADomInspectorNode()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertInstanceOf('Gwa\DOMInspector\Node', $inspector);
    }

    public function testHasADOMNode()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $node = $inspector->getDOMNode();
        $this->assertInstanceOf('\DOMNode', $node);
    }

    public function testCanReturnChildNodes()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $children = $inspector->children();
        $this->assertInstanceOf('Gwa\DOMInspector\NodeList', $children);
        $this->assertEquals(1, $children->length());

        $this->assertInstanceOf('Gwa\DOMInspector\Node', $children->get(0));
        $this->assertNull($children->get(1));
    }

    public function testCanReturnTheNodeName()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertEquals('p', $inspector->children()->get(0)->tagname());
        $this->assertEquals('a', $inspector->children()->get(0)->children()->get(0)->tagname());
    }

    public function testCanReturnAnAttributeValue()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertEquals('lead', $inspector->children(0)->attr('class'));
        $this->assertNull($inspector->children(0)->attr('notexist'));
    }

    public function testCanCheckIfANodeHasAClass()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $a = $inspector->children(0)->children(0);
        $this->assertTrue($a->hasClass('btn'));
        $this->assertTrue($a->hasClass('btn-primary'));
        $this->assertFalse($a->hasClass('notexist'));
        $this->assertFalse($a->children(0)->hasClass('notexist'));
    }

    public function testChildrenIsIterable()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $count = 0;
        foreach ($inspector->children() as $key => $value) {
            $this->assertInstanceOf('Gwa\DOMInspector\Node', $value);
            $count++;
        }
        $this->assertEquals(1, $count);
    }

    public function testCanCheckIfANodeContainsNodesOfAType()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertTrue($inspector->contains('p'));
        $this->assertTrue($inspector->contains(1, 'p'));
        $this->assertFalse($inspector->contains(2, 'p'));
        $this->assertFalse($inspector->contains(1, 'a'));
        $this->assertTrue($inspector->children(0)->contains(1, 'a'));
    }

    public function testCanFindNestedNodesByTagName()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $spans = $inspector->find('span');
        $this->assertEquals(1, $spans->length());

        $inspector = new Inspector(self::HTML_NESTED_LIST);
        $lis = $inspector->find('li');
        $this->assertEquals(3, $lis->length());
    }
}
