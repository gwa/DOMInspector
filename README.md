DOMInspector
============

The DOMInspector provides PHP methods for traversing and inspecting nodes in HTML markup.

## Example Usage

~~~~php
// Get HTML markup from some component in your application.
// Here we are using a (ficticious) class that represents an HTML select.

$select = new HTMLSelectElement();
$select->setName('fruit');
$select->setAttributes(array(
    'class' => 'big'
));
$select->setOptions(array(
    '1' => 'apples',
    '2' => 'oranges',
    '3' => 'pears',
    '4' => 'kiwis'
));
$select->setValue(2);

$markup = $select->renderHTML();
~~~~

We expect the generated markup to look like this:

~~~~html
<select name="fruit" class="big">
    <option value="1">apples</option>
    <option value="2" selected>oranges</option>
    <option value="3">pears</option>
    <option value="4">kiwis</option>
</select>
~~~~

In our unit tests we want to inspect the structure of the rendered HTML.

~~~~php
// Create an Inspector instance, passing the markup.
$inspector = new Gwa\DOMInspector\Inspector($markup);
~~~~

The inspector represents a node that contains the nodes in the markup passed into it.

We expect there should be single child node, the `select` element.

~~~~php
// (We are using the PHPUnit test framework.)

// Test that there is one node
$this->assertEquals(1, $inspector->children()->count());
$select = $inspector->children()->get(0);

// Test the tagname of the first node
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

## Tests

Run tests using `phpunit`.

~~~~bash
$ vendor/bin/phpunit -c tests/phpunit.xml tests
~~~~
