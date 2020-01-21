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

class UnderstandAPI {

    protected $connector = null;
    protected $ontology = null;

    function __construct(){
        $this->connector = new ConnectorAPI();
        $this->ontology = "";
        $this->class = "";
        $this->objprop = "";
        $this->dataprop = "";
        $this->disjoint = "";
        $this->subclass = "";
    }

    /**
      Given an api ID as URL, this function returns the ID number of such URL
    */
    function getIDfromAPIElementID($apiid){
      $an_array = preg_split("[/]", trim($apiid));
      $index = count($an_array) - 2;
      return $an_array[$index];
    }

    function getConnector(){
      return $this->connector;
    }

    function getOntologyName(){
      return $this->ontology["name"];
    }

    function getOntologyURI(){
      return $this->ontology["uri"];
    }

    function getClasses(){
      return $this->ontology["classes"];
    }

    function getObjectProperties(){
      return $this->ontology["objectproperties"];
    }

    function getDataProperties(){
      return $this->ontology["dataproperties"];
    }

    function getSubClasses(){
      return $this->ontology["subclasses"];
    }

    /**
      Return true if status is 200. Otherwise, false.
      @param a string containing to retrieve API
      @note
      "ontology"
      "ontology/id"
    */
    function status($param){
      $this->connector->validateHeader($param);
      $header = $this->connector->get_header();
      if (strcmp($header[0],"HTTP/1.1 200 OK") == 0){
        return true;
      } else {
        return false;
      }
    }

    /**
       Return a list of ontologies

       @return a JSON object with all of the ontologies in the library
     */
    function listOntologies(){
      if ($this->status("ontology")) {
        $this->connector->getOntologies();
        return $this->connector->get_col_answers();
      } else {
          echo "{\"ERROR\": \"restfulAPI returned 404 Not Found.\"}";
          exit();
      }
    }

    /**
       Return an ontology from library given an id

       @param an id number
       @return a JSON Array containing the ontology id
     */
    function getOntologyById($id){
      $string = "ontology/" . $id;
      if ($this->status($string)) {
        $this->connector->getOntologyById($id);
        $this->ontology = json_decode($this->connector->get_col_answers(),true);
        return $this->ontology;
      } else {
          echo "{\"ERROR\": \"restfulAPI returned 404 Not Found.\"}";
          exit();
      }
    }

    /**
       Return a class from the current ontology

       @param an id class number
       @return a JSON Array containing the class id
       Array
       (
       [ontology] => http://127.0.0.1:8000/ontology/12/
       [uri] => http://www.xfront.com/owl/ontologies/camera/#Window
       [name] => .Window
       [prefix] => camera
       [rdf_label] => []
       [rdf_comment] => []
       )

     */
    function getClassById($id){
      $string = "classes/" . $id;
      if ($this->status($string)) {
        $this->connector->getClassById($id);
        $this->class = json_decode($this->connector->get_col_answers(), true);
        return $this->class;
      } else {
          echo "{\"ERROR\": \"restfulAPI returned 404 Not Found.\"}";
          exit();
      }
    }

    function getClassName(){
      return $this->class["name"];
    }

    function getClassURI(){
      return $this->class["uri"];
    }

    function getClassPrefix(){
      return $this->class["prefix"];
    }

    /**
       Return a subclass from the current ontology

       @param an id subclass number
       @return a JSON Array containing the subclass id
       Array
       (
       [ontology] => http://127.0.0.1:8000/ontology/12/
       [parent] => http://127.0.0.1:8000/classes/497/
       [child] => http://127.0.0.1:8000/classes/556/
       )

     */
    function getSubClassById($id){
      $string = "subclasses/" . $id;
      if ($this->status($string)) {
        $this->connector->getSubClassById($id);
        $this->subclass = json_decode($this->connector->get_col_answers(), true);
        return $this->subclass;
      } else {
          echo "{\"ERROR\": \"restfulAPI returned 404 Not Found.\"}";
          exit();
      }
    }

    function getSubClassParent(){
      return $this->subclass["parent"];
    }

    function getSubClassChild(){
      return $this->subclass["child"];
    }


}
