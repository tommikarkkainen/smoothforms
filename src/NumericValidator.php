<?php

/*!
 * Can be used to check if user input is a number (either integer or a number
 * in general).
 */
class NumericValidator extends Validator
{
    private string $mode;

    function __construct(string $validatorCommand)
    {
        switch($validatorCommand)
        {
        case "numeric":
            $this->mode = "numeric";
            break;
        case "integer":
            $this->mode = "integer";
            break;

        default:
            throw new Exception(
                "NumericValidator initiated, but command was not 'numeric' or "
                ."'integer'."
            );
        }
    }

    public function isValid(mixed $value): bool
    {
        if($this->mode == "numeric")
        {
            return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
        }
        if($this->mode == "integer")
        {
            return filter_var($value, FILTER_VALIDATE_INT) !== false;
        }

        return false;
    }

    public function getErrorString(): string
    {
        $translator = Translator::getInstance();
        if($this->mode == "numeric")
            return $translator->text("NumericValidator.not_a_number");
        if($this->mode == "integer")
            return $translator->text("NumericValidator.not_an_integer");
    }
}

?>
