<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   satisfiable.php

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

/**
   Return if the given diagram is satisfiable.
 */

require_once __DIR__ . '/../common/import_functions.php';
require_once __DIR__ . '/../json2json/v2tov1.php';
require_once __DIR__ . '/../common/uml.php';

use Json2Json\V2toV1;

// --------------------
// Check GET and POST parameters

if (!array_key_exists('json', $_POST)){
    echo "{\"error\": \"json parameter not founded.\"}";
    exit();
}

/**
 */
function convert_v2tov1($json){
    $conv = new V2toV1($json);
    return $conv->convert_str();
} // convert_v2tov1


$json = $_POST['json'];
$json_version = 1;
if (array_key_exists('json_version', $_GET)){
    switch ($_GET['json_version']){
        case "1":
            $json = $_POST['json'];
            break;
        case "2":
            $json_version = 2;
            $json = convert_v2tov1($_POST['json']);
            break;
            
    }
}

$reasoner = 'Konclude';
if (array_key_exists('reasoner', $_POST)){
    $reasoner = $_POST['reasoner'];
}

$encoding = 'berardi';
if (array_key_exists('encoding', $_POST)){
    $encoding = $_POST['encoding'];
}


// --------------------
// Execute the service

$wicom = new Wicom\UML_Wicom();

try{    
    $answer = $wicom->full_reasoning($json, $encoding, $reasoner);
    if ($answer != null){
        echo json_encode(
            ["answer" => json_decode($answer->to_json())]
        );
    }else{
        echo json_encode(
            ["error" => "no answer from the reasoner",
             "input" => [
                 "json" => $json,
                 "encoding" => $encoding,
                 "reasoner" => $reasoner,
                 "json_version" => $json_version,
             ],
            ]);
    }
}catch(\Exception $e){
    http_response_code(500);
    echo json_encode(
        ["error" => $e->getMessage(),
         "input" => [
             "json" => $json,
             "encoding" => $encoding,
             "reasoner" => $reasoner,
             "json_version" => $json_version,
         ],
        ]);
}

?>
