DOMInspector
============

Provides methods for inspecting nodes in HTML markup.

~~~~php
$markup = '<p>A paragraph with a <a href="#" class="btn" data-foo="bar">link</a> in it</p>';
$dom = new Gwa\Dom\DOMInspector($markup);
$p = $dom->children()->get(0);
~~~~

## Tests

Run tests using `phpunit`.

~~~~bash
$ vendor/bin/phpunit -c tests/phpunit.xml tests
~~~~
