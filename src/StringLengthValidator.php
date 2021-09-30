<?php

/*!
 * Can be used to check if user input is a valid email address
 */
class StringLengthValidator extends Validator
{
    private int $compareLength;
    private string $mode;

    function __construct(string $validatorCommand)
    {
        $cmdParts = explode(":", $validatorCommand);

        switch($cmdParts[0])
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
                    $cmdParts[0]."'"
                );
        }

        if(count($cmdParts) != 2)
        {
            throw new Exception(
                $cmdParts[0] . " takes exactly one parameter,".
                " but ". count($cmdParts) . " were supplied"
            );
        }

        $this->compareLength = intval($cmdParts[1]);
        
    }

    public function isValid(mixed $value): bool
    {
        $len = strlen($value);
        if($this->mode == "min")
            return $len >= $this->compareLength;
        if($this->mode == "max")
            return  $len <= $this->compareLength;
        
        $success = false;
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