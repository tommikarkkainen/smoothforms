<?php

/*!
 * Can be used to check if user input is a valid email address
 */
class EmailValidator extends Validator
{
    function __construct(string $validatorCommand)
    {
        if($validatorCommand != "email")
            throw new Exception(
                "EmailValidator initiated, but command was not 'email'"
            );
    }

    public function isValid(mixed $value): bool
    {
        if($value == null)
            return false;

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function getErrorString(): string
    {
        $translator = Translator::getInstance();
        return $translator->text("EmailValidator.invalid_email");
    }
}

?>
