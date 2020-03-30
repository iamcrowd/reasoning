<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   berardistrat.php

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

namespace Wicom\Translator\Strategies;

use function \load;
//load('berardipack.php', 'qapackages/');
load('enzopack.php', 'qapackages/');
load('strategy.php');
load('orm.php');

use Wicom\Translator\Strategies\QAPackages\EnzoPack;

/**
   I implement the method explained on "Reasoning on UML Class Diagrams" by
   Daniela Berardi, Diego Calvanesse and Giuseppe De Giacomo.

   @see Translator class for description about the JSON format.
 */
class CrowdORM extends ORM{


    function __construct(){
        parent::__construct();

        $this->qapack = new EnzoPack();
    }

    /**
       Depending on $mult translate it into DL.

       @param $from True if we have to represent the "from" side (left one).

       @return A DL list part that represent the multiplicity restriction.
     */
    protected function translate_multiplicity($mult, $role, $from = true){
        if ($from){
            $arr_role = ["role" => $role];
            $sub1_DL = [1,
                        $arr_role];
            $sub0_DL = [0,
                        $arr_role];
        }else{
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
       Translate only the association link.

       @param link A JSON object representing one association link.
    */
    protected function translate_association($link, $builder){
        $classes = $link["classes"];
        $mult = $link["multiplicity"];

        $builder->translate_DL([
            ["subclass" => [
                ["class" => "owl:Thing"],
                ["intersection" => [
                    ["forall" => [
                        ["role" => $link["name"]],
                        ["class" => $classes[0]]]],
                    ["forall" => [
                        ["inverse" =>
                         ["role" => $link["name"]]],
                        ["class" => $classes[1]]]]
                ]] //intersection
            ]] //subclass
        ]);

        $rest = $this->translate_multiplicity($mult[1], $link["name"]);
        if (($rest != null) and (count($rest) > 0)){
            // Multiplicity should be written.
            $lst = [
                ["subclass" => [
                    ["class" => $classes[0]],
                    $rest
                ]]
            ];
            $builder->translate_DL($lst);
        }

        $rest = $this->translate_multiplicity($mult[0], $link["name"], false);
        if (($rest != null) and (count($rest) > 0)){
            // Multiplicity should be written.
            $lst = [
                ["subclass" => [
                    ["class" => $classes[1]],
                    $rest
                ]]
            ];
            $builder->translate_DL($lst);
        }
    }

    /**
       Translate a generalization link into DL using the Builder.

       @param link A generalization link in a JSON string.
     */
	 
	protected function translate_generalization_union($link, $builder){
			$parent = $link["parent"];
			$lst_entities = [];
			$lst_union =  [];
			
			foreach ($link["entities"] as $class){
				array_push($lst_union, ["class" => $class]);
			}
						
			$lst = null;
			$lst = [
                ["subclass" => [
					["union" => $lst_union],
                    ["class" => $parent]]]
            ];
            $builder->translate_DL($lst);
			
			$lst = [
                ["subclass" => [
					["class" => $parent],
                    ["union" => $lst_union]]]
            ];
            $builder->translate_DL($lst);
			
	}
	
	protected function translate_generalization_exclusive($link, $builder){
			$parent = $link["parent"];
			$lst_entities = [];
			$lst_union =  [];
			
			foreach ($link["entities"] as $class){
				array_push($lst_union, ["class" => $class]);
			}
						
			$lst = null;
			$lst = [
                ["subclass" => [
					["union" => $lst_union],
                    ["class" => $parent]]]
            ];
            $builder->translate_DL($lst);
			
			$lst = null;
			$count=1;
			$count_entities=sizeof($link["entities"]);
			foreach ($link["entities"] as $class){
				for($i = $count; $i <= $count_entities-1; ++$i) {
					$lst = [
						["disjointclasses" => [
							["class" => $class],
							["class" => $link["entities"][$i]]]]
					];
					$builder->translate_DL($lst);
				}
				$count++;
			}
	}
	
	protected function translate_generalization_exlusiveExhaustive($link, $builder){
			$parent = $link["parent"];
			$lst_entities = [];
			$lst_union =  [];
			
			foreach ($link["entities"] as $class){
				array_push($lst_union, ["class" => $class]);
			}
						
			$lst = null;
			$lst = [
                ["subclass" => [
					["union" => $lst_union],
                    ["class" => $parent]]]
            ];
            $builder->translate_DL($lst);
			
			$lst = [
                ["subclass" => [
					["class" => $parent],
                    ["union" => $lst_union]]]
            ];
            $builder->translate_DL($lst);
			
			$lst = null;
			$count=1;
			$count_entities=sizeof($link["entities"]);
			foreach ($link["entities"] as $class){
				for($i = $count; $i <= $count_entities-1; ++$i) {
					$lst = [
						["disjointclasses" => [
							["class" => $class],
							["class" => $link["entities"][$i]]]]
					];
					$builder->translate_DL($lst);
				}
				$count++;
			}
			
	}
	 
    protected function translate_generalization($link, $builder){     
		$parent=$link["parent"];
        foreach ($link["entities"] as $class){
            // Translate the parent-child relation
            $lst = [
                ["subclass" => [
                    ["class" => $class],
                    ["class" => $parent]]]
            ];
            $builder->translate_DL($lst);			
        }
		//VERIFY "subtypingContraint"
		//echo "WOW!".strtoupper($link["subtypingContraint"]);
		switch (strtoupper($link["subtypingContraint"])) {
			
			case "UNION":
				$this->translate_generalization_union($link,$builder);
				break;
			case "EXCLUSIVE":
				$this->translate_generalization_exclusive($link,$builder);
				break;
			case "EXCLUSIVEEXHAUSTIVE":				  
				$this->translate_generalization_exlusiveExhaustive($link,$builder);
				break;			
		}
    }

    protected function translate_attributes($json, $builder){
      
    }

    /**
       Translate only the links from a JSON string with links using
       the given builder.
       @param json A JSON object, the result from a decoded JSON
       String.
       @return false if no "links" part has been provided.
     */
    protected function translate_links($json, $builder){
		
        if (! array_key_exists("connectors", $json)){
            return false;
        }
        $js_links = $json["connectors"];
        foreach ($js_links as $link){
            switch ($link["type"]){
            case "association":
                //$this->translate_association($link, $builder);
                break;
            case "subtyping":
				//echo "!!!!CALLING->translate_generalization!!!!";
                $this->translate_generalization($link, $builder);
                break;
            }
        }

    }
}
