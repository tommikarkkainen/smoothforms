<?php

/*!
 * A class for HTML select fields
 */
class SelectField extends InputField
{
    protected array $options;
    private bool $strict_mode;

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

        $this->strict_mode = false;
        if(property_exists($obj, "strictmode"))
        {
            if( (gettype($obj->strictmode) == "boolean" && $obj->strictmode == true)
                || (gettype($obj->strictmode) == "string" && 
                strcmp(strtolower($obj->strictmode), "true") == 0 ))
                $this->strict_mode = true;
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
        $this->makeValidatorErrors($field);
        
        return $field;
    }

    /*!
     * Read the value submitted to this field, from the POST array. Write the
     * value into the $value member variable.
     * 
     * If the field is a select field, or if the field is a datalist AND it has
     * strict mode enabled, automatically check that the selected option is one
     * one of the allowed values. If not, the value is set as an empty string
     * that can be caught by a RequiredValidator if desired.
     */
    protected function readSubmissionValue()
    {
        if(isset($_POST[$this->name]))
        {
            if($this->type=="select" || $this->strict_mode)
            {
                $this->value = $this->getStrictValue($_POST[$this->name]);
            } else {
                $this->value = filter_var($_POST[$this->name],
                    FILTER_SANITIZE_STRING);
            }
        } else {
            $this->value = "";
        }

        /*! Read through all the options, and adjust the "selected" parameter
         * as necessary
         */
        foreach ($this->options as $option)
        {
            $option->selected = $option->value == $this->value;
        }
    }

    /*!
     * Read the user-input value in strict mode: only accept a value listed in
     * the option list of this field. Substitute other values with "".
     */    
    private function getStrictValue(string $value): string
    {
        $str = "";
        foreach($this->options as $option)
        {
            if($option->value == $value)
            {
                if($this->type == "select")
                    $str = $option->label;
                if($this->type == "datalist")
                    $str = $option->value;

                break;
            }
        }

        $str = filter_var($str, FILTER_SANITIZE_STRING);
        return $str;
    }
}

?>