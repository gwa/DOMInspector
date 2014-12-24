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

    public function testCanMatchAClassName()
    {
        $inspector = new Inspector('<span class="foo qux-qux">bar</span>');
        $node = $inspector->children(0);

        /* ---- */

        $selector = new Selector('span.foo');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('.foo');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('span.qux-qux');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('.qux-qux');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('.foo.qux-qux');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('span.foo.qux-qux');
        $this->assertTrue($selector->matches($node));

        /* ---- */

        $selector = new Selector('div.foo');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('span.bar');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('.foo.qux-qux.bar');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('span.foo.qux-qux.bar');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('div');
        $this->assertFalse($selector->matches($node));
    }

    public function testCanMatchAnId()
    {
        $inspector = new Inspector('<span class="foo qux-qux" id="baz">bar</span>');
        $node = $inspector->children(0);

        /* ---- */

        $selector = new Selector('#baz');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('span#baz');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('.foo');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('.foo#baz');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('#baz.foo');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('span#baz.qux-qux');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('#baz.qux-qux');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('#baz.foo.qux-qux');
        $this->assertTrue($selector->matches($node));

        $selector = new Selector('span#baz.foo.qux-qux');
        $this->assertTrue($selector->matches($node));


        $selector = new Selector('div.foo');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('span.bar');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('#notexist.foo.qux-qux.bar');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('span.foo.qux-qux.bar');
        $this->assertFalse($selector->matches($node));

        $selector = new Selector('div');
        $this->assertFalse($selector->matches($node));
    }
}
