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

        if(property_exists($obj, "validators"))
            $this->initValidators($obj->validators);
        else
            $this->initValidators(array());
    }

    public function makeField(): TagFactory
    {
        $field_id = "field_".$this->name;
        $field = parent::makeField();

        // Find the correct tag to fix by matching the id attribute
        $mod_field = $field->findById($field_id);
        $mod_field->setTagName("input");
        $mod_field->addAttribute("type", $this->type);

        $this->makeValidatorErrors($field);
        
        return $field;
    }
}

?>