<?php /* 

         Copyright 2020 cnngimenez

         Author: cnngimenez

         index.php

         This program is free software: you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation, either version 3 of the License, or
         (at your option) any later version.

         This program is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.

         You should have received a copy of the GNU General Public License
         along with this program.  If not, see <http://www.gnu.org/licenses/>.
       */

require_once("../common/import_functions.php");

load("v1_validator.php");
load("v2_validator.php");

use JSONValidators\V1Validator;
use JSONValidators\V2Validator;

function show_usage($message){
    print_r($_POST);
    echo "Bad usage:\n";
    echo $message;
    echo "\nPlease correct this mistakes and try again.\n";
    echo "Synopsis:\n";
    echo "  GET: version=v1|v2\n";
    echo "  POST: json='THE JSON STRING'\n";
} // show_usage

if (!array_key_exists('json', $_POST)){
    show_usage('json key not founded');
    exit();
}

if (!array_key_exists('version', $_GET)){
    show_usage('version key not founded');
    exit();
}

$input = $_POST['json'];
$version = $_GET['version'];

$valid = null;
$errors = [];

if ($version == "v1"){

    $validator = new V1Validator($input);
    $valid = $validator->validate();
    
    if (!$valid){
        $errors = $validator->get_errors();
    }
    
}else if ($version == "v2"){

    
    $validator = new V2Validator($input);
    $valid = $validator->validate();
    
    if (!$valid){
        $errors = $validator->get_errors();
    }
    
    
}else{
    show_usage("unknown input version specified on key 'version'");
}

echo json_encode(
    [
        'valid' => $valid,
        'errors' => $errors
    ]);

?>
