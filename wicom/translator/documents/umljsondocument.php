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

require_once __DIR__ . '/jsondocument.php';

/**
Json structure for UML digrams to be imported in crowd

@author GILIA
@license GPLv3
*/
class UMLJSONDocument extends JSONDocument{


    /**
    The content in PHP array and hashes. 

    This will be translated directly into JSON.
    */
    protected $content = NULL;
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
    function __construct(){
        $this->content = [
            "namespaces" => [
                "ontologyIRI" => $this->default_ontologyIRI,
                "defaultIRIs" => [],
                "IRIs" => []
            ],
            "classes" => [],
            "links" => [],
            "owllink" => [],
        ];
    }

    function to_json(){
        return json_encode($this->content);
    }

    /**
    Change all the prefixes (default IRIs). 

    @param $prefixes {array} An array of association between prefixes names
    and their IRIs. Format:
    `[["name" => "PrefixName", "IRI" => "An IRI Here"], ...]`
    */
    function set_prefixes($prefixes){
        foreach ($prefixes as $p){
            array_push($this->content["namespaces"]["defaultIRIs"], $p);
        }
    }

    /**
      Change the ontology IRI.

      @param $ontologyIRI {string}
    */
    function set_ontologyIRI($ontologyIRI){
        array_push($this->content["namespaces"]["ontologyIRI"], $ontologyIRI);
    }

    /**
      Retrieve the ontology IRI.
       
      @return A string.
    */
    function get_ontologyIRI(){
        return $this->content["namespaces"]["ontologyIRI"];
    }

    /**
      Insert a class with its attributes into the document.

      @param $classname {string} The class name.
      @param $attrs {array} An array of names with their own datatype.
      Format:
      `[["name" => "AttrName", "datatype" => "AttrType"], ...]`
    */
    public function insert_class_with_attr($classname, $attrs){
        $class_array = ["name" => $classname, "attrs" => $attrs,
                        "methods" => []];
        array_push($this->content["classes"], $class_array);
    }
    
    /**
      Insert a class without attributes into the document.

      @param $classname {string}
      @param $attrs {array} [[$attname, $datatype],...,[$attnameN, $datatypeN]]
    **/
    function insert_class_without_attr($classname){
        $class_array = ["name" => $classname, "attrs" => [], "methods" => []];
        array_push($this->content["classes"],$class_array);
    }


    function insert_attribute($attr, $class, $datatype){
        $attr_array = ["name" => null, "datatype" => null];

        $i = 0;
        $classes = count($this->content["classes"]);

        while ($i < $classes){

            if (strcmp($this->content["classes"][$i]["name"], $class) == 0){
                array_push($this->content["classes"][$i]["attrs"],
                           ["name" => $attr, "datatype" => $datatype]);
            }
            $i++;
        }
    }

    /**
      Insert a subsumption/generalization/Is-A relationship into the document.

      This method will not add the classes. It just add the generalization. 

      @param $classes {array} ["name1", "name2",...,"nameN"]
      @param $parent {string} "parent1"
      @param $constraints ["disjont","covering"]
    **/
    function insert_subsumption($classes, $parent, $constraints = []){
        $this->subsumption_number += 1;
        $array_sub = [];
        $ontologyIRI = $this->get_ontologyIRI();
        $array_sub = ["name" => $ontologyIRI . "s" . $this->subsumption_number,
                      "classes" => $classes,
                      "multiplicity" => NULL,
                      "roles" => [NULL,NULL],
                      "type" => "generalization",
                      "parent" => $parent,
                      "constraint" => $constraints];
        array_push($this->content["links"],$array_sub);
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
