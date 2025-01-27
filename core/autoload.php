<?php
spl_autoload_register(function ($className) {
    // Assume a directory structure where classes are in a "classes" folder
    $classFileController = "core/Controllers/" . ucfirst($className) . '.php';
    $classFileModel = "core/Model/" . ucfirst($className) . '.php';
    $classFileFunctionality = "core/Functionality/" . ucfirst($className) . '.php';

    if (file_exists($classFileController)) {
           require $classFileController;
    } elseif (file_exists($classFileModel)) {
       require $classFileModel;
    }
    elseif (file_exists($classFileFunctionality)) {
       require $classFileFunctionality;
    }
    
    else {
           echo json_encode(['error' => 'the called instance does not exist']);
    }
});


function show($data) {
   
   echo'<pre>';
   print_r($data);
   echo'</pre>';
}