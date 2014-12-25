<?php
use Gwa\DOMInspector\Inspector;

class InspectorTest extends PHPUnit_Framework_TestCase
{
    const HTML_PARAGRAPH = '<p class="lead" id="my-paragraph">This is a test node <a href="#" class="btn btn-primary">with a <span>link</span></a></p>';
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

    public function testCanReturnNodeHTML()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertEquals(self::HTML_PARAGRAPH, $inspector->children(0)->html());

        $inspector = new Inspector(self::HTML_NESTED_LIST);
        $this->assertEquals('<li class="foo">First Item</li>', $inspector->find('li.foo')->first()->html());
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

    public function testCanReturnTheNodeId()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertEquals('my-paragraph', $inspector->children(0)->id());
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

    public function testHasIterableChildren()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $count = 0;
        foreach ($inspector->children() as $key => $value) {
            $this->assertInstanceOf('Gwa\DOMInspector\Node', $value);
            $count++;
        }
        $this->assertEquals(1, $count);
    }

    public function testCanFilterChildren()
    {
        $inspector = new Inspector(self::HTML_NESTED_LIST);
        $ul = $inspector->children(0);
        $lis = $ul->children();
        $this->assertEquals(2, $lis->length());
        $filtered = $lis->filter('.foo');
        $this->assertEquals(1, $filtered->length());
    }

    public function testCanCheckIfANodeContainsNodesBySelector()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $this->assertTrue($inspector->contains('p'));
        $this->assertTrue($inspector->contains('p.lead'));
        $this->assertTrue($inspector->contains('.lead'));
        $this->assertTrue($inspector->contains(1, 'p'));
        $this->assertTrue($inspector->contains(1, 'p.lead'));
        $this->assertTrue($inspector->contains(1, '.lead'));

        $this->assertFalse($inspector->contains(2, 'p'));
        $this->assertFalse($inspector->contains(1, 'a'));

        $this->assertTrue($inspector->children(0)->contains(1, 'a'));
    }

    public function testCanFindNestedNodesBySelector()
    {
        $inspector = new Inspector(self::HTML_PARAGRAPH);
        $spans = $inspector->find('span');
        $this->assertEquals(1, $spans->length());

        $inspector = new Inspector(self::HTML_NESTED_LIST);
        $lis = $inspector->find('li');
        $this->assertEquals(3, $lis->length());

        $inspector = new Inspector(self::HTML_NESTED_LIST);
        $lis = $inspector->find('li.foo');
        $this->assertEquals(2, $lis->length());
    }

    public function testCanInspectAFullHtmlPage()
    {
        $markup = file_get_contents(__DIR__.'/fixtures/html5.html');
        $inspector = new Inspector($markup);
        $this->assertEquals('html', $inspector->getDoctype());

        // get the title
        $title = $inspector->document()->find('head')->first()->find('title')->first()->text();
        $this->assertEquals('HTML 5 Test Page', $title);

        // get the charset
        $charset = $inspector->document()->find('head')->first()->find('meta')->first()->attr('charset');
        $this->assertEquals('utf-8', $charset);

        // get the H1 text
        $h1 = $inspector->find('h1')->first()->text();
        $this->assertEquals($h1, $title);
    }
}
