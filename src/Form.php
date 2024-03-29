<?php

require_once("TCheckFields.php");

/*!
 * A class representing a web form
 */
class Form {
    use CheckFields;
    use HoneypotCSS;

    private string $form_title;
    private string $form_template;
    private string $thankyou_template;
    private string $lang;
    private array $send_to;
    private string $from;
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
            "from",
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
        $this->from = $json->from;

        // set optional properties
        $this->lang = property_exists($json, "lang") ? $json->lang : "default";
        $translator = Translator::getInstance();
        $translator->setLanguage($this->lang);

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

        $tpl = new Template();
        $tpl->loadTemplate("./templates/".$this->form_template);
        $tpl->registerVariable("form_title", $this->form_title);
        $tpl->registerVariable("form_css", file_get_contents("./static/default.css"));
        $tpl->registerVariable("honeypot_css", Form::generateHoneypotCSS());
        $tpl->registerVariable("form", $formTag->makeHTML());

        $lang = $this->lang == "default" ? "en" : $this->lang;
        $tpl->registerVariable("lang", $lang);

        return $tpl->output();
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

        $translator = Translator::getInstance();
        $new_entry_string = sprintf($translator->text("Form.new_entry"),
            $this->form_title);

        $header_text = $new_entry_string;
        $plaintext .= $header_text."\r\n\r\n";
        $html_header = new TagFactory("h1");
        $html_header->addChild(new TextElement($new_entry_string));
        $html_section->addChild($html_header);

        foreach($this->fields as $field)
        {
            $html_section->addChild($field->makeFieldResponseHTML());
            $plaintext .= $field->makeFieldResponsePlain();
        }

        $tpl = new Template();
        $tpl->loadTemplate("./templates/".$this->thankyou_template);
        $tpl->registerVariable("form_title", $this->form_title);
        $tpl->registerVariable("form_css", file_get_contents("./static/default.css"));
        $tpl->registerVariable("form", $html_section->makeHTML());

        $addresses = implode(", ", $this->send_to);
        $from = "From: ".$this->from."\r\n";
        $sent = mail($addresses, $new_entry_string, $plaintext, $from);

        if($sent)
        {
            echo $tpl->output();
        } else {
            echo $translator->text("Form.send_failed");
        }
    }

}

?>
