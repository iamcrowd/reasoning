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

require_once("../../common/import_functions.php");

load("wicom.php", "../../common/");
load("owl2Importer.php", "../../common/");

use \SimpleXMLElement;

$wicom = new Wicom\OWL2Importer();


if (array_key_exists('owl', $_POST)){
    try{

        $xml = new SimpleXMLElement($_POST['owl']);
        $answer = $wicom->owl2importer($xml->asXML());
        echo $answer;

    }catch(Exception $e){
        http_response_code(500);
        echo $e->getMessage();
    }
}else{
    echo "Error, owl parameter not founded.";
}
?>
