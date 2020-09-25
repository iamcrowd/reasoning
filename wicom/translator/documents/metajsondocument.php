<?php
/*

   Copyright 2018

   Author: GILIA

   document.php

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
   Json structure for UML digrams to be imported in crowd

   @author GILIA
   @license GPLv3
 */
class MetaJSONDocument extends JSONDocument{

    /**
       The content in PHP array and hashes.

       This will be translated directly into JSON.
    */
    protected $content = [];
    /**
       Number used to count the amount of subsumptions.

       This is used for giving a unique name to each subsumption created.
    */
    protected $subsumption_number = 0;

    /**
       If the ontology IRI is not provided, this will be used as the default.
    */
    protected $default_ontologyIRI = "http://crowd.fi.uncoma.edu.ar/kb1#";

    /**
       Constructor. It defines the base template for the JSON document.
    */
    function __construct(){}

    function instantiate_MM($json){
      $this->content = json_decode($json, true);
    }

    function to_json(){
	    return json_encode($this->content);
    }

    function get_product(){
      return $this->content;
    }


    /**
       Check if a subsumtion between both parent and child given as parameters exists in the current KF instance

       @// NOTE: see kfmetaScheme.json
     **/
    function subsumption_in_instance($child, $parent){
      foreach ($this->content["Relationship"]["Subsumption"] as $sub) {
        if ((\strcmp($sub["entity child"], $child) == 0 ) && (\strcmp($sub["entity parent"],$parent) == 0 )){
          return true;
        }
      }
      return false;
    }

    function mincard_role_in_instance($otc, $min){
      $c_min = null;
      foreach ($this->content["Constraints"]["Cardinality constraints"]["Object type cardinality"] as $otc_e) {
        if (
            (\strcmp($otc_e["name"], $otc) == 0)
           ){
              $c_min = $otc_e["minimum"];
              return $c_min;
        }
      }
      return $c_min;
    }

    function maxcard_role_in_instance($otc, $max){
      $c_max = null;
      foreach ($this->content["Constraints"]["Cardinality constraints"]["Object type cardinality"] as $otc_e) {
        if (
            (\strcmp($otc_e["name"], $otc) == 0)
           ){
              $c_max = $otc_e["maximum"];
              return $c_max;
        }
      }
      return $c_max;
    }

    /**
      Modify the current max cardinality of a role
    */
    function add_newMaxcardinality($otc, $maxcard){
      for ($i = 0; $i < count($this->content["Constraints"]["Cardinality constraints"]["Object type cardinality"]); $i++) {
        if (
            (\strcmp($this->content["Constraints"]["Cardinality constraints"]["Object type cardinality"][$i]["name"], $otc) == 0)
           ){
              $this->content["Constraints"]["Cardinality constraints"]["Object type cardinality"][$i]["maximum"] = $maxcard;
        }
      }
    }

    /**
       Check if a role relating a class with a relationship and the respective cardinalities given as parameters exists in the current KF product.

       "Role": [
           {
               "object type cardinality": [
                   "card1"
               ],
               "rolename": "http://crowd.fi.uncoma.edu.ar/kb1#person",
               "entity type": "http://crowd.fi.uncoma.edu.ar/kb1#Person",
               "relationship": "http://crowd.fi.uncoma.edu.ar/kb1#enrolled"
           }
        ]

       @return an object type cardinality id if $role exists. Otherwise, null.

       @// NOTE: see kfmetaScheme.json
     **/
    function role_in_instance($role, $class, $rel){
      $an_otc = null;
      foreach ($this->content["Role"] as $role_e) {
        if (
            (\strcmp($role_e["rolename"], $role) == 0) &&
            (\strcmp($role_e["entity type"], $class) == 0) &&
            (\strcmp($role_e["relationship"], $rel) == 0)
           ){
             $an_otc = $role_e["object type cardinality"][0];
             return $an_otc;
        }
      }
      return null;
    }

    /**
       Insert a subsumption and update the current KF instance. Return id for the new subsumption.

       This method will not add the classes. It just add the generalization.

       @// NOTE: see kfmetaScheme.json
     **/
    function insert_subsumption($child, $parent, $constraints = []){
	     $this->subsumption_number += 1;
       $name = "http://crowd.fi.uncoma.edu.ar/inferredSubsumption#" . "s" . $this->subsumption_number;
	     $array_sub = [];
	     $array_sub = [
         "entity child" => $child,
         "name" => $name,
         "entity parent" => $parent
       ];
	     array_push($this->content["Relationship"]["Subsumption"], $array_sub);
       return $name;
    }

    /**
       Insert a relationship between classes into the document.

       This method will not add the classes. It just add the association.

       @param $name {string} "R1"
       @param $classes {array} ["Student", "Class1"]
       @param $multiplicity {array} ["2..4","1..*"]
       @param $roles ["e","c"]
     **/
    function insert_relationship($classes, $name, $cardinalities, $roles){
	$array_rel = [];
	$array_rel = ["name" => $name,
		      "classes" => $classes,
		      "multiplicity" => $cardinalities,
		      "roles" => $roles,
		      "type" => "association"];
	array_push($this->content["links"],$array_rel);
    }

    function get_classfrom_bassoc($b_assoc){
	return $b_assoc["classes"][0];
    }

    function get_classto_bassoc($b_assoc){
	return $b_assoc["classes"][1];
    }


    protected function normalise_cards($card){
	$n_card = [];

	if (count($card) != 0){

	    if (($card[0] == null) && ($card[1] == null)){
		$n_card = ["0..*"];

	    } elseif (($card[0] != null) && ($card[1] == null)){
		array_push($n_card, implode([$card[0], "..*"]));

	    } elseif (($card[0] == null) && ($card[1] != null)) {
		array_push($n_card, implode(["0..", $card[1]]));

	    } elseif (($card[0] != null) && ($card[1] != null)) {
		array_push($n_card, implode([$card[0], "..", $card[1]]));
	    }
	}

	return $n_card;

    }

    function edit_cardinalities($bassoc_o, $r_cards, $l_cards){
	$card = $bassoc_o["multiplicity"];
	$new_l = [];
	$new_r = [];
	$n_r_cards = [];
	$n_l_cards = [];

	$n_r_cards = $this->normalise_cards($r_cards);
	$n_l_cards = $this->normalise_cards($l_cards);

	if ((count($n_r_cards) != 0) && (count($n_l_cards) != 0)){
	    if (($card[0][0] !== $n_l_cards[0][0]) ||
		($card[0][3] !== $n_l_cards[0][3])){
		$new_l = $n_l_cards[0];
	    }
	    if (($card[1][0] !== $n_r_cards[0][0]) ||
		($card[1][3] !== $n_r_cards[0][3])){
		$new_r = $n_r_cards[0];
	    }
	} elseif ((count($n_r_cards) == 0) && (count($n_l_cards) != 0)){
	    if (($card[0][0] !== $n_l_cards[0][0]) ||
		($card[0][3] !== $n_l_cards[0][3])){
		$new_l = $n_l_cards[0];
	    }

	} elseif ((count($n_r_cards) != 0) && (count($n_l_cards) == 0)){
	    if (($card[1][0] !== $n_r_cards[0][0]) ||
		($card[1][3] !== $n_r_cards[0][3])){
		$new_r = $n_r_cards[0];
	    }
	}


	if ((count($new_l) !== 0) && (count($new_r) !== 0)){
	    $bassoc_o["multiplicity"] = "";
	    $bassoc_o["multiplicity"] = [$new_l, $new_r];
	    //array_merge($new_l, $new_r);
	    return $bassoc_o;
	}
	elseif ((count($new_l) !== 0) && (count($new_r) == 0)){
	    $bassoc_o["multiplicity"] = "";
	    $bassoc_o["multiplicity"] = [$new_l, $card[1]];
	    return $bassoc_o;
	}
	elseif ((count($new_l) == 0) && (count($new_r) !== 0)){
	    $bassoc_o["multiplicity"] = "";
	    $bassoc_o["multiplicity"] = [$card[0], $new_r];
	    return $bassoc_o;
	}
	else return [];

    }

    function get_bassoc_name($b){
	return $b["name"];
    }

    /**
       @param $name {string} "R1"
       @param $classes {array} ["Student", "Class1"]
       @param $multiplicity {array} ["2..4","1..*"]
       @param $roles ["e","c"]
       @param $assoc_class ["name" => "R1", "attrs" => [], "methods" => []]
     **/
    function insert_withclass_relationship($classes, $name, $assoc_class,
				    $cardinalities, $roles){
	$array_rel = [];
	$array_rel = ["name" => $name,
		      "classes" => $classes,
		      "multiplicity" => $cardinalities,
		      "roles" => $roles,
		      "associated_class" => $assoc_class,
		      "type" => "association with class"];
	array_push($this->content["links"],$array_rel);
    }


    /**
       Compare subsumptions by parent and childs according to the standard JSON
       generated in crowd
     */
    function same_subsumption($sub1, $sub2){
	if (strcmp($sub1["parent"], $sub2["parent"]) == 0){
	    if (array_diff($sub1["classes"], $sub2["classes"]) == []){
		return true;
	    }
	    else{
		return false;
	    }
	}
	else{
	    return false;
	}
    }

    /**
       @return [["name" => $ontologyIRI."#s1",
       "classes" => $classes,
       "multiplicity" => NULL,
       "roles" => [NULL,NULL],
       "type" => "generalization",
       "parent" => $parent,
       "constraint" => $constraints]...]
     */
    function get_subs_links($json){
	$json_array = json_decode($json, true);
	$link_list = $json_array["links"];
	$subs = [];

	foreach ($link_list as $link){
	    if ($link["type"] == "generalization"){
		array_push($subs, $link);
	    }
	}
	return $subs;
    }

    /**
       Compare associations by domains and ranges and name from JSON generated
       in crowd.
     */
    function same_b_assoc($assoc1, $assoc2){
	if ((strcmp($assoc1["name"], $assoc2["name"]) == 0) &&
	    (array_diff($sub1["classes"], $sub2["classes"]) == [])){

	    return true;
	}
	else {
	    return false;
	}
    }


    function same_wcb_assoc($assoc1, $assoc2){
	if ((strcmp($assoc1["name"], $assoc2["name"]) == 0) &&
	    (array_diff($sub1["classes"], $sub2["classes"]) == []) &&
	    (strcmp($assoc1["associated_class"]["name"],
		    $assoc2["associated_class"]["name"]) == 0)){

	    return true;
	}
	else {
	    return false;
	}
    }

    /**
       @return [["name" => http://crowd.fi.uncoma.edu.ar#r2,
       "classes" => ["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
       "multiplicity" => ["0..*","0..*"],
       "roles" => ["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
       "type" => "association"]...]
     */
    function get_bassoc_links($json){
	$json_array = json_decode($json, true);
	$link_list = $json_array["links"];
	$assocs = [];

	foreach ($link_list as $link){
	    if ($link["type"] == "association"){
		array_push($assocs, $link);
	    }
	}
	return $assocs;
    }

    /**
       @return [$array_assoc = ["name" => http://crowd.fi.uncoma.edu.ar#r2,
       "classes" => ["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
       "multiplicity" => ["0..*","0..*"],
       "roles" => ["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
       "associated_class" => ["name" => $classname, "attrs" => [], "methods" => []],
       "type" => "association with class"]...]
     */
    function get_wClassassoc_links($json){
	$json_array = json_decode($json, true);
	$link_list = $json_array["links"];
	$w_assocs = [];

	foreach ($link_list as $link){
	    if ($link["type"] == "association with class"){
		array_push($w_assocs, $link);
	    }
	}
	return $w_assocs;
    }
}



?>
