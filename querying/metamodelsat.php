<?php
/*

   Copyright 2019 GILIA

   Author: gilia

   metamodel_satisfiable.php

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

 */

require_once("../common/import_functions.php");

load("kf.php", "../common/");

// --------------------
// Check GET and POST parameters

  if (!array_key_exists('json', $_POST)){
      echo "{\"error\": \"json parameter not founded.\"}";
      exit();
  }

  $reasoner = 'Racer';
  //also works for  'Konclude'
  if (array_key_exists('reasoner', $_POST)){
    $reasoner = $_POST['reasoner'];
  }

  // cards is set to false
  $cards = false;
  if (array_key_exists('cards', $_POST)){
    $cards = $_POST['cards'];
  }

// --------------------
// Execute the service

  $kf = new Wicom\KF_Wicom();

  try{
    $answer = $kf->full_reasoning($_POST['json'], "metamodel", $reasoner, $cards);
    if ($answer != null){
        echo $answer;
    }else{
        echo "answer is null";
    }

  }catch(\Exception $e){
    http_response_code(500);
    echo json_encode(
        ["error" => $e->getMessage(),
         "input" => [
             "json" => $json,
             "reasoner" => $reasoner,
             "cards" => $cards,
         ],
       ]);
}

?>
