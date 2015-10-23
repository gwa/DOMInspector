DOMInspector
============

The DOMInspector provides PHP methods for traversing and inspecting nodes in HTML markup.

## Example Usage

Consider the following markup in the variable `$markup`:

~~~~html
<select name="fruit" class="big">
    <option value="1">apples</option>
    <option value="2" selected>oranges</option>
    <option value="3">pears</option>
    <option value="4">kiwis</option>
</select>
~~~~

In our unit tests we want to inspect the structure of the HTML.

~~~~php
// Create an Inspector instance, passing the markup.
$inspector = new \Gwa\DOMInspector\Inspector($markup);
~~~~

The inspector represents a node that contains the nodes in the markup passed into it.

We expect there should be single child node, the `select` element.

~~~~php
// (We are using the PHPUnit test framework.)

// Test that there is one node
$this->assertEquals(1, $inspector->children()->count());
$select = $inspector->children()->get(0);

// Test the "tag name" of the first node
$this->assertEquals('select', $select->tagname());

// Test that the select has the class `big`
$this->assertTrue($select->hasClass('big'));

// Test the `name` attribute value
$this->assertEquals('fruit', $select->attr('name'));
~~~~

The `select` element should expose four `option` nodes.

~~~~php
$this->assertTrue($select->contains(4, 'option'));
$this->assertEquals(4, $select->children()->count());
~~~~

----

## Selectors

A **selector** is a string with one of the following formats:

```
tag
.classname
#id
tag.classname
tag#id
tag#id.classname
```

## Methods

### Inspector / Node

#### `find($selector) NodeList`

Returns a `NodeList` containing all child nodes that match the selector.

#### `children($index = null) NodeList`

Returns a `NodeList` containing all _direct child_ nodes, or a single `Node` if an index is specified.

#### `tagname() string`

Returns the tag name of the node.

#### `id() string|null`

Returns the id attribute value of the node.

#### `attr($attr) string|null`

Returns the value of an attribute of the node.

#### `html() string`

Returns the "outer" HTML value of the node.

#### `hasClass($cssclass) boolean`

Assert whether the node has the class passed as an attribute.

#### `contains($selector) boolean`

Assert whether the node has one or more _direct child_ nodes that match the selector.

#### `containsDeep($selector) boolean`

Assert whether the node contains one or more child nodes that match the selector.

#### `containsNum($selector) boolean`

Assert whether the node has a certain number of _direct child_ nodes that match the selector.

#### `containsNumDeep($selector) boolean`

Assert whether the node contains a certain number of child nodes that match the selector.

## NodeList

The `NodeList` is a **flat list** of nodes. It is [iterable](http://php.net/manual/en/class.iterator.php), so you can do this:

```php
$blanks = [];
$links = $inspector->find('a');
foreach ($links as $link) {
    if ($link->attr('target') === '_blank') {
        $blanks[] = $link;
    }
}
```

#### `count() integer`

Returns the number of nodes in the list.

#### `get($index) Node`

Returns the Node at the zero-based index specified.

#### `first() Node`

Returns the first Node in the list.

#### `last() Node`

Returns the last Node in the list.

#### `filter() NodeList`

Returns a new NodeList created by filtering the current list using the selector passed.

----

## Tests

Run tests using `phpunit`.

~~~~bash
$ vendor/bin/phpunit -c tests/phpunit.xml tests
~~~~
