<?php
/*!
 * Run all unit tests. When a new test file is added, add a call in this file.
 */

spl_autoload_register(function ($class_name) {
    $try1 = "src/" . $class_name . '.php';
    $try2 = $class_name . '.php';
    if(file_exists($try1))
        include $try1;
    else
        include $try2;
});

$translator = Translator::getInstance();
$translator->setLanguage("default");

include('TestRequiredValidator.php');
include('TestEmailValidator.php');
include('TestStringLengthValidator.php');
include('TestNumericValidator.php');
include('TestNumberValueValidator.php');
include('TestPatternValidator.php');

?>
