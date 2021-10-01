<?php

abstract class ValidatorWithArguments extends Validator
{
    protected int $num_arguments;
    protected array $arguments;

    function __construct(string $validatorCommand)
    {
        if(!isset($this->num_arguments))
            $this->num_arguments = 1;

        $cmdParts = explode(":", $validatorCommand);

        if(count($cmdParts) != $this->num_arguments + 1)
        {
            throw new Exception(
                $cmdParts[0] . " takes ".$this->num_arguments." parameter,".
                " but ". count($cmdParts) - 1 . " were supplied"
            );
        }

        $this->arguments = $cmdParts;
        
    }
}

?>