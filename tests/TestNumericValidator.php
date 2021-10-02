<?php

$numeric = Validator::create("numeric");
$integer = Validator::create("integer");
assert($numeric instanceof NumericValidator);
assert($integer instanceof NumericValidator);

assert($numeric->isValid("72"));
assert($numeric->isValid("12.73"));
assert($numeric->isValid("NaN") === false);
assert($numeric->isValid("Hello") === false);

assert($integer->isValid("72"));
assert($integer->isValid("12.73") === false);
assert($integer->isValid("NaN") === false);
assert($integer->isValid("Hello") === false);

?>
