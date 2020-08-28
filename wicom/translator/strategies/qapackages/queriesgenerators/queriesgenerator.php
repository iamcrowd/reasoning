<?php
/*

   Copyright 2016 GILIA.

   Author: Giménez, Christian. Braun, Germán

   queriesgenerator.php

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

namespace Wicom\Translator\Strategies\QAPackages\QueriesGenerators;

/**
   Superclass for all the queries generators available.
 */
abstract class QueriesGenerator{
    function __construct(){
    }

    /**
       Generate on the $builder all the queries for this generator.
       This module generates common queries for any encoding.

       @param $json_str A String in JSON format for the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder subclass.
     */
    function generate_all_queries($json_str, $builder){
      $this->gen_satisfiable($builder);
      $this->gen_class_satisfiable($json_str, $builder);
      $this->gen_objectProperty_satisfiable($json_str, $builder);
      $this->gen_dataProperty_satisfiable($json_str, $builder);
      $this->gen_subClassHierarchy($builder);
      $this->gen_getDisjointClasses($json_str, $builder);
      $this->gen_getEquivalentClasses($json_str, $builder);
      $this->gen_getDisjointObjProp($json_str, $builder);
      $this->gen_getEquivalentObjProp($json_str, $builder);
      $this->gen_getDisjointDataProp($json_str, $builder);
      $this->gen_getEquivalentDataProp($json_str, $builder);    
      $this->gen_getPrefixes($builder);
//      $this->gen_subObjectPropertyHierarchy($builder);
    }


    /**
       I generate queries for checking diagram satisfability.

       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
     */
    function gen_satisfiable($builder){
        $builder->insert_satisfiable();
    }

    function gen_getPrefixes($builder){
      $builder->insert_getPrefixes();
    }

    /**
       I generate queries for checking satisfability per each class
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_class_satisfiable($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $jelem) {
            $builder->insert_satisfiable_class($jelem["name"]);
        }
    }

    /**
       I generate queries for checking satisfability for each objectProperty (role)
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
       @todo add roles for n-ary assocs. Now only generates queries for UML binary associations
    */
    function gen_objectProperty_satisfiable($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];
        $json_links = $json["links"];

        foreach ($json_links as $link) {
          switch ($link["type"]){
          case "association":
              $builder->insert_satisfiable_objectProperty($link["name"]);
              break;
          }
        }
    }

    /**
       I generate queries for checking satisfability for each attribute
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_dataProperty_satisfiable($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $jelem) {
          $attr_list = $jelem["attrs"];

          foreach ($attr_list as $attr) {
            $builder->insert_satisfiable_dataProperty($attr["name"]);
          }
        }
    }


    /**
       Generate queries for checking disjointness in each class
       of the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getDisjointClasses($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $jelem) {
            $builder->insert_get_disjointClasses_query($jelem["name"]);
        }
    }

    /**
       Generate queries for checking class equivalence in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getEquivalentClasses($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $jelem) {
            $builder->insert_get_equivalentClasses_query($jelem["name"]);
        }
    }

    /**
       Generate query for getting subclasses hierarchy.

       @see gen_class_satisfiable() for parameters.
     */
    function gen_subClassHierarchy($builder){
        $builder->insert_getSubClassHierarchy_query();
    }

    /**
       Generate query for getting subObjectProperties hierarchy.

       @see gen_class_satisfiable() for parameters.
     */
    function gen_subObjectPropertyHierarchy($builder){
        $builder->insert_getSubObjectPropertyHierarchy_query();
    }


    /**
       Generate queries for checking for entailed classes. Entailed applies just for EquivalentClasses axiom

       @see gen_class_satisfiable() for parameters.
       @see get_entailedDirect_classes for class axioms diferent from EquivalentClasses
     */
    function gen_entailed_EquivalentClasses($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        $i = 0;

        while ($i < count($json_classes) - 1){
            $j = $i + 1;
            while ($j < count($json_classes)) {
              $array_en = [];
              array_push($array_en, $json_classes[$i]["name"], $json_classes[$j]["name"]);
              $builder->insert_isEntailed_query($array_en);
              $j++;
            }
            $i++;
        }
    }

    /**
       Generate queries for checking for entailedDirect classes. EntailedDirect applies for
       DisjointClasses, SubClassOf and SubObjectPropertyOf axiom

       @see gen_class_satisfiable() for parameters.
     */
    function gen_entailedDirect_SubClasses($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        $i = 0;

        while ($i < count($json_classes) - 1){
            $j = $i + 1;
            while ($j < count($json_classes)) {
              $array_en = [];
              array_push($array_en, $json_classes[$i]["name"], $json_classes[$j]["name"]);
              $builder->insert_isEntailedDirectSubClasses_query($array_en);
              $j++;
            }
            $i++;
        }
    }

    /**
    Generate queries for checking for entailedDirect classes. EntailedDirect applies for
    DisjointClasses, SubClassOf and SubObjectPropertyOf axiom

    @see gen_class_satisfiable() for parameters.
     */
    function gen_entailedDirect_DisjointClasses($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        $i = 0;

        while ($i < count($json_classes) - 1){
            $j = $i + 1;
            while ($j < count($json_classes)) {
              $array_en = [];
              array_push($array_en, $json_classes[$i]["name"], $json_classes[$j]["name"]);
              $builder->insert_isEntailedDirectDisjointClasses_query($array_en);
              $j++;
            }
            $i++;
        }
    }

    /**
    Generate queries for checking for entailedDirect classes. EntailedDirect applies for
    DisjointClasses, SubClassOf and SubObjectPropertyOf axiom

    @see gen_class_satisfiable() for parameters.
     */
    function gen_entailedDirect_SubObjPropertyOf($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        $i = 0;

        while ($i < count($json_classes) - 1){
            $j = $i + 1;
            while ($j < count($json_classes)) {
              $array_en = [];
              array_push($array_en, $json_classes[$i]["name"], $json_classes[$j]["name"]);
              $builder->insert_isEntailedDirectSubObjPropertyOf_query($array_en);
              $j++;
            }
            $i++;
        }
    }



}

?>
