<?php
/*

   Copyright 2020 GILIA

   Author: GILIA

   crowdAPI.php

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

namespace Wicom\Importer;

use function \load;
use function \json_decode;

load("config.php", "../../../config/");

class ConnectorAPI {

    /**
     The last answers stored from the reasoner.
   */
   protected $col_answers = [];
   protected $header = [];

   function __construct(){
     $this->header = [];
     $this->col_answers = [];
   }

   function get_col_answers(){
       return $this->col_answers[0];
   }

   function get_header(){
       return $this->header[0];
   }

    //TODO: Change PROGRAM_CMD and FILES_PATH into configuration variables.

    /**
       API command to execute with all its parameters.
     */
    const PROGRAM_CMD = "http ";
    const PROGRAM_PARAMS = "--form";

    /* GET, POST, DELETE */
    /*
    /ontology/
    /ontology/id/
    /classes/
    /objectproperties/
    /dataproperties/
    */


    function validateHeader($param){
      $list_path = "";
      $api_url = $GLOBALS['config']['api_url'];

      $list_path .= ConnectorAPI::PROGRAM_CMD . "--headers";
      $commandline = $list_path . " " . $api_url . $param . "/";

      exec($commandline, $answer);
      array_push($this->header, $answer);

    }

    /**
       Call API in order to list all ontologies
     */
    function getOntologies(){
      $list_path = "";
      $api_url = $GLOBALS['config']['api_url'];
      $uuid = uniqid();
      $list_path .= ConnectorAPI::PROGRAM_CMD . ConnectorAPI::PROGRAM_PARAMS;
      $commandline = $list_path . " " . "GET" . " " . $api_url . "ontology/";

      exec($commandline, $answer);
      $this->col_answers = [];
      array_push($this->col_answers, join($answer));
    }

    /**
       Call API in order to get an ontology by id
     */
    function getOntologyById($id){
      $list_path = "";
      $api_url = $GLOBALS['config']['api_url'];
      $uuid = uniqid();
      $list_path .= ConnectorAPI::PROGRAM_CMD . ConnectorAPI::PROGRAM_PARAMS;
      $commandline = $list_path . " " . "GET" . " " . $api_url . "ontology/" . $id . "/";

      exec($commandline, $answer);
      $this->col_answers = [];
      array_push($this->col_answers, join($answer));
    }

    /**
       Call API in order to get a class by id
     */
    function getClassById($id){
      $list_path = "";
      $api_url = $GLOBALS['config']['api_url'];
      $uuid = uniqid();
      $list_path .= ConnectorAPI::PROGRAM_CMD . ConnectorAPI::PROGRAM_PARAMS;
      $commandline = $list_path . " " . "GET" . " " . $api_url . "classes/" . $id . "/";

      exec($commandline, $answer);
      $this->col_answers = [];
      array_push($this->col_answers, join($answer));
    }

    /**
       Call API in order to get a objectproperty by id
     */
    function getObjectPropertyById($id){
      $list_path = "";
      $api_url = $GLOBALS['config']['api_url'];
      $uuid = uniqid();
      $list_path .= ConnectorAPI::PROGRAM_CMD . ConnectorAPI::PROGRAM_PARAMS;
      $commandline = $list_path . " " . "GET" . " " . $api_url . "objectproperties/" . $id . "/";

      exec($commandline, $answer);
      $this->col_answers = [];
      array_push($this->col_answers, join($answer));
    }

    /**
       Call API in order to get a class by id
     */
    function getSubClassById($id){
      $list_path = "";
      $api_url = $GLOBALS['config']['api_url'];
      $uuid = uniqid();
      $list_path .= ConnectorAPI::PROGRAM_CMD . ConnectorAPI::PROGRAM_PARAMS;
      $commandline = $list_path . " " . "GET" . " " . $api_url . "subclasses/" . $id . "/";

      exec($commandline, $answer);
      $this->col_answers = [];
      array_push($this->col_answers, join($answer));
    }

}
