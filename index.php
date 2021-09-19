<?php

spl_autoload_register(function ($class_name) {
    $try1 = "src/" . $class_name . '.php';
    $try2 = $class_name . '.php';
    if(file_exists($try1))
        include $try1;
    else
        include $try2;
});

$json = json_decode(file_get_contents("forms/default.json"));

try 
{
    $form = new Form($json);
} catch (Exception $e) {
    echo $e->getMessage();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo $form->makeForm();
        break;

    case 'POST':
        $form->processSubmission();
        break;
    
    default:
        throw new Exception("Unsupported HTTP method.");
        break;
}

?>
