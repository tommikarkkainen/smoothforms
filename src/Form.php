<?php

require_once("TCheckFields.php");

/*!
 * A class representing a web form
 */
class Form {
    use CheckFields;

    private string $form_title;
    private string $form_template;
    private string $thankyou_template;
    private string $lang;
    private array $send_to;
    private array $fields;
    private bool $valid = false;

    /*!
     * Create a Form object from a generic class object
     * @param $json an object, e.g. returned by json_decode()
     */
    public function __construct(object $json)
    {
        $required_fields = [
            "formtitle",
            "formtemplate",
            "thankyoutemplate",
            "sendto",
            "fields"
        ];

        $errors = $this::CheckFields($json, $required_fields);

        if(count($errors) > 0)
        {
            $err_msg = "";
            foreach($errors as $error)
                $err_msg .= "* ".$error."<br>\n"; 
            throw new Exception("Invalid form:\n<br>".$err_msg);
        }

        // set mandatory properties
        $this->form_title = $json->formtitle;
        $this->form_template = $json->formtemplate;
        $this->thankyou_template = $json->thankyoutemplate;
        $this->send_to = $json->sendto;

        // set optional properties
        $this->lang = property_exists($json, "lang") ? $json->lang : "";

        $this->fields = array();
        foreach($json->fields as $field)
        {
            $newField = FormField::newFromObject($field);
            array_push($this->fields, $newField);
        }
        
    }

    /*!
     * Outputs the HTML code for the form.
     */
    public function makeForm()
    {
        $formTag = new TagFactory(
            "form",
            array(
                "method" => "POST",
                "action" => $_SERVER['REQUEST_URI']
            )
        );
        foreach($this->fields as $field)
            $formTag->addChild($field->makeField());
        return $formTag->makeHTML();
    }

    /*!
     * Process the submitted form data
     */
    public function processSubmission()
    {
        $this->validate();
        if($this->valid)
        {
            $this->sendForm();
        } else {
            echo $this->makeForm();
        }
    }

    /*!
     * Validate the form fields. Sets the $valid member variable according
     * to whether or not ALL form fields are valid (true) or not (false).
     * 
     * @returns true when all fields valid, false otherwise.
     */
    public function validate()
    {
        $valid = true;
        foreach($this->fields as $field)
        {
            $field_valid = $field->validate();
            if(!$field_valid)
            {
                $valid = false;
            }
        }

        $this->valid = $valid;
        return $valid;
    }

    /*!
     * Send the form data
     */
    private function sendForm()
    {
        if(!$this->valid)
        {
            throw new Exception (
                "Attempted to send a form that was not valid."
            );
        }

        $plaintext = "";
        $html_section = new TagFactory("section");

        $header_text = "New entry: " . $this->form_title;
        $plaintext .= $header_text."\r\n\r\n";
        $html_header = new TagFactory("h1");
        $html_header->addChild(new TextElement("New entry: " . $this->form_title));
        $html_section->addChild($html_header);

        foreach($this->fields as $field)
        {
            $html_section->addChild($field->makeFieldResponseHTML());
            $plaintext .= $field->makeFieldResponsePlain();
        }

        $pt = new TagFactory("pre");
        $pt->addChild(new TextElement($plaintext));
        $html_section->addChild($pt);
        echo $html_section->makeHTML();
    }

}

?>
