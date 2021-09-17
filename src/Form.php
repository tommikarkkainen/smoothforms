<?php

require_once("TCheckFields.php");

class Form {
    use CheckFields;

    private string $form_title;
    private string $form_template;
    private string $thankyou_template;
    private string $lang;
    private array $send_to;
    private array $fields;

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
}

?>
