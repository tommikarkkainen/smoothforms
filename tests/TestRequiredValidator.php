<?php

try
{
    $required_validator = new RequiredValidator("invalid");
} catch(Exception $e) {}

assert(!isset($required_validator));

$required_validator = new RequiredValidator("required");
assert($required_validator instanceof RequiredValidator);
assert($required_validator->isValid(null) == false);
assert($required_validator->isValid("") == false);
assert($required_validator->isValid(" ") == false);
assert($required_validator->isValid(125));
assert($required_validator->isValid("This is a string."));

?>