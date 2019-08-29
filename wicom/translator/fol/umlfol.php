<?php 
/* 

   Copyright 2016 GILIA, Departamento de Teoría de la Computación, Universidad Nacional del Comahue
   
   Author: GILIA

   crowd_uml.php
   
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

namespace Wicom\Translator\Fol;

use function \load;

load("umlPredicates.php");

use Wicom\Translator\Fol\ClassPredicate;
use Wicom\Translator\Fol\IsAPredicate;
use Wicom\Translator\Fol\Attribute;
use Wicom\Translator\Fol\Association;
use Wicom\Translator\Fol\IsAConstraints;
/**
   This module implements the graphical-oriented UML encoding for crowd.
   Read paper "" for more details about formalisation.

   @see Translator class for description about the JSON format.
 */
class UMLFol{  //llamar a éste archivo UMLFOL y los método de UMLFOL VIEJO METERLOS ACÁ... HACER COMO UMLMETA.PHP

	/**
	 Translate a given JSON String representing a UML class diagram into a new JSON metamodel string.
	
	 @param json JSON UML string
	 @return a JSON metamodel string.
	
	 @see MetaStrategy class for description about the JSON format.
	 */
	
	
	public $fol;
	
	function __construct(){
		$this->fol = ["Classes" => [],"Attribute" => [],"Links"=> [] ,"IsA" => []];
	}
	    
    function create_fol($json_str){
        $json = json_decode($json_str, true);                                                        #Ejemplo: $json = '{"a":1,"b":2}';   ------->   (TRADUCE A ARREGLOS ASOCIATIVOS)
                                                                                                           # array(5) {
                                                                                                      #      ["a"] => int(1)
                                                                                                      #      ["b"] => int(2) }
 //       print_r($json);
        $this->translateClassesToFol($json);
        $this->translateIsA($json);
        $this->translateLinks($json);
    }
    
    
    function translateClassesToFol($json){
        $js_classes = $json["classes"];
    	foreach ($js_classes as $class){
    		$class_predicate = new ClassPredicate($class["name"]);
    		array_push($this->fol["Classes"],$class_predicate->get_json_array());
                    
        $js_attr = $class["attrs"];            
        if (!empty($js_attr)){
    			foreach ($js_attr as $attr){
    				$attr_obj = new Attribute($class["name"],$attr["name"],$attr["datatype"]);
    				array_push($this->fol["Attribute"],$attr_obj->get_json_array());
    			}
    		}
    	}
        //print_r($this->fol); //Esto cambié
    }
    
     function translateIsA($json) {
        $js_links = $json["links"];
        $gen_classes = array_filter($js_links, function($gen) {
            return $gen["type"] == "generalization";
        });
        foreach ($gen_classes as $gen_class) {
            $children = $gen_class["classes"];
            foreach ($children as $child) {
                $isA_predicate = new IsAPredicate($gen_class["parent"], $child);
                array_push($this->fol["IsA"], $isA_predicate->get_json_array());
            }
            $parent= $gen_class["parent"];
            
            $constraints = $gen_class["constraint"];
            
            if (!empty($constraints)) {
                $isA_Constraints=new IsAConstraints($constraints,$parent,$children); //implementar ésto (hijos,restricciones)  
                array_push($this->fol["IsA"], $isA_Constraints->get_json_array());  //agrego las restricciones
            }
        }
    }

    function get_json(){
    	return json_encode($this->fol,true);
    	
    }

    
    function translateLinks($json) {
        /*
         *  "name": "estudia",
            "classes": ["Persona", "Carrera"],
            "multiplicity": ["1..*", "1..*"],
            "roles": [null, null],
            "type": "association"
         * 
         */
        $js_links = $json["links"];
        foreach ($js_links as $link) {
            switch ($link["type"]) {
                case "association":
                    $classes=$link["classes"];
                    $multiplicities=$link["multiplicity"];
                    $name_association=$link["name"];
                    $association = new Association($classes,$multiplicities,$name_association,null);
                    array_push($this->fol["Links"],$association->get_json_array());
                    break;
                //case "generalization":
                  //  $this->translate_generalization($link, $builder);
                 //   break;
            }
        }
    }

    /**
       Depending on $mult translate it into DL.

       @param $from True if we have to represent the right cardinality.

       @return A DL list part that represent the multiplicity restriction.
     */
    
    
    protected function translate_multiplicity($mult, $role, $classes, $from = true){
 
        if ($from) {
            $arr_role = ["role" => $role];
            $sub1_DL = [1,
                        $arr_role];
            $sub0_DL = [0,
                        $arr_role];
        } 

		else {
            $arr_role = ["inverse" => ["role" => $role]];
            $sub1_DL = [1,
                        $arr_role];
            $sub0_DL = [0,
                        $arr_role];
                        
        }
        
        $ret = null;
        switch($mult){
        case "1..1":
            $ret = ["intersection" => [
                ["mincard" => $sub1_DL],
                ["maxcard" => $sub1_DL]]];
            break;
        case "0..1":
            $ret = ["maxcard" => $sub1_DL];
            break;            
        case "1..*":
        case "1..n":
            $ret = ["mincard" => $sub1_DL];
            break;
        case "0..*":            
        case "0..n":
            $ret = [];
            break;
        }
        return $ret;
    }

    /**
       Translate associations without class together with cardinalities 0..*, 1..*, 0..1, 1..1 and M..N > 1 for both directions.
       
       @param link A JSON object representing one association link without class.
    */

    protected function translate_association_without_class($link, $builder){

        $classes = $link["classes"];
        $mult = $link["multiplicity"];


		$assoc_without_class = [
			["domain" => [["role" => $link["name"]], ["class" => $classes[0]]]],
			["range" => [["role" => $link["name"]], ["class" => $classes[1]]]],
			["equivalentclasses" => [["class_min" => [$classes[0], $link["name"]]],
						            ["intersection" => [["class" => $classes[0]],
                				                        ["mincard" => [1, ["role" => $link["name"]], ["top" => "owl:Thing"]]]]
									]]
			],
			["equivalentclasses" => [["class_max" => [$classes[0], $link["name"]]],
						            ["intersection" => [["class" => $classes[0]],
                				                        ["maxcard" => [1, ["role" => $link["name"]], ["top" => "owl:Thing"]]]]
									]]
			],
			["equivalentclasses" => [["class_min" => [$classes[1], $link["name"]]],
						            ["intersection" => [["class" => $classes[1]],
                				                        ["mincard" => [1, ["inverse" => ["role" => $link["name"]]], ["top" => "owl:Thing"]]]]
									]]
			],
			["equivalentclasses" => [["class_max" => [$classes[1], $link["name"]]],
						            ["intersection" => [["class" => $classes[1]],
                				                        ["maxcard" => [1, ["inverse" => ["role" => $link["name"]]], ["top" => "owl:Thing"]]]]
									]]
			]

		];
            
		// [1..1,0..2] $right=0..2, $left=1..1
		$right = null;
		switch ($mult[1]){
				
			case null : $right = [];
						break;
			case "0..1" : $right = [
							["subclass" => [["class" => $classes[0]],
						            		["maxcard" => [1, ["role" => $link["name"]], ["class" => $classes[1]]]]]
									]];
				  		break;
			case "1..*" : $right = [
							["subclass" => [["class" => $classes[0]],
						            		["mincard" => [1, ["role" => $link["name"]], ["class" => $classes[1]]]]]
									]];
						break;
			case "1..1" : $right = [
							["subclass" => [["class" => $classes[0]],
						            		["mincard" => [1, ["role" => $link["name"]], ["class" => $classes[1]]]]]],
							["subclass" => [["class" => $classes[0]],
						            		["maxcard" => [1, ["role" => $link["name"]], ["class" => $classes[1]]]]]]
									];
						break;
			default: 

				if (($mult[1][0] >= 0) || ($mult[1][3] >= 0)){

					if ($mult[1][0] <= $mult[1][3]){

						$right = [
							["subclass" => [["class" => $classes[0]],
						            		["mincard" => [$mult[1][0], ["role" => $link["name"]], ["class" => $classes[1]]]]]],
							["subclass" => [["class" => $classes[0]],
						            		["maxcard" => [$mult[1][3], ["role" => $link["name"]], ["class" => $classes[1]]]]]]
									];
					}
					else throw new \Exception("Right multiplicity between: ".$classes[0]." and ".$classes[1]. " is wrongly defined");

				}
				else throw new \Exception("Undefined right multiplicity between: ".$classes[0]." and ".$classes[1]);

		}


		$left = null;
		switch ($mult[0]){

			case null : $left = [];
						break;
			case "0..1" : $left = [
							["subclass" => [["class" => $classes[1]],
						            		["maxcard" => [1, ["inverse" => ["role" => $link["name"]]], ["class" => $classes[0]]]]]
									]];
						break; 
			case "1..*" : $left = [
							["subclass" => [["class" => $classes[1]],
						            		["mincard" => [1, ["inverse" => ["role" => $link["name"]]], ["class" => $classes[0]]]]]
									]];
						break;
			case "1..1" : $left = [
							["subclass" => [["class" => $classes[1]],
						            		["mincard" => [1, ["inverse" => ["role" => $link["name"]]], ["class" => $classes[0]]]]]],	
							["subclass" => [["class" => $classes[1]],
						            		["maxcard" => [1, ["inverse" => ["role" => $link["name"]]], ["class" => $classes[0]]]]]]
								 ];

						break;

			default: 

				if (($mult[0][0] > 1) || ($mult[0][3] > 1)){

					if ($mult[0][0] <= $mult[0][3]){

						$left = [
							["subclass" => [["class" => $classes[1]],
						            		["mincard" => [$mult[0][0], ["inverse" => ["role" => $link["name"]]], ["class" => $classes[0]]]]]],
							["subclass" => [["class" => $classes[1]],
						            		["maxcard" => [$mult[0][3], ["inverse" => ["role" => $link["name"]]], ["class" => $classes[0]]]]]]
									];
					}
					else throw new \Exception("Left multiplicity between: ".$classes[1]." and ".$classes[0]. " is wrongly defined");

				}
				else throw new \Exception("Undefined left multiplicity between: ".$classes[1]." and ".$classes[0]);
		}


		foreach ($right as $rightelem) {
			
			array_push($assoc_without_class, $rightelem);
		}


		foreach ($left as $leftelem) {
			
			array_push($assoc_without_class, $leftelem);
		}


		$builder->translate_DL($assoc_without_class);


    }


    /**
       Translate only the association link.
       
       @param link A JSON object representing one association link with class.
    */
    protected function translate_association_with_class($link, $builder){
        $classes = $link["classes"];
        $mult = $link["multiplicity"];
            
        $builder->translate_DL([
            ["subclass" => [
                			["exists" => $link["name"]],
							["class" => $classes[0]]
			]]]);

		$builder->translate_DL([
            ["subclass" => [
                ["exists" => [ 
					["inverse" => $link["name"]]]],
				["class" => $classes[1]]
			]]]);


		$rest = $this->generate_internal_classes($link["name"], $classes,true);

    }

    /**
       Translate a generalization link into DL using the Builder.

       @param link A generaization link in a JSON string.
     */
    protected function translate_generalization($link, $builder){
        $parent = $link["parent"];

        foreach ($link["classes"] as $class){
            // Translate the parent-child relation
            $lst = [
                ["subclass" => [
                    ["class" => $class],
                    ["class" => $parent]]]
            ];
            $builder->translate_DL($lst);
        }

        // Again the same for each, so it will create an organized OWLlink:
        // First all classes are subclasses and then the disjoints and covering.
        foreach ($link["classes"] as $class){

            // Translate the disjoint constraint DL
            if (in_array("disjoint",$link["constraint"])){
                $index = array_search($class, $link["classes"]);
                $complements = array_slice($link["classes"], $index+1);
                
                // Make the complement of Class_index for each j=index..n
                $comp_dl = [];
                foreach ($complements as $compclass){
                    array_push($comp_dl,
                               ["complement" =>
                                ["class" => $compclass]]
                    );
                }

                
                // Create the disjoint DL with the complements.
                $lst = null;
                if (count($complements) > 1){
                    $lst = [
                        ["subclass" => [
                            ["class" => $class],
                            ["intersection" => 
                             $comp_dl]]]

                    ];
                    
                    $builder->translate_DL($lst);
                }else{ if (count($complements) == 1){                        
                        $lst = [["subclass" => [
                            ["class" => $class],
                            $comp_dl[0]
                        ]]];

                        $builder->translate_DL($lst);
                    }
                }
                
                
                
            } // end if-disjoint
        } // end foreach

        // Translate the covering constraint
        if (in_array("covering", $link["constraint"])){
            $union = [];
            foreach ($link["classes"] as $classunion){
                array_push($union, ["class" => $classunion]);
            }
            $lst = [["subclass" => [
                ["class" => $parent],
                ["union" => $union]
            ]]];
            $builder->translate_DL($lst);
        }

    }

    /**
       Translate only the links from a JSON string with links using
       the given builder.
       @param json A JSON object, the result from a decoded JSON 
       String.
       @return false if no "links" part has been provided.
     */
    protected function translate_links($json, $builder){
        if (! array_key_exists("links", $json)){
            return false;
        }
        $js_links = $json["links"];
        foreach ($js_links as $link){
            switch ($link["type"]){
            case "association":
                $this->translate_association_without_class($link, $builder);
                break;
            case "generalization":
                $this->translate_generalization($link, $builder);
                break;
            }
        }
        
    }
}

