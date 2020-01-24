<?php
/*

   Copyright 2020

   Author: GILIA

   metajsondocument.php

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

namespace Wicom\Translator\Documents;

use function \load;
load('jsondocument.php');

/**
   JSON structure of KF metamodel instances to be imported in crowd

   @author GILIA
   @license GPLv3
 */
class MetaJSONDocument extends JSONDocument{

    /**
       The content in PHP array and hashes.

       This will be translated directly into JSON.
    */
    protected $content = NULL;

    /**
       Number used to count the amount of subsumptions.

       This is used for giving a unique name to each subsumption created.
    */
    protected $card_number_id = 0;

    /**
       If the ontology IRI is not provided, this will be used as the default.
    */
    protected $default_ontologyIRI = "http://crowd.fi.uncoma.edu.ar/kb1#";

    /**
       Constructor. It defines the base template for the JSON document.

       {
       ["Entity type"]=> array(3)
        { ["Object type"]=> array(2) {
            [0]=> string(37) "http://crowd.fi.uncoma.edu.ar/kb1#Dog"
            [1]=> string(40) "http://crowd.fi.uncoma.edu.ar/kb1#Person" }
          ["Data type"]=> array(1) {
            [0]=> string(48) "http://www.w3.org/2001/XMLSchema-instance#String" }
          ["Value property"]=> array(1) {
            [0]=> array(3) {
              ["name"]=> string(50) "http://www.w3.org/2001/XMLSchema-instance#PropName"
              ["domain"]=> array(1) {
                [0]=> string(40) "http://crowd.fi.uncoma.edu.ar/kb1#Person" }
              ["value type"]=> string(38) "http://crowd.fi.uncoma.edu.ar/kb1#Name"}
            }
          }
        }
    */
    function __construct(){
	     $this->content = [
	        "Entity type" => [
            "Object type" => [],
	          "Data type" => [],
	          "Value property" => [],
	        ],
          "Role" => [],
          "Relationship" => [
            "Subsumption" => [],
            "Relationship" => [],
            "Attributive Property" => []
          ],
          "Constraints" => [
            "Disjointness constraints" => [
              "Disjoint object type" => [],
              "Disjoint role" => []
            ],
            "Completeness constraints" => [],
            "Cardinality constraints" => [
              "Object type cardinality" => [],
              "Attibutive property cardinality" => []
            ]
          ]
        ];
    }

    function to_json(){
	     return json_encode($this->content);
    }

    /**
       Insert Object Types

       @param $otname {string} The object type name.
    */
    public function insert_object_type($otname){
	     array_push($this->content["Entity type"]["Object type"], $otname);
    }

    /**
       Insert Roles

       @param $rolename {string} The role name.
       @param $domain {string} The role domain name (relationship).
       @param $range {string} The role range name. (entity type)
       @param $card_min {string} The role min cardinality.
       @param $card_max {string} The role max cardinality.
    */
    public function insert_roles($rolename, $domain, $range, $card_min, $card_max){
      $data["rolename"] = $rolename;
      $data["relationship"] = $domain;
      $data["entity type"] = $range;
      $data["object type cardinality"] = [];
      $id_card = $this->insert_object_type_cardinality($card_min, $card_max);
      array_push($data["object type cardinality"], $id_card);
      array_push($this->content["Role"], $data);
    }

    /**
       Insert Object Type Cardinalities

       @param $card_min {string} A min card.
       @param $card_max {string} A max card.

       @return an card id to be referenced from roles
    */
    public function insert_object_type_cardinality($card_min = "0", $card_max = "N"){
      $this->card_number_id = $this->card_number_id + 1;
      $data["name"] = "obj type card number_". $this->card_number_id;
      $data["minimum"] = $card_min;
      $data["maximum"] = $card_max;
      array_push($this->content["Constraints"]["Cardinality constraints"]["Object type cardinality"], $data);
      return $data["name"];
    }



    /**
       Insert a Subsumptions.

       @param $classes {array} ["name1", "name2",...,"nameN"]
       @param $parent {string} "parent1"
       @param $constraints ["disjont","covering"]
     **/
    function insert_subsumption($parent, $child, $id, $compl = "", $disj = ""){
      $data["name"] = $id;
      $data["entity parent"] = $parent;
      $data["entity children"] = $child;
      $data["disjointness constraints"] = $disj;
      $data["completeness constraints"] = $compl;
	    array_push($this->content["Relationship"]["Subsumption"], $data);
    }

}



?>
