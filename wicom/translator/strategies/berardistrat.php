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

require_once __DIR__ . '/qapackages/berardipack.php';
require_once __DIR__ . '/strategy.php';
require_once __DIR__ . '/uml.php';

use Wicom\Translator\Strategies\QAPackages\BerardiPack;

/**
   I implement the method explained on "Reasoning on UML Class Diagrams" by
   Daniela Berardi, Diego Calvanesse and Giuseppe De Giacomo.

   @see Translator class for description about the JSON format.
 */
class Berardi extends UML{


    function __construct(){
        parent::__construct();

        $this->qapack = new BerardiPack();
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
        if (! array_key_exists("links", $json)){
            return false;
        }
        $js_links = $json["links"];
        foreach ($js_links as $link){
            switch ($link["type"]){
            case "association":
                $this->translate_association($link, $builder);
                break;
            case "generalization":
                $this->translate_generalization($link, $builder);
                break;
            }
        }

    }
}
