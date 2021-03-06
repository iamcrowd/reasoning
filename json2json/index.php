<?php 
/* 

   Copyright 2019 GILIA
   
   Author: GILIA 

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


require_once __DIR__ . '/../common/import_functions.php';
require_once __DIR__ . '/v1tov2.php';
require_once __DIR__ . '/v2tov1.php';

use Json2Json\v1tov2;
use Json2Json\v2tov1;

/**
   Return the usage through HTTP output.
 */
function show_usage($message){
    print_r($_POST);
    echo "Bad usage:\n";
    echo $message;
    echo "\nPlease correct this mistakes and try again.\n";
    echo "Synopsis:\n";
    echo "  GET : from=v1|v2&to=v2|v1&prefix_iri=true/false\n";
    echo "  POST: json='UML JSON STRING'\n";
} // show_usage

$prefix_iri = true;
if (array_key_exists('prefix_iri', $_GET)){
    $prefix_iri = $_GET['prefix_iri'] == 'true';
}

/**
   Convert an UML model from V1 to V2 JSON format.
 */
function convert_v1tov2(){
    $conv = new V1toV2($_POST['json']);
    if ($prefix_iri) {
        $conv->with_prefix_iris();
    }else{
        $conv->without_prefix_iris();
    }
    echo $conv->convert_str();
} // convert_v1tov2

/**
   Convert an UML model from V2 to V1 JSON format.
 */
function convert_v2tov1($prefix_iri){
    $conv = new V2toV1($_POST['json']);
    if ($prefix_iri) {
        $conv->with_prefix_iris();
    }else{
        $conv->without_prefix_iris();
    }
    echo $conv->convert_str();
} // convert_v2tov1

if (!array_key_exists('json', $_POST)){
    show_usage('json key not founded');
    exit();
}

if (array_key_exists('from', $_GET) and array_key_exists('to', $_GET)){
    $from = $_GET['from'];
    $to = $_GET['to'];
    
    if ($from == 'v1'){
        if ($to == 'v2'){
            // V1 -> v2
            convert_v1tov2($prefix_iri);
        }
    }else if ($from == 'v2'){
        if ($to == 'v1'){
            // V2 -> v1
            convert_v2tov1($prefix_iri);
        }
    }else{
        show_usage('from or to key not founded');
    }
    
}else{
    show_usage();
}

?>
