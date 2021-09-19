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
        $this->type = $obj->type;

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
        $list_id = "list_".$this->name;

        $field = new TagFactory("label", array("for" => $field_id));
        $field->addChild(new TextElement($this->label . ": "));
        if($this->type == "datalist")
        {
            $input_field = new TagFactory("input", 
                array("id" => $field_id, "name" => $this->name,
                    "list" => $list_id), true);
            $field->addChild($input_field);
        }

        $select_field = new TagFactory($this->type, array(
            "name" => ($this->type=="select" ? $this->name : $list_id),
            "id" => ($this->type=="select" ? $field_id : $list_id)
        ));

        foreach($this->options as $option)
        {
            $attributes = array("value" => $option->value);
            if(property_exists($option, "selected") && $option->selected)
            {
                $attributes = array_merge($attributes, array("selected" => "true"));
            }
            
            $opt = new TagFactory("option", $attributes);
            if(property_exists($option, "label"))
                $opt->addChild(new TextElement($option->label));

            $select_field->addChild($opt);
        }

        $field->addChild($select_field);
        
        return $field;
    }
}

?>