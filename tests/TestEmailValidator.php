<?php

try
{
    $email_validator = new EmailValidator("invalid");
} catch(Exception $e) {}

assert(!isset($email_validator));

$email_validator = new EmailValidator("email");
assert($email_validator instanceof EmailValidator);
assert($email_validator->isValid(null) == false);
assert($email_validator->isValid(" ") == false);
assert($email_validator->isValid(125) == false);
assert($email_validator->isValid("malformed .email@address.invalid")
    == false);
assert($email_validator->isValid("just a string") == false);
assert($email_validator->isValid("first.last@institution.example"));
assert($email_validator->isValid("simple@address.example"));

?>
