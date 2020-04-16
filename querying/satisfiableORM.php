<?php
/*

   Copyright 2016 GILIA

   Author: GILIA

   full.php

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
   Return reasoner responses.
 */

//require_once("../../common/import_functions.php");

require_once("../common/import_functions.php");

load("wicom.php", "../common/");
load("orm.php", "../common/");


$wicom = new Wicom\ORM_Wicom();


if (array_key_exists('json', $_POST)){
    try{

        $strategy = 'CrowdOrm';
        if (array_key_exists('strategy',$_REQUEST)){
            $strategy = $_REQUEST['strategy'];
        }

        $reasoner = 'Racer';
        if (array_key_exists('reasoner',$_REQUEST)){
            $reasoner = $_REQUEST['reasoner'];
        }

        $answer = $wicom->is_satisfiable($_POST['json'], $strategy, $reasoner);
        $json_answer = $answer -> to_json();
        /*
        $json_obj = json_decode($json_answer, true);
        print_r( $json_obj['satisfiable']['kb']);
        print_r( $json_obj['satisfiable']['classes']);
        print_r( $json_obj['unsatisfiable']['classes']);
        */
        echo $json_answer;

    }catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
    }
}else{
    echo "Error, json parameter not founded.";
}
?>
