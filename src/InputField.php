<?php

class InputField extends FormField
{
    public function __construct(object $obj)
    {
        $this->name = $obj->name;
        $this->label = $obj->label;
        $this->type = $obj->type;

        if(property_exists($obj, "value"))
            $this->value = $obj->value;
        else
            $this->value = null;

        $this->validators = array();

    }

    protected function makeLabel():string { return ""; }
    protected function makeTag():string { return ""; }
}

?>