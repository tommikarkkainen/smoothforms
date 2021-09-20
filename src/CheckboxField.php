<?php

/*!
 * This class represents a checkbox form field or a radio button form field.
 * Creates exactly one field - to make the form function as intended, the user
 * is expected to create multiple fields with the same name. IDs are created
 * as field_<name>_<value>, with value having all non-alphanumeric characters
 * stripped.
 */
class CheckboxField extends InputField
{
    public function makeField(): TagFactory
    {
        $stripped_value = preg_replace("/[^[:alnum:][:space:]]/u",
            '', $this->value);
        $field_id = "field_".$this->name."_".$stripped_value;

        $div = new TagFactory("div");
        $div->addChild(new TagFactory("input", array(
            "type" => $this->type, "id" => $field_id, "name" => $this->name,
            "value" => $this->value
            ), true));
        $label = new TagFactory("label", array("for" => $field_id));
        $label->addChild(new TextElement($this->label));
        $div->addChild($label);
        $this->makeValidatorErrors($div);

        return $div;
    }
}

?>