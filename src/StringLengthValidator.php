<?php

/*!
 * Can be used to check if user input is of an accepted length
 */
class StringLengthValidator extends ValidatorWithArguments
{
    private int $compareLength;
    private string $mode;

    function __construct(string $validatorCommand)
    {
        parent::__construct($validatorCommand);

        switch($this->arguments[0])
        {
            case "minlen":
                $this->mode = "min";
                break;

            case "maxlen":
                $this->mode = "max";
                break;

            default:
                throw new Exception(
                    "Invalid command for StringLengthValidator: '".
                    $this->arguments[0]."'"
                );
        }

        if(!is_numeric($this->arguments[1]))
            throw new Exception("Length argument has to be a number");
        $this->compareLength = intval($this->arguments[1]);
    }

    public function isValid(mixed $value): bool
    {
        $len = strlen($value);
        if($this->mode == "min")
            return $len >= $this->compareLength;
        if($this->mode == "max")
            return $len <= $this->compareLength;
        
        return false;
    }

    public function getErrorString(): string
    {
        $translator = Translator::getInstance();
        $str = "";
        if($this->mode == "min")
            $str = $translator->text("StringLengthValidator.too_short");
        if($this->mode == "max")
            $str = $translator->text("StringLengthValidator.too_long");

        $errStr = sprintf($str, $this->compareLength);

        return $errStr;
    }
}

?>