<?php
use Gwa\DOMInspector\Inspector;
use Gwa\DOMInspector\Selector;

class SelectorTest extends PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $selector = new Selector('');
        $this->assertInstanceOf('Gwa\DOMInspector\Selector', $selector);
    }

    public function testCanMatchATagName()
    {
        $inspector = new Inspector('<span>foo</span>');
        $node = $inspector->children(0);

        $selector = new Selector('span');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('div');
        $this->assertFalse($selector->matches($node));
    }
}
