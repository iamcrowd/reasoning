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

{"classes":[
	{"name":"Person","attrs":[{"name":"dni","datatype":"String"},
														{"name":"firstname","datatype":"String"},
														{"name":"surname","datatype":"String"},
														{"name":"birthdate","datatype":"Date"}],
									 "methods":[],
									 "position":{"x":20,"y":20}},
	{"name":"Student","attrs":[{"name":"id","datatype":"String"},
														 {"name":"enrolldate","datatype":"Date"}],
										"methods":[],
										"position":{"x":491,"y":107}},
  {"name":"Class1","attrs":[],
									 "methods":[],
									 "position":{"x":1025,"y":104}}],
"links":[
	{"name":"r1","classes":["Student"],
							 "multiplicity":null,
							 "roles":[null,null],
							 "type":"generalization",
							 "parent":"Person",
							 "constraint":[]},
	{"name":"R1","classes":["Student","Class1"],
							 "multiplicity":["2..4","1..*"],
							 "roles":["e","c"],
							 "type":"association"}]
}

array(2) {
  ["classes"]=>
  array(3) {
    [0]=> array(4) {["name"]=>string(6) "Person"
      ["attrs"]=>
      array(4) {
        [0]=>
        array(2) {
          ["name"]=>
          string(3) "dni"
          ["datatype"]=>
          string(6) "String"
        }
        [1]=>
        array(2) {
          ["name"]=>
          string(9) "firstname"
          ["datatype"]=>
          string(6) "String"
        }
        [2]=>
        array(2) {
          ["name"]=>
          string(7) "surname"
          ["datatype"]=>
          string(6) "String"
        }
        [3]=>
        array(2) {
          ["name"]=>
          string(9) "birthdate"
          ["datatype"]=>
          string(4) "Date"
        }
      }
      ["methods"]=>
      array(0) {
      }
      ["position"]=>
      array(2) {
        ["x"]=>
        int(20)
        ["y"]=>
        int(20)
      }
    }
    [1]=>
    array(4) {
      ["name"]=>
      string(7) "Student"
      ["attrs"]=>
      array(2) {
        [0]=>
        array(2) {
          ["name"]=>
          string(2) "id"
          ["datatype"]=>
          string(6) "String"
        }
        [1]=>
        array(2) {
          ["name"]=>
          string(10) "enrolldate"
          ["datatype"]=>
          string(4) "Date"
        }
      }
      ["methods"]=>
      array(0) {
      }
      ["position"]=>
      array(2) {
        ["x"]=>
        int(491)
        ["y"]=>
        int(107)
      }
    }
    [2]=>
    array(4) {
      ["name"]=>
      string(6) "Class1"
      ["attrs"]=>
      array(0) {
      }
      ["methods"]=>
      array(0) {
      }
      ["position"]=>
      array(2) {
        ["x"]=>
        int(1025)
        ["y"]=>
        int(104)
      }
    }
  }
  ["links"]=>
  array(2) {
    [0]=>
    array(7) {
      ["name"]=>
      string(2) "r1"
      ["classes"]=>
      array(1) {
        [0]=>
        string(7) "Student"
      }
      ["multiplicity"]=>
      NULL
      ["roles"]=>
      array(2) {
        [0]=>
        NULL
        [1]=>
        NULL
      }
      ["type"]=>
      string(14) "generalization"
      ["parent"]=>
      string(6) "Person"
      ["constraint"]=>
      array(0) {
      }
    }
    [1]=>
    array(5) {
      ["name"]=>
      string(2) "R1"
      ["classes"]=>
      array(2) {
        [0]=>
        string(7) "Student"
        [1]=>
        string(6) "Class1"
      }
      ["multiplicity"]=>
      array(2) {
        [0]=>
        string(4) "2..4"
        [1]=>
        string(4) "1..*"
      }
      ["roles"]=>
      array(2) {
        [0]=>
        string(1) "e"
        [1]=>
        string(1) "c"
      }
      ["type"]=>
      string(11) "association"
    }
  }
}

*/

class UMLJSONDocument extends JSONDocument{

	protected $classes = [];
	protected $links = [];
	protected $content = NULL;

	function __construct(){
		$ontologyIRI = [];
		$defaultIRIs = [];
		$IRIs = [];
		$classes = [];
		$links = [];
		$this->content = ["namespaces" => [
													"ontologyIRI" => $ontologyIRI,
													"defaultIRIs" => $defaultIRIs,
													"IRIs" => $IRIs
												],
											"classes" => $classes,
											"links" => $links,
											"owllink" => [],
										];
	}

	function to_json(){
		return json_encode($this->content);
	}

	function set_prefixes($prefixes){
		foreach ($prefixes as $p){
			array_push($this->content["namespaces"]["defaultIRIs"], $p);
		}
	}

	function set_ontologyIRI($ontologyIRI){
		array_push($this->content["namespaces"]["ontologyIRI"], $ontologyIRI);
	}

	function get_ontologyIRI(){
		return $this->content["namespaces"]["ontologyIRI"][0]["value"];
	}

	/**
	@param $classname {string}
	@param $attrs {array} [[$attname, $datatype],...,[$attnameN, $datatypeN]]

	["classes"]=>
  array(1) {
    [0]=> array(3) {["name"]=>string(6) "Person"
      ["attrs"]=>
      array(4) {
        [0]=>array(2) {["name"]=> string(3) "dni" ["datatype"]=>string(6) "String"}
        [1]=>array(2) {["name"]=>string(9) "firstname"["datatype"]=>string(6) "String"}
        [2]=>array(2) {["name"]=>string(7) "surname"["datatype"]=>string(6) "String"}
        [3]=>array(2) {["name"]=>string(9) "birthdate"["datatype"]=>string(4) "Date"}
      }
      ["methods"]=>array(0) {}
    }
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
				array_push($this->content["classes"][$i]["attrs"], ["name" => $attr, "datatype" => $datatype]);
			}
			$i++;
		}
	}

	/**
	["classes"]=>
  array(2) {
    [0]=> array(3) {["name"]=>string(6) "Person"
      ["attrs"]=>array(0) {}
      ["methods"]=>array(0) {}
    }
		[1]=> array(3) {["name"]=>string(6) "Student"
      ["attrs"]=>array(0) {}
      ["methods"]=>array(0) {}
    }
	}
		["links"]=>
	  array(1) {
	    [0]=>array(7) {["name"]=> string(2) "r1"
										 ["classes"]=>array(1) {[0]=>string(7) "Student"}
										 ["multiplicity"]=>NULL
										 ["roles"]=>array(2) {[0]=>NULL[1]=>NULL}
	      			       ["type"]=>string(14) "generalization"
	      						 ["parent"]=>string(6) "Person"
										 ["constraint"]=>array(0) {}
	    }
	}

	@param $classes {array} ["name1", "name2",...,"nameN"]
	@param $parent {string} "parent1"
	@param $constraints ["disjont","covering"]
	**/
	function insert_subsumption($classes, $parent, $constraints = []){
		$array_sub = [];
		$ontologyIRI = $this->get_ontologyIRI();
		$array_sub = ["name" => $ontologyIRI."#s1",
									"classes" => $classes,
									"multiplicity" => NULL,
									"roles" => [NULL,NULL],
									"type" => "generalization",
									"parent" => $parent,
									"constraint" => $constraints];
		array_push($this->content["links"],$array_sub);
	}

	/**
	["classes"]=>
	array(2) {
		[0]=> array(3) {["name"]=>string(6) "Person"
			["attrs"]=>array(0) {}
			["methods"]=>array(0) {}
		}
		[1]=> array(3) {["name"]=>string(6) "Student"
			["attrs"]=>array(0) {}
			["methods"]=>array(0) {}
		}
	}
		["links"]=>
		array(1) {
				[0]=>array(5) {["name"]=>string(2) "R1"
				               ["classes"]=>array(2) {[0]=>string(7) "Student"
											                        [1]=>string(6) "Class1"
																						 }
											 ["multiplicity"]=>array(2) {
																							[0]=>string(4) "2..4"
																							[1]=>string(4) "1..*"
																						}
									     ["roles"]=>array(2) {
																							[0]=>string(1) "e"
																							[1]=>string(1) "c"
																					}
											 ["type"]=>string(11) "association"
										}
	}

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

			if (($card[0][0] !== $n_l_cards[0][0]) || ($card[0][3] !== $n_l_cards[0][3])){
				$new_l = $n_l_cards[0];
			}
			if (($card[1][0] !== $n_r_cards[0][0]) || ($card[1][3] !== $n_r_cards[0][3])){
				$new_r = $n_r_cards[0];
			}
		} elseif ((count($n_r_cards) == 0) && (count($n_l_cards) != 0)){
				if (($card[0][0] !== $n_l_cards[0][0]) || ($card[0][3] !== $n_l_cards[0][3])){
					$new_l = $n_l_cards[0];
				}

			} elseif ((count($n_r_cards) != 0) && (count($n_l_cards) == 0)){

					if (($card[1][0] !== $n_r_cards[0][0]) || ($card[1][3] !== $n_r_cards[0][3])){
						$new_r = $n_r_cards[0];
					}
			}


		if ((count($new_l) !== 0) && (count($new_r) !== 0)){
			$bassoc_o["multiplicity"] = "";
			$bassoc_o["multiplicity"] = [$new_l, $new_r]; //array_merge($new_l, $new_r);
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
	function insert_withclass_relationship($classes, $name, $assoc_class, $cardinalities, $roles){
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
	Compare subsumptions by parent and childs according to the standard JSON generated in crowd

	{"name":"http://crowd.fi.uncoma.edu.ar#s1","parent":"http://crowd.fi.uncoma.edu.ar#Class1",
						"classes":["http://crowd.fi.uncoma.edu.ar#Class2"],
						"multiplicity":null,
						"roles":null,
						"type":"generalization",
						"constraint":[]}

	$array_sub = ["name" => $ontologyIRI."#s1",
								"classes" => $classes,
								"multiplicity" => NULL,
								"roles" => [NULL,NULL],
								"type" => "generalization",
								"parent" => $parent,
								"constraint" => $constraints];
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
	Compare associations by domains and ranges and name from JSON generated in crowd
	if assoc is binary =>

	{"name":"http://crowd.fi.uncoma.edu.ar#r2",
	 "classes":["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
	 "multiplicity":["0..*","0..*"],"roles":["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
	 "type":"association"}

	$array_assoc = ["name" => http://crowd.fi.uncoma.edu.ar#r2,
								"classes" => ["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
								"multiplicity" => ["0..*","0..*"],
								"roles" => ["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
								"type" => "association"];

	if assoc has an associated class =>

	{"name":"http://crowd.fi.uncoma.edu.ar#r1",
	 "classes":["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
	 "multiplicity":["0..*","0..*"],"roles":["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
	 "associated_class":{"name":"http://crowd.fi.uncoma.edu.ar#r1",
	    								 "attrs":[],
											 "methods":[]},
	"type":"association with class"};

	$array_assoc = ["name" => http://crowd.fi.uncoma.edu.ar#r2,
								"classes" => ["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
								"multiplicity" => ["0..*","0..*"],
								"roles" => ["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
								"associated_class" => ["name" => $classname, "attrs" => [], "methods" => []],
								"type" => "association with class"];

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
			 (strcmp($assoc1["associated_class"]["name"], $assoc2["associated_class"]["name"]) == 0)){

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
