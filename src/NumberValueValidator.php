<?php

/*!
 * Can be used to check if user input is a number, with a maximum or minimum
 * value.
 */
class NumberValueValidator extends ValidatorWithArguments
{
    private float $compare;
    private string $mode;

    function __construct(string $validatorCommand)
    {
        parent::__construct($validatorCommand);
        switch($this->arguments[0])
        {
            case "min":
                $this->mode = "min";
                break;

            case "max":
                $this->mode = "max";
                break;

            default:
                throw new Exception(
                    "Invalid command for NumberValueValidator: '".
                    $this->arguments[0]."'"
                );
        }

        if(!is_numeric($this->arguments[1]))
            throw new Exception("Compare value must be a number");
        $this->compare = floatval($this->arguments[1]);
    }

    public function isValid(mixed $value): bool
    {
        $val = (double) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION);
        if($this->mode == "min")
            return $val >= $this->compare;
        if($this->mode == "max")
            return $val <= $this->compare;
        
        return false;
    }

    public function getErrorString(): string
    {
        $translator = Translator::getInstance();
        $str = "";
        if($this->mode == "min")
            $str = $translator->text("NumberValueValidator.too_small");
        if($this->mode == "max")
            $str = $translator->text("NumberValueValidator.too_large");

        $errStr = sprintf($str, $this->compare);

        return $errStr;
    }
}

?>