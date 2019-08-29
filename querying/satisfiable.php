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

require_once("../../common/import_functions.php");

load("wicom.php", "../../common/");

$wicom = new Wicom\Wicom();

if (array_key_exists('json', $_POST)){
    try{
        $reasoner = 'Konclude';
        if (array_key_exists('reasoner', $_POST)){
            $reasoner = $_POST['reasoner'];
        }
        $answer = $wicom->full_reasoning($_POST['json'], $reasoner);
        echo $answer->to_json();
    }catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
    }
}else{
    echo "Error, json parameter not founded.";
}
?>
