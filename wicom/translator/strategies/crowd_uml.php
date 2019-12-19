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

namespace Wicom\Translator\Strategies;

use function \load;
load('crowdpack.php', './qapackages/');
load('strategy.php');
load('uml.php');
load('ontoextractor.php', './sparqldl/');
load('graphicalaxioms.php', './sparqldl/');
load("umljsonbuilder.php", "../../../wicom/translator/builders/");

use Wicom\Translator\Builders\UMLJSONBuilder;
use Wicom\Translator\Strategies\QAPackages\CrowdPack;
use Wicom\Translator\Strategies\SPARQLDL\OntoExtractor;
use Wicom\Translator\Strategies\SPARQLDL\GraphicalAxioms;

use SimpleXMLIterator;

/**
   This module implements the graphical-oriented UML encoding for crowd.
   Read paper "" for more details about formalisation.

   @see Translator class for description about the JSON format.
 */
class UMLcrowd extends UML{


    const MAX_CARDINALITY = 9;

    protected $with_min_max = true;

    function __construct(){
        parent::__construct();

        $this->qapack = new CrowdPack();
        $this->sparqldl = "";
        $this->with_min_max = true;
    }


    public function change_min_maxTo_false(){
      $this->with_min_max = false;
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
       @todo refactor this functions for reusing cardinalities parser



      Algorithm to generate min and max classes:

        from i = 1 to Max
          Class_R_min_i

        from i = Min to Max_Cardinality
          Class_R_max_i

    These min and max classes must be enumerated in order to avoid inconsistencies.
    */

    protected function translate_association_without_class($link, $builder){

        $classes = $link["classes"];
        $mult = $link["multiplicity"];

		    $assoc_without_class = [
			         ["subclass" => [["exists" => [["role" => $link["name"]], ["top" => "owl:Thing"]]],["class" => $classes[0]]]],
               ["subclass" => [["exists" => [["inverse" => ["role" => $link["name"]]], ["top" => "owl:Thing"]]],["class" => $classes[1]]]]
		           ];

    		// [1..1,0..2] $mult[1]=0..2=right, $mult[0]=1..1=left
        //$mult[1][0] = 0, $mult[1][1] = ., $mult[1][2] = ., $mult[1][3] = 2

    		$right = [];

        // 1..*/2..*/.../9..*
        if (($mult[1][0] > 0) && ($mult[1][3] == "*")) {
          $right = [
    							["subclass" => [["class" => $classes[0]],
    						            		["mincard" => [$mult[1][0], ["role" => $link["name"]]]]]
    							]];
        // 0..1/0..2/.../0..9
        } elseif (($mult[1][0] == 0) && ($mult[1][3] > 0)) {
            $right = [
              ["subclass" => [["class" => $classes[0]],
                        ["maxcard" => [$mult[1][3], ["role" => $link["name"]]]]]
                        ]];
        // 1..1/2..4/.../9..9
          } elseif (($mult[1][0] > 0) && ($mult[1][3] > 0)) {
            $right = [
                ["subclass" => [["class" => $classes[0]],
                              ["mincard" => [$mult[1][0], ["role" => $link["name"]]]]]
                ],
                ["subclass" => [["class" => $classes[0]],
                              ["maxcard" => [$mult[1][3], ["role" => $link["name"]]]]]
                ]];
          }


    		$left = [];

        // 1..*/2..*/.../9..*
        if (($mult[0][0] > 0) && ($mult[0][3] == "*")) {
          $left = [
    							["subclass" => [["class" => $classes[1]],
    						            		["mincard" => [$mult[0][0], ["inverse" => ["role" => $link["name"]]]]]]
    							]];
        // 0..1/0..2/.../0..9
        } elseif (($mult[0][0] == 0) && ($mult[0][3] > 0)) {
            $left = [
      							["subclass" => [["class" => $classes[1]],
      						            		["maxcard" => [$mult[0][3], ["inverse" => ["role" => $link["name"]]]]]]
      							]];
        // 1..1/2..4/.../9..9
          } elseif (($mult[0][0] > 0) && ($mult[0][3] > 0)) {
            $left = [
      							["subclass" => [["class" => $classes[1]],
      						            		["mincard" => [$mult[0][0], ["inverse" => ["role" => $link["name"]]]]]]
                    ],
      							["subclass" => [["class" => $classes[1]],
      						            		["maxcard" => [$mult[0][3], ["inverse" => ["role" => $link["name"]]]]]]
      							]];
          }

          if ($this->with_min_max){

                  $addit_min_class = [];
                  $local_max_card = $mult[1][3];

                  if ($local_max_card != "*"){
                    for ($i = 1; $i <= $local_max_card; $i++) {
                      $right_min_class = ["equivalentclasses" => [["class_min" => [$classes[0], $link["name"], $i]],
                                        ["intersection" => [["class" => $classes[0]],
                                                                ["mincard" => [$i, ["role" => $link["name"]]]]]
                                  ]]
                                ];
                      array_push($addit_min_class, $right_min_class);
                    }
                  }
                  elseif ($local_max_card == "*"){
                    for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                      $right_min_class = ["equivalentclasses" => [["class_min" => [$classes[0], $link["name"], $i]],
                                        ["intersection" => [["class" => $classes[0]],
                                                                ["mincard" => [$i, ["role" => $link["name"]]]]]
                                  ]]
                                ];
                      array_push($addit_min_class, $right_min_class);
                    }
                  }

                  foreach ($addit_min_class as $addit_min_class_elem) {
                    array_push($right, $addit_min_class_elem);
                  }

                  $addit_max_class = [];
                  $local_min_card = $mult[1][0];

                  if ($local_min_card >= 1){

                    for ($i = $local_min_card; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                      $right_max_class = ["equivalentclasses" => [["class_max" => [$classes[0], $link["name"], $i]],
                                      ["intersection" => [["class" => $classes[0]],
                                                              ["maxcard" => [$i, ["role" => $link["name"]]]]]
                                ]]
                              ];
                      array_push($addit_max_class, $right_max_class);
                    }
                  }
                  elseif ($local_min_card == 0) {
                    for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                      $right_max_class = ["equivalentclasses" => [["class_max" => [$classes[0], $link["name"], $i]],
                                      ["intersection" => [["class" => $classes[0]],
                                                              ["maxcard" => [$i, ["role" => $link["name"]]]]]
                                ]]
                              ];
                      array_push($addit_max_class, $right_max_class);
                    }
                  }

                  foreach ($addit_max_class as $addit_max_class_elem) {
                    array_push($right, $addit_max_class_elem);
                  }


                $addit_min_class = [];
                $local_max_card = $mult[0][3];

                if ($local_max_card != "*"){
                  for ($i = 1; $i <= $local_max_card; $i++) {
                    $left_min_class = ["equivalentclasses" => [["class_min" => [$classes[1], $link["name"], $i]],
            						            ["intersection" => [["class" => $classes[1]],
                            				                        ["mincard" => [$i, ["inverse" => ["role" => $link["name"]]]]]]
            									]]
            			             ];
                     array_push($addit_min_class, $left_min_class);
                   }
                }
                elseif ($local_max_card == "*"){
                  for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                    $left_min_class = ["equivalentclasses" => [["class_min" => [$classes[1], $link["name"], $i]],
            						            ["intersection" => [["class" => $classes[1]],
                            				                        ["mincard" => [$i, ["inverse" => ["role" => $link["name"]]]]]]
            									]]
            			             ];
                     array_push($addit_min_class, $left_min_class);
                   }
                }

                foreach ($addit_min_class as $addit_min_class_elem) {
                  array_push($left, $addit_min_class_elem);
                }

                $addit_max_class = [];
                $local_min_card = $mult[0][0];

                if ($local_min_card >= 1) {
                  for ($i = $local_min_card; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                    $left_max_class = ["equivalentclasses" => [["class_max" => [$classes[1], $link["name"], $i]],
          						            ["intersection" => [["class" => $classes[1]],
                          				                        ["maxcard" => [$i, ["inverse" => ["role" => $link["name"]]]]]]
          									]]
          			    ];
                  array_push($addit_max_class, $left_max_class);
                  }
                }
                elseif ($local_min_card == 0){
                  for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                    $left_max_class = ["equivalentclasses" => [["class_max" => [$classes[1], $link["name"], $i]],
                                  ["intersection" => [["class" => $classes[1]],
                                                          ["maxcard" => [$i, ["inverse" => ["role" => $link["name"]]]]]]
                            ]]
                    ];
                  array_push($addit_max_class, $left_max_class);
                  }
                }

                foreach ($addit_max_class as $addit_max_class_elem) {
                  array_push($left, $addit_max_class_elem);
                }
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
       Translate associations without class together with cardinalities.

       @param link A JSON object representing one association link with class.
    */
    protected function translate_association_with_class($link, $builder){

        $classes = $link["classes"];
        $roles = $link["roles"];
        $assoc_class = $link["associated_class"];
        $mult = $link["multiplicity"];

    		$assoc_with_class = [
    		 ["subclass" => [["exists" => [["role" => $roles[0]], ["top" => "owl:Thing"]]],["class" => $assoc_class["name"]]]],
         ["subclass" => [["exists" => [["inverse" => ["role" => $roles[0]]], ["top" => "owl:Thing"]]],["class" => $classes[0]]]],
         ["subclass" => [["exists" => [["role" => $roles[1]], ["top" => "owl:Thing"]]],["class" => $assoc_class["name"]]]],
         ["subclass" => [["exists" => [["inverse" => ["role" => $roles[1]]], ["top" => "owl:Thing"]]],["class" => $classes[1]]]],
         ["subclass" => [
           ["class" => $assoc_class["name"]],
           ["intersection" => [
             ["exists" => [["role" => $roles[0]], ["top" => "owl:Thing"]]],
             ["maxcard" => [1, ["role" => $roles[0]]]],
             ["exists" => [["role" => $roles[1]], ["top" => "owl:Thing"]]],
             ["maxcard" => [1, ["role" => $roles[1]]]]
           ]]]]
    		 ];


         // [1..1,0..2] $mult[1]=0..2=right, $mult[0]=1..1=left
         //$mult[1][0] = 0, $mult[1][1] = ., $mult[1][2] = ., $mult[1][3] = 2

         $right = [];

         // 1..*/2..*/.../9..*
         if (($mult[1][0] > 0) && ($mult[1][3] == "*")) {
           $right = [
                   ["subclass" => [["class" => $classes[0]],
                                 ["mincard" => [$mult[1][0], ["inverse" => ["role" => $roles[0]]]]]]
                   ]];
         // 0..1/0..2/.../0..9
         } elseif (($mult[1][0] == 0) && ($mult[1][3] > 0)) {
             $right = [
               ["subclass" => [["class" => $classes[0]],
                         ["maxcard" => [$mult[1][3], ["inverse" => ["role" => $roles[0]]]]]]
                         ]];
         // 1..1/2..4/.../9..9
           } elseif (($mult[1][0] > 0) && ($mult[1][3] > 0)) {
             $right = [
                 ["subclass" => [["class" => $classes[0]],
                               ["mincard" => [$mult[1][0], ["inverse" => ["role" => $roles[0]]]]]]
                 ],
                 ["subclass" => [["class" => $classes[0]],
                               ["maxcard" => [$mult[1][3], ["inverse" => ["role" => $roles[0]]]]]]
                 ]];
           }


         $left = [];

         // 1..*/2..*/.../9..*
         if (($mult[0][0] > 0) && ($mult[0][3] == "*")) {
           $left = [
                   ["subclass" => [["class" => $classes[1]],
                                 ["mincard" => [$mult[0][0], ["inverse" => ["role" => $roles[1]]]]]]
                   ]];
         // 0..1/0..2/.../0..9
         } elseif (($mult[0][0] == 0) && ($mult[0][3] > 0)) {
             $left = [
                     ["subclass" => [["class" => $classes[1]],
                                   ["maxcard" => [$mult[0][3], ["inverse" => ["role" => $roles[1]]]]]]
                     ]];
         // 1..1/2..4/.../9..9
           } elseif (($mult[0][0] > 0) && ($mult[0][3] > 0)) {
             $left = [
                     ["subclass" => [["class" => $classes[1]],
                                   ["mincard" => [$mult[0][0], ["inverse" => ["role" => $roles[1]]]]]]
                     ],
                     ["subclass" => [["class" => $classes[1]],
                                   ["maxcard" => [$mult[0][3], ["inverse" => ["role" => $roles[1]]]]]]
                     ]];
           }

           if ($this->with_min_max){

                 $addit_min_class = [];
                 $local_max_card = $mult[1][3];

                 if ($local_max_card != "*"){
                   for ($i = 1; $i <= $local_max_card; $i++) {
                     $right_min_class = ["equivalentclasses" => [["class_min" => [$classes[0], $roles[0], $i]],
                                       ["intersection" => [["class" => $classes[0]],
                                                               ["mincard" => [$i, ["inverse" => ["role" => $roles[0]]]]]]
                                 ]]
                               ];
                     array_push($addit_min_class, $right_min_class);
                   }
                 }
                 elseif ($local_max_card == "*"){
                   for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                     $right_min_class = ["equivalentclasses" => [["class_min" => [$classes[0], $roles[0], $i]],
                                       ["intersection" => [["class" => $classes[0]],
                                                               ["mincard" => [$i, ["inverse" => ["role" => $roles[0]]]]]]
                                 ]]
                               ];
                     array_push($addit_min_class, $right_min_class);
                   }
                 }

                 foreach ($addit_min_class as $addit_min_class_elem) {
                   array_push($right, $addit_min_class_elem);
                 }

                 $addit_max_class = [];
                 $local_min_card = $mult[1][0];

                 if ($local_min_card > 0){

                   for ($i = $local_min_card; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                     $right_max_class = ["equivalentclasses" => [["class_max" => [$classes[0], $roles[0], $i]],
                                     ["intersection" => [["class" => $classes[0]],
                                                             ["maxcard" => [$i, ["inverse" => ["role" => $roles[0]]]]]]
                               ]]
                             ];
                     array_push($addit_max_class, $right_max_class);
                   }
                 }
                 elseif ($local_min_card == 0) {
                   for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                     $right_max_class = ["equivalentclasses" => [["class_max" => [$classes[0], $roles[0], $i]],
                                     ["intersection" => [["class" => $classes[0]],
                                                             ["maxcard" => [$i, ["inverse" => ["role" => $roles[0]]]]]]
                               ]]
                             ];
                     array_push($addit_max_class, $right_max_class);
                   }
                 }

                 foreach ($addit_max_class as $addit_max_class_elem) {
                   array_push($right, $addit_max_class_elem);
                 }


               $addit_min_class = [];
               $local_max_card = $mult[0][3];

               if ($local_max_card != "*"){
                 for ($i = 1; $i <= $local_max_card; $i++) {
                   $left_min_class = ["equivalentclasses" => [["class_min" => [$classes[1], $roles[1], $i]],
                                   ["intersection" => [["class" => $classes[1]],
                                                           ["mincard" => [$i, ["inverse" => ["role" => $roles[1]]]]]]
                             ]]
                              ];
                    array_push($addit_min_class, $left_min_class);
                  }
               }
               elseif ($local_max_card == "*"){
                 for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                   $left_min_class = ["equivalentclasses" => [["class_min" => [$classes[1], $roles[1], $i]],
                                   ["intersection" => [["class" => $classes[1]],
                                                           ["mincard" => [$i, ["inverse" => ["role" => $roles[1]]]]]]
                             ]]
                              ];
                    array_push($addit_min_class, $left_min_class);
                  }
               }

               foreach ($addit_min_class as $addit_min_class_elem) {
                 array_push($left, $addit_min_class_elem);
               }

               $addit_max_class = [];
               $local_min_card = $mult[0][0];

               if ($local_min_card > 0) {
                 for ($i = $local_min_card; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                   $left_max_class = ["equivalentclasses" => [["class_max" => [$classes[1], $roles[1], $i]],
                                 ["intersection" => [["class" => $classes[1]],
                                                         ["maxcard" => [$i, ["inverse" => ["role" => $roles[1]]]]]]
                           ]]
                   ];
                 array_push($addit_max_class, $left_max_class);
                 }
               }
               elseif ($local_min_card == 0){
                 for ($i = 1; $i <= UMLCrowd::MAX_CARDINALITY; $i++) {
                   $left_max_class = ["equivalentclasses" => [["class_max" => [$classes[1], $roles[1], $i]],
                                 ["intersection" => [["class" => $classes[1]],
                                                         ["maxcard" => [$i, ["inverse" => ["role" => $roles[1]]]]]]
                           ]]
                   ];
                 array_push($addit_max_class, $left_max_class);
                 }
               }

               foreach ($addit_max_class as $addit_max_class_elem) {
                 array_push($left, $addit_max_class_elem);
               }
          }




      		foreach ($right as $rightelem) {
      			array_push($assoc_with_class, $rightelem);
      		}


      		foreach ($left as $leftelem) {
      			array_push($assoc_with_class, $leftelem);
      		}


      		$builder->translate_DL($assoc_with_class);


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

        // Translate a composed generalization (without constraints)
        if (count($link["classes"]) > 1) {
          $union = [];
          foreach ($link["classes"] as $classunion){
              array_push($union, ["class" => $classunion]);
          }
          $lst = [["subclass" => [
            ["union" => $union],
            ["class" => $parent]
          ]]];
          $builder->translate_DL($lst);

        }

        // Translate the covering constraint
        if (in_array("covering", $link["constraint"])){
            $covering = [];
            foreach ($link["classes"] as $classcovering){
                array_push($covering, ["class" => $classcovering]);
            }
            $gencov = [["subclass" => [
                ["class" => $parent],
                ["union" => $covering]
            ]]];
            $builder->translate_DL($gencov);
        }

        // Translate the disjoint constraint
        if (in_array("disjoint", $link["constraint"])){
            $disjoint = [];
            foreach ($link["classes"] as $classdisj){
                array_push($disjoint, ["class" => $classdisj]);
            }
            $gendisj = [["disjointclasses" => $disjoint]];
            $builder->translate_DL($gendisj);
        }

    }

    /**
       Translate attributes from a JSON string using the given builder.
       @param json A JSON object, the result from a decoded JSON
       String.
       @return false if no "attribute" part has been provided.
     */
    protected function translate_attributes($json, $builder){
      if (! array_key_exists("classes", $json)){
          return false;
      }
      $js_classes = $json["classes"];

      foreach ($js_classes as $class){
        $class_name = $class["name"];
        $attr_list = $class["attrs"];

        foreach ($attr_list as $attr_el) {
          $el = [["data_domain" => [
                  ["data_domain_exists" => [
                    ["data_role" => $attr_el["name"]]]],
                    ["class" => $class_name]]],
                 ["data_range" => [
                   ["data_range_exists" => [
                    ["data_range_inverse" =>
                        ["data_role" => $attr_el["name"]]]]],
                  ["datatype" => $attr_el["datatype"]]]],
                ["subclass" => [
                  ["class" => $class_name],
                  ["data_maxcard" =>
                    [1,
                    ["data_role" => $attr_el["name"]]]]]]
              ];
          $builder->translate_DL($el);
        }
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
            case "association with class":
                $this->translate_association_with_class($link, $builder);
                break;
            case "generalization":
                $this->translate_generalization($link, $builder);
                break;
            }
        }

    }




    /**
    Decoding classes and building JSON UML primitives
    @param extractor is a set of axioms extracted from an OWL 2 document using SPARQL-DL
    @param builder is a UMLJSONBuilder
    */

    public function decode_classes($extractor, $builder){
      $classes = $extractor->returnClassAxioms();

      foreach ($classes as $class){
        $st_name = $extractor->remove_prefixExpansion($class);

        if ((!strcmp($st_name,"Thing") == 0) && (!strcmp($st_name,"Nothing") == 0) &&
            (!$this->is_crowd_class($class)) && (!$this->is_crowd_class($class))){

              $builder->insert_class($class);
        }

      }
    }

    /**
    This function identifies classes generated for the crowd strategy.
    For instance: "http://crowd.fi.uncoma.edu.ar/Class_R_max" and "http://crowd.fi.uncoma.edu.ar/Class_R_min"
    */

    protected function is_crowd_class($class){

      $exp_min = "/^(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\_(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\_min/i";
      $exp_max = "/^(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\_(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\_max/i";

      if ((preg_match($exp_min, $class)) || (preg_match($exp_max, $class))) {

            return true;
      } else {
            return false;
      }
    }

    /**
    This function identifies classes generated for the crowd strategy.
    For instance: "http://crowd.fi.uncoma.edu.ar/Class_http://crowd.fi.uncoma.edu.ar/R_min_N"

    @note variable $matches in preg_match returns an array of subpatterns. For each crowd min and max class,
    the subpatterns are:
    - array[0]: full matching string "http://crowd.fi.uncoma.edu.ar/Class_http://crowd.fi.uncoma.edu.ar/R_min_N"
    - array[1]: first subpattern "http://crowd.fi.uncoma.edu.ar/Class"
    - array[5]: last subpattern "R"
    */

    protected function is_crowd_classMin($extractor, $class, $op){
      $st_name = $extractor->remove_prefixExpansion($op);
      $exp = "/^(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\_(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\/([a-zA-Z0-9\.\-\_]+)\_min/i";

      if (preg_match($exp, $class, $matches)) {
        if (strcasecmp($st_name, $matches[5]) == 0){
            return true;
        }
        else {
          return false;
        }
      } else {

            return false;
      }
    }

    /**
    This function identifies classes generated for the crowd strategy.
    For instance: "http://crowd.fi.uncoma.edu.ar/Class_R_max"
    */

    protected function is_crowd_classMax($extractor, $class, $op){
      $st_name = $extractor->remove_prefixExpansion($op);
      $exp = "/^(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\_(http:\/\/([a-zA-Z0-9\/\.\-\_\#])+)\/([a-zA-Z0-9\.\-\_]+)\_max/i";

      if (preg_match($exp, $class, $matches)) {
        if (strcasecmp($st_name, $matches[5]) == 0){
            return true;
        }
        else {
          return false;
        }
      } else {

            return false;
      }
    }

    /**
    Decoding subsumptions and building JSON UML primitives. This strategy does not build subsumptions with bottom.

    @param extractor is a set of axioms extracted from an OWL 2 document using SPARQL-DL
    @param builder is a UMLJSONBuilder
    */
    public function decode_subsumptions($extractor, $builder){
      $subs = $extractor->returnStrictSubClassAxioms();
      $unsat = $extractor->return_unsatisfiableClasses();

      foreach ($subs as $subclass){
        $class = $subclass["subclass"];

        if (!in_array($class[0], $unsat) && !in_array($class[1], $unsat)){

          $st_name = $extractor->remove_prefixExpansion($class[0]);
          $st_name1 = $extractor->remove_prefixExpansion($class[1]);

            if (((!strcmp($st_name,"Thing") == 0) && (!strcmp($st_name,"Nothing") == 0)) &&
              ((!strcmp($st_name1,"Thing") == 0) && (!strcmp($st_name1,"Nothing") == 0))
              && (!$this->is_crowd_class($class[0])) && (!$this->is_crowd_class($class[1]))){

                $builder->insert_subsumption([$class[0]], $class[1], []);
            }
        }
      }
    }

    protected function strictsub($subs, $without_thing, $extractor){
      $strictclass = "";

      foreach ($subs as $subclass){
        $class = $subclass["subclass"];
        $st_name = $extractor->remove_prefixExpansion($class[0]);
        $st_name1 = $extractor->remove_prefixExpansion($class[1]);

          if (((!strcmp($st_name,"Thing") == 0) && (!strcmp($st_name,"Nothing") == 0)) &&
            ((!strcmp($st_name1,"Thing") == 0) && (!strcmp($st_name1,"Nothing") == 0))){

              if ((in_array($class[0], $without_thing)) && (in_array($class[1], $without_thing))){
                $strictclass = $class[0];
              }
        }
      }
      return $strictclass;
    }

    /**
    Returns the strictest class domain for a given ObjectProperty
    */
    private function strictdomain_lookup($domains, $op, $subs, $extractor){
      $dom_classes = [];
      $objprop_dom = [];

      // get all domain classes
      foreach ($domains as $dom){

        if (array_key_exists($op, $dom)){
            array_push($objprop_dom, $dom[$op]);
        }
      }

      foreach ($objprop_dom as $class){
//        $st_name = $extractor->remove_prefixExpansion($class);

        if (!$this->is_crowd_class($class)){
          array_push($dom_classes, $class);
        }
      }

      $number_dom = count($dom_classes);

      if ($number_dom == 1){
        return $dom_classes;
      }
      else {
        $without_thing = [];

        foreach ($dom_classes as $class){
          $st_name_c = $extractor->remove_prefixExpansion($class);

          if ((!strcmp($st_name_c,"Thing") == 0) && (!strcmp($st_name_c,"Nothing") == 0)){
            array_push($without_thing, $class);
          }
        }
      }

      $after_thing = count($without_thing);

      if ($after_thing == 1) {
        return $without_thing[0];
      }
      else {
        $domainop = $this->strictsub($subs, $without_thing, $extractor);
        return $domainop;
      }
    }

    /**
    Returns the strictest class range for a given ObjectProperty
    */
    private function strictrange_lookup($ranges, $op, $subs, $extractor){
      $ran_classes = [];
      $objprop_ran = [];

      foreach ($ranges as $ran){

        if (array_key_exists($op, $ran)){
            array_push($objprop_ran, $ran[$op]);
        }
      }

      foreach ($objprop_ran as $class){
//        $st_name = $extractor->remove_prefixExpansion($class);

        if (!$this->is_crowd_class($class)){
          array_push($ran_classes, $class);
        }
      }

      $number_ran = count($ran_classes);

      if ($number_ran == 1){
        return $ran_classes[0];
      }
      else{
        $without_thing = [];

        foreach ($ran_classes as $class){
          $st_name_c = $extractor->remove_prefixExpansion($class);

          if ((!strcmp($st_name_c,"Thing") == 0) && (!strcmp($st_name_c,"Nothing") == 0)){
            array_push($without_thing, $class);
          }
        }
      }

      $after_thing = count($without_thing);

      if ($after_thing == 1) {
        return $without_thing[0];
      }
      else {
        $rangeop = $this->strictsub($subs, $without_thing, $extractor);
        return $rangeop;
      }
    }

    /**
    Returns cardinalities looking for min and max classes according to the specific crowd strategy

    @param $domain A String containing the class domain of the object property $op
    @param $range A String containing the class range of object property $op
    @param $eqclasses An Array containing the equivalent classes of min and max for the object property $op
    @param $op A String containing an object property

    @return $cardinality An Array with left and right cardinalities for the object property $op
    */

    public function cardinalities_lookup($extractor, $domain, $range, $eqclasses, $op){
      $cardinality = [];
      $right_min_card = null;
      $right_max_card = null;
      $left_min_card = null;
      $left_max_card = null;
      $left_card = null;
      $right_card = null;

      foreach ($eqclasses as $eq){
        $classes = $eq["equivalentclasses"];

        if ((strcmp($domain,$classes[0]) == 0) || (strcmp($domain,$classes[1]) == 0)){
          // get right cardinality
          if ((strcmp($domain,$classes[0]) == 0) && ($this->is_crowd_classMin($extractor, $classes[1], $op))){
            $right_min_card = "1";
          } elseif ((strcmp($domain,$classes[1]) == 0) && ($this->is_crowd_classMin($extractor, $classes[0], $op))){
            $right_min_card = "1";
          } elseif ((strcmp($domain,$classes[0]) == 0) && ($this->is_crowd_classMax($extractor, $classes[1], $op))){
            $right_max_card = "1";
          } elseif ((strcmp($domain,$classes[1]) == 0) && ($this->is_crowd_classMax($extractor, $classes[0], $op))){
            $right_max_card = "1";
          }

          if (($right_min_card == null)&&($right_max_card == null)){
            $right_card = null;
          }elseif ($right_min_card == null){
            $right_card = "0"."..".$right_max_card;
          }elseif ($right_max_card == null){
            $right_card = $right_min_card.".."."*";
          }else{
            $right_card = $right_min_card."..".$right_max_card;
          }
      }
      elseif ((strcmp($range,$classes[0]) == 0) || (strcmp($range,$classes[1]) == 0)){
        // get left cardinality
        if ((strcmp($range,$classes[0]) == 0) && ($this->is_crowd_classMin($extractor, $classes[1], $op))){
          $left_min_card = "1";
        } elseif ((strcmp($range,$classes[1]) == 0) && ($this->is_crowd_classMin($extractor, $classes[0], $op))){
          $left_min_card = "1";
        } elseif ((strcmp($range,$classes[0]) == 0) && ($this->is_crowd_classMax($extractor, $classes[1], $op))){
          $left_max_card = "1";
        } elseif ((strcmp($range,$classes[1]) == 0) && ($this->is_crowd_classMax($extractor, $classes[0], $op))){
          $left_max_card = "1";
        }

        if (($left_min_card == null)&&($left_max_card == null)){
          $left_card = null;
        }elseif ($left_min_card == null){
          $left_card = "0"."..".$left_max_card;
        }elseif ($left_max_card == null){
          $left_card = $left_min_card.".."."*";
        }else{
          $left_card = $left_min_card."..".$left_max_card;
        }
      }
    }

      $cardinality = [$left_card,$right_card];
      return $cardinality;

    }

    /**
    Decoding roles and building JSON UML association primitives
    @param extractor is a set of axioms extracted from an OWL 2 document using SPARQL-DL
    @param builder is an UMLJSONBuilder
    */
    public function decode_relationships($extractor, $builder){
      $subs = $extractor->returnStrictSubClassAxioms();
      $domains = $extractor->returnDomain();
      $ranges = $extractor->returnRange();
      $objprops = $extractor->returnObjectProperties();
      $eqclasses = $extractor->returnEqClasses();


      if (!is_null($objprops)){

        foreach ($objprops as $op){

          $domainop = $this->strictdomain_lookup($domains, $op, $subs, $extractor);
          $rangeop = $this->strictrange_lookup($ranges, $op, $subs, $extractor);

          if (!is_null($eqclasses)){
            $cardinality = $this->cardinalities_lookup($extractor, $domainop, $rangeop, $eqclasses, $op);
            $builder->insert_relationship([$domainop, $rangeop], $op, $cardinality);
          }
          else {
            $builder->insert_relationship([$domainop, $rangeop], $op);
          }
        }
      }
    }

    /**
    Decoding data properties and building JSON UML attributes primitives
    @param extractor is a set of axioms extracted from an OWL 2 document using SPARQL-DL
    @param builder is an UMLJSONBuilder
    */
    public function decode_attributes($extractor, $builder){
      $subs = $extractor->returnStrictSubClassAxioms();
      $domains = $extractor->returnDataPropertyDomain();
      $ranges = $extractor->returnDataPropertyRange();
      $dataprops = $extractor->returnDataProperties();

      if (!is_null($dataprops)){

        foreach ($dataprops as $dp){

          $domaindp = $this->strictdomain_lookup($domains, $dp, $subs, $extractor); // domaindp is the class of the attribute dp
          $rangedp = $this->strictrange_lookup($ranges, $dp, $subs, $extractor); // rangedp is the datatype of the attribute dp
          $builder->insert_attribute($dp, $domaindp, $rangedp);
        }
      }
    }


    /**
    Decoding equivalence axioms and building JSON primitives to append to UML JSON as non-graphicable.
    @param extractor is a set of axioms extracted from an OWL 2 document using SPARQL-DL
    @param builder is an UMLJSONBuilder
    @note UML does not include any graphical primitive for OWL 2 equivalence axioms
    */
    public function decode_equivalences($extractor, $builder){
    }


    /**
    Decoding disjoint axioms and building JSON primitives to append to UML JSON as non-graphicable.
    @param extractor is a set of axioms extracted from an OWL 2 document using SPARQL-DL
    @param builder is an UMLJSONBuilder
    @note UML does not include any graphical primitive for OWL 2 disjoint axioms
    */
    public function decode_disjointness($extractor, $builder){
    }

    /**
    Decoding role hierarchies and building JSON primitives to append to UML JSON as non-graphicable.
    @param extractor is a set of axioms extracted from an OWL 2 document using SPARQL-DL
    @param builder is an UMLJSONBuilder
    @note UML does not include any graphical primitive for OWL 2 subobjectproperty axioms
    */
    public function decode_rolehierarchy($extractor, $builder){
    }





    /**
    Following functions process the RACER outputs in order to get explicit and implicit cardinalities
    by using crowd strategy where min and max classes are asserted before feeding the reasoner.
    */


    /**
    A set of min (max) classes become equivalent to their parents when cardinality is fulfilled.
    So the the greater one for min and the minor for max are chosen from cardinality array.

    @see This class should be refactored if max cardinality is changed to greater than 9

    @param $min_arr {Array} An array of min equivalent classes
    @param $max_arr {Array} An array of max equivalent classes
    */
    protected function get_crowd_cardinalities($min_arr, $max_arr){
      $f_min = null;
      $f_max = null;
      $c = [];

      if (count($min_arr) > 0){
        $f_min = 1;
        foreach ($min_arr as $mi){
          $class_min_array = str_split($mi);
          $l = count($class_min_array);
          $nro = $class_min_array[$l - 1];

          if ($nro >= $f_min){
            $f_min = $nro;
          }
        }
      }

      if (count($max_arr) > 0){
        $f_max = 9;
        foreach ($max_arr as $ma){
          $class_max_array = str_split($ma);
          $l = count($class_max_array);
          $nro = $class_max_array[$l - 1];

          if ($nro <= $f_max){
            $f_max = $nro;
          }
        }
      }

      if (($f_min == null) && ($f_max == null)){
        $c = null;
      }
      else{
        $c = [$f_min, $f_max];
      }
      return $c;
  }


    /**
    This function returns the right cardinality for $op and $domain class

    @param $domain {String} Domain class for $op
    @param $eqc_to_domain {Array} Equivalent classes to domain (min and max ones)
    @param $op {String} Binary association name (Object Property)
    @return An array of cardinalities [M,N]
    */
    public function r_cardinalities_lookupOWLLINK($domain, $eqc_to_domain, $op){
      $cardinality = [];
      $extractor = new OntoExtractor();
      $min_arr = [];
      $max_arr = [];

      foreach ($eqc_to_domain as $eq){

        if ((strcmp($domain, $eq) == 0)){
          $i_eqc = $eqc_to_domain;
          $min_arr = [];
          $max_arr = [];

          foreach ($i_eqc as $c_equiv){

            if ((strcmp($c_equiv, $domain) !== 0)){
              if ($this->is_crowd_classMin($extractor, $c_equiv, $op)){
                array_push($min_arr, $c_equiv);
              }

              if ($this->is_crowd_classMax($extractor, $c_equiv, $op)){
                array_push($max_arr, $c_equiv);
              }
            }
          }
        }
      }

      $cardinality = $this->get_crowd_cardinalities($min_arr, $max_arr);
      return $cardinality;
    }

    /**
    This function returns the left cardinality for $op and $range class

    @param $range {String} Range class for $op
    @param $eqc_to_range {Array} Equivalent classes to range (min and max ones)
    @param $op {String} Binary association name (Object Property)
    @return An array of cardinalities [M,N]
    */
    public function l_cardinalities_lookupOWLLINK($range, $eqc_to_range, $op){
      $cardinality = [];
      $extractor = new OntoExtractor();
      $min_arr = [];
      $max_arr = [];

      foreach ($eqc_to_range as $eq){

        if ((strcmp($range, $eq) == 0)){
          $i_eqc = $eqc_to_range;
          $min_arr = [];
          $max_arr = [];

          foreach ($i_eqc as $c_equiv){

            if ((strcmp($c_equiv, $range) !== 0)){
              if ($this->is_crowd_classMin($extractor, $c_equiv, $op)){
                array_push($min_arr, $c_equiv);
              }

              if ($this->is_crowd_classMax($extractor, $c_equiv, $op)){
                array_push($max_arr, $c_equiv);
              }
            }
          }
        }
      }

      $cardinality = $this->get_crowd_cardinalities($min_arr, $max_arr);
      return $cardinality;
  }

    /**
    Comparing cardinalities and looking for new ones after reasoning and extracting new OWL 2

    @param $json_o {JSON} JSON representing original diagram
    @param $json_new {JSON} JSON representing new diagram extracted from a resulting OWL by means of SPARQL-DL
    @param $jsonbuider {Object} Object to work with JSON diagram primitives
    @return An array of associations with implicit cardinalities (if any). Otherwise, it returns an empty array.
    */
    function compare_cardinalities($json_o, $json_new, $jsonbuilder){
      $b_assoclinks_o = [];
	    $unsat_classes = [];

      $b_assoclinks_o = $jsonbuilder->get_product()->get_bassoc_links($json_o);
      $unsat_classes = $this->qapack->get_unsatClasses();

//      $wc_assoclinks_o = $jsonbuilder->get_product()->get_wClassassoc_links($json_o);

      $n_bassoc_inferred_cards = [];

      // binary
      foreach($b_assoclinks_o as $bassoc_o){
        $equivsTo_f = [];
        $equivsTo_t = [];

        $b_name = $jsonbuilder->get_product()->get_bassoc_name($bassoc_o);

        $class_f = $jsonbuilder->get_product()->get_classfrom_bassoc($bassoc_o);
        $class_t = $jsonbuilder->get_product()->get_classto_bassoc($bassoc_o);

        $equivsTo_f = $this->qapack->get_equiv($class_f);
        $equivsTo_t = $this->qapack->get_equiv($class_t);

        $a = [];
        if ((!in_array($class_f, $unsat_classes)) && (!in_array($class_t, $unsat_classes))){

          $r_cards = $this->r_cardinalities_lookupOWLLINK($class_f, $equivsTo_f, $b_name);
          $l_cards = $this->l_cardinalities_lookupOWLLINK($class_t, $equivsTo_t, $b_name);

          $a = $jsonbuilder->get_product()->edit_cardinalities($bassoc_o, $r_cards, $l_cards);


        }

        /*elseif ((!in_array($class_f, $unsat_classes)) && in_array($class_t, $unsat_classes)){
            $r_cards = $this->r_cardinalities_lookupOWLLINK($class_f, $equivsTo_f, $b_name);
            $a = $jsonbuilder->get_product()->edit_cardinalities($bassoc_o, $r_cards, []);

          } elseif (in_array($class_f, $unsat_classes) && (!in_array($class_t, $unsat_classes))) {
                $l_cards = $this->l_cardinalities_lookupOWLLINK($class_t, $equivsTo_t, $b_name);
                $a = $jsonbuilder->get_product()->edit_cardinalities($bassoc_o, [], $l_cards);

            } elseif (in_array($class_f, $unsat_classes) && in_array($class_t, $unsat_classes)) {
                $a = $jsonbuilder->get_product()->edit_cardinalities($bassoc_o, [], []);
			  } */

        if (count($a) != 0){
          array_push($n_bassoc_inferred_cards, $a);
        }
      }

      return $n_bassoc_inferred_cards;
   }


    // answer "equivalences":[
    //                        ["http:\/\/crowd.fi.uncoma.edu.ar#Class1","http:\/\/crowd.fi.uncoma.edu.ar#Class2"],
    //                        ["http:\/\/crowd.fi.uncoma.edu.ar#Class1","http:\/\/crowd.fi.uncoma.edu.ar#Class2"]]
    /**
    Merge answer puts together results from SPARQL-DL and Racer/Konclude to get a resulting diagram
    It calls its parent to get implicit subsumptions and here processes associations for implicit cardinalities.

    @param $json_o {JSON} JSON representing original diagram
    @param $json_new {JSON} JSON representing new diagram extracted from a resulting OWL by means of SPARQL-DL
    @return An answer
    @see Answer
    */
    function merge_answer($json_o, $json_new){
      parent::merge_answer($json_o, $json_new);
      $inferred_c = $this->compare_cardinalities($json_o, $json_new, new UMLJSONBuilder());
      $this->qapack->incorporate_inferredCards($inferred_c);

      return $this->get_answer();
    }

}
