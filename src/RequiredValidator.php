<?php

class RequiredValidator extends Validator
{
    function __construct(string $validatorCommand)
    {
        if($validatorCommand != "required")
            throw new Exception(
                "RequiredValidator initiated, but command was not 'required'"
            );
    }

    public function isValid(string $value): bool
    {
        if($value == null)
            return false;

        $value = trim($value);
        if($value == "")
            return false;

        return true;
    }

    public function getErrorString(): string
    {
        $translator = Translator::getInstance();
        return $translator->text("RequiredValidator.required_field");
    }
}

?>