<?php

/*!
 * A class for handling textarea inputs
 */
class TextareaField extends InputField
{
    public function makeField(): TagFactory
    {
        $field_id = "field_".$this->name;
        $field = new TagFactory("textarea", array(
            "name" => $this->name,
            "id" => $field_id,
        ));

        if($this->value != null)
            $field->addChild(new TextElement($this->value));
        
        return $field;
    }
}

?>