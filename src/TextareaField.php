<?php

/*!
 * A class for handling textarea inputs
 */
class TextareaField extends InputField
{
    public function makeField(): TagFactory
    {
        $field_id = "field_".$this->name;
        $field = new TagFactory("div");
        $textarea = new TagFactory("textarea", array(
            "name" => $this->name,
            "id" => $field_id,
        ));

        if($this->value != null)
            $textarea->addChild(new TextElement($this->value));

        $field->addChild($textarea);
        
        $this->makeValidatorErrors($field);

        return $field;
    }
}

?>