<?php

require_once("src/Form.php");

$json = json_decode(file_get_contents("forms/default.json"));
$form = new Form($json);

?>
