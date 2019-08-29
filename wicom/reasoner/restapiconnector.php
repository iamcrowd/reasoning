<?php
/*

   Copyright 2019 GILIA

   Author: GILIA

   restapiconnector.php

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

namespace Wicom\Reasoner;

load("connector.php");
load("config.php", "../../config/");

use Wicom\Reasoner\Connector;

/**
JSON API output

{id, uri, name, classes, objectproperties, dataproperties, disjointness, subclasses}

**/

class RestAPIConnector extends Connector{


    //TODO: Change PROGRAM_CMD and FILES_PATH into configuration variables.

    /**
       The Racer command to execute with all its parameters.
     */
    const PROGRAM_CMD = "http --form";

    /**
       Invoke RestAPI to get all ontologies

       @return an Array of ontologies
     */
    function get_ontologies(){
      $API_URL = $GLOBALS['config']['restful_api'];

      $commandline = RestAPIConnector::PROGRAM_CMD . " " . "GET" . " " . $API_URL;

      exec($commandline,$answer);

      return json_decode($answer[0])->results;
    }

    /**
       Look for a specific ontology

       @param $name A String with the name of one of the ontologies in the library

       @return an ontology object
     */

    function get_ontologyByName($name){
      if ($name != NULL){

        $API_URL = $GLOBALS['config']['restful_api'];
        $commandline = RestAPIConnector::PROGRAM_CMD . " " . "GET" . " " . $API_URL;
        exec($commandline,$answer);
        $ontologies = json_decode($answer[0])->results;

        $name_e = "";

        foreach ($ontologies as $onto){
          if (strcmp($onto->name, $name) == 0){
            return $onto;
          }else{
            $name_e = NULL;
          }
        }
        if ($name_e == NULL){
          throw new \Exception("Ontology Not Found");
        }

      } else{
        throw new \Exception("Invalid Ontology Name");
      }
    }


    /**
       Look for a specific class

       @param $name A String with the link of one of the classes in the library

       @return a class object
     */

    function get_ClassByLink($url){
      $class = null;
      if ($url != NULL){

        $commandline = RestAPIConnector::PROGRAM_CMD . " " . "GET" . " " . $url;
        exec($commandline,$answer);

        if ($answer != null){
            return json_decode($answer[0]);
        }else{
          throw new \Exception("Class Not Found");
        }
    }
  }



   function run($document){}

}

?>
