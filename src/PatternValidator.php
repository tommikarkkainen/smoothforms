<?php

/*!
 * Can be used to check if user input matches a pattern
 */
class PatternValidator extends ValidatorWithArguments
{
    private string $pattern;

    function __construct(string $validatorCommand)
    {
        parent::__construct($validatorCommand);

        if(strcmp($this->arguments[0], "pattern") != 0)
        {
            throw new Exception(
                "Invalid command for PatternValidator: '".
                $this->arguments[0]."'"
            );
        }

        $this->pattern = $this->arguments[1];
    }

    public function isValid(mixed $value): bool
    {
        return preg_match($this->pattern, $value);
    }

    public function getErrorString(): string
    {
        $translator = Translator::getInstance();
        return $translator->text("PatternValidator.no_match");
    }
}

?>
