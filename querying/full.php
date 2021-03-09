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

require_once __DIR__ . '/../../common/import_functions.php';

require_once __DIR__ . '/../../common/wicom.php';
require_once __DIR__ . '/../../common/uml.php';

$wicom = new Wicom\UML_Wicom();


if (array_key_exists('json', $_POST)){
    try{

        $strategy = 'crowd';
        if (array_key_exists('strategy',$_REQUEST)){
            $strategy = $_REQUEST['strategy'];
        }

        $reasoner = 'Racer';
        if (array_key_exists('reasoner',$_REQUEST)){
            $reasoner = $_REQUEST['reasoner'];
        }

        $answer = $wicom->full_reasoning($_POST['json'], $strategy, $reasoner);
        echo $answer->to_json();

    }catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
    }
}else{
    echo "Error, json parameter not founded.";
}
?>
