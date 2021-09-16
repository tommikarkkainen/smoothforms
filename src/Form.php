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

        if(count($errors) == 0)
        {
            echo "Is valid form!";
        } else {
            foreach($errors as $error)
                echo $error; 
        }
        
    }
}

?>
