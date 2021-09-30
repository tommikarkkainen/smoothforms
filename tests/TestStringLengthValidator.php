<?php


try
{
    $missingArgumentValidator = Validator::create("minlen");
    $invalidArgumentValidator = Validator::create("minlen:invalid");
}catch (Exception $e) {}

assert(!isset($missingArgumentValidator));
assert(!isset($invalidArgumentValidator));

$minlen = Validator::create("minlen:5");
$maxlen = Validator::create("maxlen:5");
assert($minlen instanceof StringLengthValidator);
assert($maxlen instanceof StringLengthValidator);

assert($minlen->isValid("aaaa") == false);
assert($minlen->isValid("aaaaa") == true);
assert($minlen->isValid("aaaaaa") == true);

assert($maxlen->isValid("aaaa") == true);
assert($maxlen->isValid("aaaaa") == true);
assert($maxlen->isValid("aaaaaa") == false);

?>
