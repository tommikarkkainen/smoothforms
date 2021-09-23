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

    /*!
     * Read the value submitted to this field, from the POST array. Write the
     * value into the $value member variable. The JSON-specified $value will be
     * overridden. The overridden value can be identified by the fact that the
     * CheckboxField::readSubmissionValue writes a bool value.
     */
    protected function readSubmissionValue()
    {
        if(isset($_POST[$this->name]))
        {
            if(strcmp($this->value, $_POST[$this->name]) == 0)
            {
                $this->value = true;
            } else {
                $this->value = false;
            }
        } else {
            $this->value = false;
        }
    }

    private function getCheckboxString()
    {
        $str = "";
        if($this->type == "checkbox")
        {
            $str = $this->value === true ? "[x] " : "[ ] ";
        }

        if($this->type == "radio")
        {
            $str = $this->value === true ? "(*) " : "( ) ";            
        }

        if($str != "")
            $str .= $this->label;

        return $str;
    }

    /*!
     * Returns the submitted data as a TagFactory, to be used in creating HTML
     * either for display in-browser, or for HTML emails.
     */
    public function makeFieldResponseHTML(): TagFactory
    {
        $cbstr = $this->getCheckboxString();
        $p = new TagFactory("p");
        $p->addChild(new TextElement($cbstr));

        return $p;
    }

    /*!
     * Returns the submitted data as plain text so that it can be included in
     * plaintext emails.
     */
    public function makeFieldResponsePlain(): string
    {
        return $this->getCheckboxString() . "\r\n\r\n";
        
        return $str;
    }
}

?>