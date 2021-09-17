<?php

require_once("src/Form.php");

$json = json_decode(file_get_contents("forms/default.json"));

try 
{
    $form = new Form($json);
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
