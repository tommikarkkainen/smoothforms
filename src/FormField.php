<?php

class FormField {
    use CheckFields;

    protected string $name;
    protected string $label;
    protected mixed $value;
    protected string $type;
    private array $validators;

    public function makeField(): TagFactory
    {
        $field_id = "field_".$this->name;

        $field = new TagFactory("label", array("for" => $field_id));
        $field->addChild(new TextElement($this->label . ": "));
        $field->addChild(new TagFactory(
            $this->type,
            array(
                "value" => $this->value,
                "name" => $this->name,
                "id" => $field_id
            ), true
        ));

        return $field;
    }

    /*!
     * Initialize the array of validators
     */
    protected function initValidators($obj)
    {
        if(!isset($this->validators))
            $this->validators = array();

        if(is_array($obj))
        {
            foreach($obj as $o)
            {
                $this->initValidators($o);
            }
        } else {
            $new_validator = Validator::create($obj);
            array_push($this->validators, $new_validator);
        }
    }

    /*!
     * Run all validators associated with this form field. If the validator
     * array is not initialized (e.g. due to validation being irrelevant to the
     * field type, the validation is passed automatically.
     * 
     * @returns true if the field is valid (all validators) and false otherwise
     */
    public function validate(): bool
    {
        if(!isset($this->validators))
        {
            return true;
        }
        $valid = true;
        foreach($this->validators as $validator)
        {
            if(!$validator->isValid($this->value))
            {
                $valid = false;
            }
        }

        return $valid; 
    }
    
    static public function newFromObject($obj)
    {
        $required_fields = ["name", "type", "label"];
        $errors = FormField::CheckFields($obj, $required_fields);
        if(count($errors) > 0)
        {
            $err_msg = "";
            foreach($errors as $error)
                $err_msg .= "* ".$error."<br>\n"; 
            throw new Exception("Invalid form field:\n<br>".$err_msg);
        }

        // return an object with a class approprite for the field type
        switch ($obj->type) {
            
            // 'html' is not a form field, but a way of allowing the user to
            // add text or images etc. in the middle of the form.
            case 'html':
                return new HTMLField($obj);
                break;

            // input types (HTML5)
            case 'input':
            case 'button':
            case 'color':
            case 'date':
            case 'datetime-local':
            case 'email':
            case 'file':
            case 'hidden':
            case 'image':
            case 'month':
            case 'number':
            case 'password':
            case 'range':
            case 'reset':
            case 'search':
            case 'submit':
            case 'tel':
            case 'text':
            case 'time':
            case 'url':
            case 'week':
                return new InputField($obj);
                break;

            case 'radio':
            case 'checkbox':
                return new CheckboxField($obj);


            // other form elements:
            case 'textarea':
                return new TextareaField($obj);
                break;

            case 'select':
            case 'datalist':
                return new SelectField($obj);
                break;
                
            case 'button':
            case 'output':
            default:
                throw new Exception (
                    "Unknown form field type ".$obj->type." requested."
                );
                break;
        }
    }
}

?>