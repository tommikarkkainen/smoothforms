<?php

trait CheckFields {

    // Check if $obj as all fieldnames present in $fields. Returns an
    // array with error messages (one error for each missing field).
    // Returns an empty array if no fields are missing
    protected static function CheckFields(object $obj, array $fields): array
    {
        $errors = array();

        foreach($fields as $field)
        {
            if(!property_exists($obj, $field))
                array_push($errors, "Missing field '".$field."'");
        }

        return $errors;
    }
}

?>
