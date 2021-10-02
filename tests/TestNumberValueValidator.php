<?php


try
{
    $missingArgumentValidator = Validator::create("min");
}catch (Exception $e) {}
assert(!isset($missingArgumentValidator));

try
{
    $invalidArgumentValidator = Validator::create("min:invalid");
}catch (Exception $e) {}
assert(!isset($invalidArgumentValidator));

$min = Validator::create("min:5");
$max = Validator::create("max:5");
$fmin = Validator::create("min:24.5");
assert($min instanceof NumberValueValidator);
assert($max instanceof NumberValueValidator);
assert($fmin instanceof NumberValueValidator);

assert($min->isValid("4") === false);
assert($min->isValid("4.5") === false);
assert($min->isValid("5"));
assert($min->isValid("5.5"));
assert($min->isValid("6"));

assert($max->isValid("4"));
assert($max->isValid("4.5"));
assert($max->isValid("5"));
assert($max->isValid("5.5") === false);
assert($max->isValid("6") === false);

assert($fmin->isValid("23.5") === false);
assert($fmin->isValid("24") === false);
assert($fmin->isValid("24.5"));
assert($fmin->isValid("25"));
assert($fmin->isValid("25.0"));

assert(strcmp($fmin->getErrorString(), "Must be at least 24.5") == 0);
assert(strcmp($max->getErrorString(), "Must be no more than 5") == 0);

?>
