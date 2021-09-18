<?php

/*!
 * A class for HTML select fields
 */
class SelectField extends InputField
{
    protected array $options;

    public function __construct(object $obj)
    {
        $this->name = $obj->name;
        $this->label = $obj->label;
        $this->type = "select";

        if(property_exists($obj, "options"))
        {
            $this->options = $obj->options;
        } else {
            $this->options = array();
        }
    }

    public function makeField(): TagFactory
    {
        $field_id = "field_".$this->name;

        $field = new TagFactory("label", array("for" => $field_id));
        $field->addChild(new TextElement($this->label . ": "));
        $select_field = new TagFactory("select", array(
            "name" => $this->name,
            "id" => $field_id
        ));

        foreach($this->options as $option)
        {
            $attributes = array("value" => $option->value);
            if(property_exists($option, "selected") && $option->selected)
            {
                $attributes = array_merge($attributes, array("selected" => "true"));
            }
            $opt = new TagFactory("option", $attributes);
            $opt->addChild(new TextElement($option->label));
            $select_field->addChild($opt);
        }

        $field->addChild($select_field);
        
        return $field;
    }
}

?>