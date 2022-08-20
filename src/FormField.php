<?php

class FormField {
    use CheckFields;

    protected string $name;
    protected string $label;
    protected $value;
    protected string $type;
    private array $validators;
    protected array $validator_errors;

    public function makeField(): TagFactory
    {
        $field_id = "field_".$this->name;
        $no_label_for = array("submit");

        $labeltext = in_array($this->type, $no_label_for)
            ? "" : $this->label.": ";

        $field = new TagFactory("label", array("for" => $field_id));
        $field->addChild(new TextElement($labeltext));
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
        $this->readSubmissionValue();

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
                if(!isset($this->validator_errors))
                    $this->validator_errors = array();
                array_push($this->validator_errors, $validator->getErrorString());
            }
        }

        return $valid; 
    }

    /*!
     * Read the value submitted to this field, from the POST array. Write the
     * value into the $value member variable, overriding any existing (default)
     * value.
     */
    protected function readSubmissionValue()
    {
        if(isset($_POST[$this->name]))
        {
            $val = $_POST[$this->name];
            $val = htmlspecialchars($val);
            $this->value = $val;
        } else {
            $this->value = "";
        }
    }

    /*!
     * Add validator errors to user output. Function is to be called from child
     * classes.
     */
    protected function makeValidatorErrors(TagFactory $element)
    {
        if(isset($this->validator_errors))
        {
            foreach($this->validator_errors as $error)
            {
                $errTag = new TagFactory("span", array (
                    "class" => "form-validator-error"
                ));
                $errTag->addChild(new TextElement($error));
                $element->addChild($errTag);
            }
        }
    }

    /*!
     * Returns the submitted data as a TagFactory, to be used in creating HTML
     * either for display in-browser, or for HTML emails.
     */
    public function makeFieldResponseHTML(): TagFactory
    {
        $p = new TagFactory("p");
        $label = new TagFactory("strong");
        $label->addChild(new TextElement($this->label.": "));
        $p->addChild($label);
        $p->addChild(new TextElement($this->value));

        return $p;
    }

    /*!
     * Returns the submitted data as plain text so that it can be included in
     * plaintext emails.
     */
    public function makeFieldResponsePlain(): string
    {
        $str = $this->label . ": " . $this->value . "\r\n\r\n";
        return $str;
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

            case 'honeypot':
                return new HoneypotField($obj);
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