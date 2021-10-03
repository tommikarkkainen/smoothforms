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
        $label = new TagFactory("label", array("for" => $field_id));
        $label->addChild (new TextElement($this->label.":"));
        $field->addChild($label);
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

    /*!
     * Returns the submitted data as a TagFactory, to be used in creating HTML
     * either for display in-browser, or for HTML emails.
     */
    public function makeFieldResponseHTML(): TagFactory
    {
        $div = new TagFactory("div");
        $p = new TagFactory("p");
        $label = new TagFactory("strong");
        $label->addChild(new TextElement($this->label.":"));
        $p->addChild($label);
        $div->addChild($p);

        $pvalue = new TagFactory("p");
        $pvalue->addChild(new TextElement($this->value));

        $div->addChild($pvalue);

        return $div;
    }

    /*!
     * Returns the submitted data as plain text so that it can be included in
     * plaintext emails.
     */
    public function makeFieldResponsePlain(): string
    {
        $str = $this->label . ":\r\n\r\n" . $this->value . "\r\n\r\n";
        return $str;
    }
}

?>