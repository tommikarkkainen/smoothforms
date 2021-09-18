<?php

/*!
 * HTMLField is not a *real* form field, but allows adding arbitrary HTML
 * (e.g. for explanatory texts) into the form.
 */
class HTMLField extends FormField
{
    public function __construct(object $obj)
    {
        $this->name = $obj->name;
        $this->label = $obj->label;
        $this->type = $obj->type;
    }

    public function makeField(): TagFactory
    {
        return new TextElement($this->label);
    }
}

?>